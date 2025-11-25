<script setup lang="ts">
import Navbar from "../components/Navbar.vue";
import CreateBoardModal from "../components/CreateBoardModal.vue";
import DeleteBoardModal from "../components/DeleteBoardModal.vue";
import AddColumnModal from "../components/AddColumnModal.vue"; 
import ColumnMenu from "../components/ColumnMenu.vue";
import EditColumnModal from "../components/EditColumnModal.vue";
import DeleteColumnModal from "../components/DeleteColumnModal.vue";
import AddTaskModal from "../components/Task/AddTaskModal.vue";
import TaskCard from "../components/Task/TaskCard.vue";
import TaskDetailsModal from "../components/Task/TaskDetailsModal.vue";
import EditTaskModal from "../components/Task/EditTaskModal.vue";
import DeleteTaskModal from "../components/Task/DeleteTaskModal.vue";
import type { Column } from "../types/Column";
import type { Task } from "../types/Task";
import "../assets/boardView.css";
import { ref, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { api } from "../api";





const route = useRoute();
const router = useRouter();

const boardId = ref(Number(route.params.id));
const boardName = ref("");
const columns = ref<Column[]>([]);

const selectedListId = ref<number | null>(null);

const showColumnMenu = ref(false);
const showTaskDetails = ref(false);
const activeColumn = ref<Column | null>(null);
const selectedColumn = ref<Column | null>(null);
const selectedTask = ref<Task | null>(null);



// --- Modals ---
const showCreateModal = ref(false);
const showDeleteModal = ref(false);
const showColumnModal = ref(false);
const showEditColumnModal = ref(false);
const showDeleteColumnModal = ref(false);
const showTaskModal = ref(false);
const showEditTaskModal = ref(false);
const showDeleteTaskModal = ref(false);


// --- Open and close column menu ---
const openColumnMenu = (col: Column) => {

  if (showColumnMenu.value && activeColumn.value?.id === col.id) {
    showColumnMenu.value = false;
    activeColumn.value = null;
    return;
  }

  activeColumn.value = col;
  showColumnMenu.value = true;
};

onMounted(() => {
  window.addEventListener("click", () => (showColumnMenu.value = false));
});

// --- Open edit and delete column modals ---
const openEditModal = (col: Column) => {
  selectedColumn.value = col;
  showEditColumnModal.value = true;
};

const openDeleteModal = (col: Column) => {
  selectedColumn.value = col;
  showDeleteColumnModal.value = true;
};


// --- Open addTask modal ---
const openTaskModal = (listId: number) => {
  selectedListId.value = listId;
  showTaskModal.value = true;
};

// --- Open task details ---
const openTaskDetails = (task: Task) => {
  selectedTask.value = task;
  showTaskDetails.value = true;
};

// --- Open edit and delete task modals ---
const openEditTaskModal = (task: Task) => {
  selectedTask.value = task;
  showTaskDetails.value = true;
  showEditTaskModal.value = true;
};

const openDeleteTaskModal = (task: Task) => {
  selectedTask.value = task;
  showTaskDetails.value = true;
  showDeleteTaskModal.value = true;
};


// --- Load Current Board ---
const loadBoard = async (id: number) => {
    const res = await api.get(`/boards/${id}`);
    boardName.value = res.data.title;
};


// --- Load Columns ---
const loadColumns = async () => {
  const res = await api.get(`/boards/${boardId.value}/lists`);
  columns.value = res.data.map((col: any) => ({
    ...col,
    tasks: [],
  }));
};

// --- Load Tasks ---
const loadTasksForColumns = async () => {
  for (const col of columns.value) {
    const res = await api.get(`/lists/${col.id}/tasks`);
    col.tasks = res.data;
  }
};


onMounted(async () => {
  await loadBoard(boardId.value);
  await loadColumns();
  await loadTasksForColumns();
});

watch(() => route.params.id, async () => {
  boardId.value = Number(route.params.id);
  await loadBoard(boardId.value);
  await loadColumns();
  await loadTasksForColumns();
});


// --- Create Board ---
const createBoard = async (data: { title: string; description: string }) => {
  try {
    const res = await api.post("/boards", data);

    showCreateModal.value = false;

    router.push(`/boards/${res.data.id}`);
  } catch (err) {
    console.error("Failed to create board", err);
  }
};

// --- Delete Board ---
const deleteBoard = async () => {
  try {
    await api.delete(`/boards/${boardId.value}`);

    showDeleteModal.value = false;

    router.push("/boards");

  } catch (err) {
    console.error("Failed to delete board", err);
  }
};

// --- Create column ---
const createColumn = async (data: { title: string }) => {
  try {
    const res  = await api.post<Column>(`/boards/${boardId.value}/lists`, data);

    columns.value.push({
      ...res.data,
      tasks: [] as Task[]
    });
    showColumnModal.value = false;
  } catch (err) {
    console.error("Failed to create column:", err);
  }
};

// --- Edit column ---
const updateColumn = async (data: { id: number; title: string}) => {
  try {
    await api.put(`/lists/${data.id}`, { title: data.title });

    const col = columns.value.find(c => c.id === data.id);
    if (col) col.title = data.title;

    showEditColumnModal.value = false;
  } catch (err) {
    console.error("Failed to update column", err);
  }
};

// --- Delete column ---
const deleteColumn = async () => {
  if (!selectedColumn.value) return;

  try {
    await api.delete(`/lists/${selectedColumn.value.id}`);

    columns.value = columns.value.filter(c => c.id !== selectedColumn.value!.id);

    showDeleteColumnModal.value = false;
  } catch (err) {
    console.error("Failed to delete column", err);
  }
};


// --- Create task ---
const createTask = async (data :  Task) => {
  if (!selectedListId.value) return;

  const res = await api.post(`/lists/${selectedListId.value}/tasks`, data);

  const list = columns.value.find(c => c.id === selectedListId.value);

  if (list){
    list.tasks.push(res.data);
  }

  showTaskModal.value = false;
};

// --- Edit task ---
const updateTask = async (data: Task) => {
  try {
    const res = await api.put(`/tasks/${data.id}`, data);

    const updated = res.data;

    const col = columns.value.find(c => c.id === updated.list_id);
    if (!col) return;

    const index = col.tasks.findIndex(t => t.id === updated.id);
    if (index !== -1) {
      col.tasks[index] = updated; 
    }

    if (selectedTask.value && selectedTask.value.id === updated.id) {
      selectedTask.value = updated;
    } 

    showEditTaskModal.value = false;

  } catch (err) {
    console.error("Failed to update task", err);
  }
};

// --- Delete task ---
const deleteTask = async () => {
  if (!selectedTask.value) return;

  try {
    await api.delete(`/tasks/${selectedTask.value.id}`);

    for (const col of columns.value){
      col.tasks = col.tasks.filter(t => t.id !== selectedTask.value!.id);
    }

    showDeleteTaskModal.value = false;
    showTaskDetails.value = false;

    selectedTask.value = null;

  } catch (err) {
    console.error("Failed to delete task", err);
  }
};

</script>

<template>
  <div class="board-page">
    <Navbar />

    <div class="board-header">
      <h1 class="kanban-board-title">{{ boardName }}</h1>

      <div class="board-header-actions">
        <button class="btn btn-light" @click="showCreateModal = true">+ New board</button>

        <button class="btn btn-danger" @click="showDeleteModal =true">
          <img src="/src/assets/icons/delete.png" class="icon" />
        </button>
      </div>
  </div>

    
  <div class="columns-wrapper">
    <div
      class="column"
      v-for="col in columns"
      :key="col.id">

    <div class="column-header">
      <h3>{{ col.title }}</h3>
        <button class="dots-btn" @click.stop="openColumnMenu(col)">⋯</button>
        
        <ColumnMenu
          v-if="showColumnMenu && activeColumn && activeColumn.id === col.id"
          @edit="openEditModal(col)"
          @delete="openDeleteModal(col)"/>
          
    </div>

    <div class="column-content"></div>
      <TaskCard
        v-for="task in col.tasks"
        :key="task.id"
        :task="task"
        @click="openTaskDetails(task)"
      />
      <button class="add-item-btn" @click="openTaskModal(col.id)">+ Add item</button>
  </div>

      <button class="add-column-btn" @click="showColumnModal = true">+ Add column</button>
 </div>
 </div>


<CreateBoardModal
  v-if="showCreateModal"
  @close="showCreateModal = false"
  @create="createBoard"
/>

<DeleteBoardModal
    v-if="showDeleteModal"
    @close="showDeleteModal = false"
    @confirm="deleteBoard"
/>

<AddColumnModal
  v-if="showColumnModal"
  @close="showColumnModal = false"
  @create="createColumn"
/>

<EditColumnModal
  v-if="showEditColumnModal"
  :column="selectedColumn"
  @close="showEditColumnModal = false"
  @update="updateColumn"
/>

<DeleteColumnModal
  v-if="showDeleteColumnModal"
  @close="showDeleteColumnModal = false"
  @confirm="deleteColumn"
/>

<AddTaskModal
  v-if="showTaskModal"
  @close="showTaskModal = false"
  @create="createTask"
/>

<TaskDetailsModal
  v-if="showTaskDetails"
  :task="selectedTask"
  @close="showTaskDetails = false"
  @edit="openEditTaskModal"
  @delete="openDeleteTaskModal"
/>

<EditTaskModal
  v-if="showEditTaskModal && selectedTask"
  :task="selectedTask"
  @close="showEditTaskModal = false"
  @update="updateTask"
/>

<DeleteTaskModal
  v-if="showDeleteTaskModal && selectedTask"
  @close="showDeleteTaskModal = false"
  @confirm="deleteTask"
/>

</template>