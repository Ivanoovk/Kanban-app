<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import { api } from "../api";
import Navbar from "../components/Navbar.vue";
import CreateBoardModal from "../components/CreateBoardModal.vue";
import EditBoardModal from "@/components/EditBoardModal.vue";
import DeleteBoardModal from "@/components/DeleteBoardModal.vue";
import "../assets/yourBoards.css";

// Describes the structure of a board as returned by the backend.
interface Board { 
  id: number;
  title: string;
  description?: string | null;
}

const router = useRouter();

const boards = ref<Board[]>([]);
const userName = ref("");
const loadingBoards = ref(true);
const search = ref("");
const error = ref("");


const filteredBoards = computed(() => {
  return boards.value.filter((b) =>
    b.title.toLowerCase().includes(search.value.toLowerCase())
  );
});

// --- Load Current User ---
const loadUser = async () => {
  try {
    const res = await api.get("/user");
    userName.value = res.data.name ?? "";
  } catch {
    router.push("/login");
  }
};

// --- Load Boards ---
const loadBoards = async () => {
  loadingBoards.value = true;

  try {
    const res = await api.get("/boards");
    boards.value = res.data;
  } catch (err) {
    console.error("Failed to load boards:", err);
    boards.value = []; 
  }

  loadingBoards.value = false;
};

// --- Modals ---
const showCreateModal = ref(false);
const editingBoard = ref(null);
const deletingBoardId = ref(null);

// --- Create Board ---
const createBoard = async (data: { title: string; description: string }) => {
  if (!data.title) return;

  try {
    const res = await api.post("/boards", data);
    boards.value.push(res.data);
    showCreateModal.value = false;
  } catch (err) {
    console.error("Failed to create board:", err);
  }
};

// --- Open Board ---
const openBoard = (id: number) => {
  router.push(`/boards/${id}`);
};


// --- Edit Board ---
const updateBoard = async (data: { id: number; title: string; description: string }) => {
  try {
    const res = await api.put(`/boards/${data.id}`, data);

    // Update local array
    const index = boards.value.findIndex(b => b.id === data.id);
    if (index !== -1) boards.value[index] = res.data;

    editingBoard.value = null;
  } catch {
    error.value = "Failed to update board.";
  }
};


// --- Delete Board ---
const deleteBoard = async () => {
  try {
    await api.delete(`/boards/${deletingBoardId.value}`);

    boards.value = boards.value.filter(b => b.id !== deletingBoardId.value);

    deletingBoardId.value = null;
  } catch {
    error.value = "Failed to delete board.";
  }
};

onMounted(() => {
  loadUser();
  loadBoards();
});
</script>

<template>
  <div class="page">
    <Navbar :userName="userName" />

    <main class="main-wrapper">
      <div class="boards-card">
        <div class="boards-card-header">
          <h1>Your Boards</h1>

          <div class="create-inline">
            <input
              v-model="search"
              class="input"
              placeholder="Search boards..."
            />

            <button class="btn" @click="showCreateModal = true">
              + Create board
            </button>
          </div>
        </div>

        <div class="boards-card-body">
          <div v-if="loadingBoards" class="center-text">
            Loading...
          </div>

          <div v-else>
            <div v-if="boards.length === 0" class="no-boards">
              No boards yet.
            </div>

            <ul v-else class="boards-list">
              <li
                v-for="board in filteredBoards"
                :key="board.id"
                class="board-row"
              >
                <div class="board-info" @click="openBoard(board.id)">
                  <div class="board-title">{{ board.title }}</div>
                  <div class="board-description">
                    {{ board.description || "No description" }}
                  </div>
                </div>

                <div class="board-actions">
                  <button class="icon-btn" @click.stop="openBoard(board.id)">
                    <img src="/src/assets/icons/visible.png" class="icon" />
                  </button>
                  <button class="icon-btn" @click.stop="editingBoard = board">
                    <img src="/src/assets/icons/pen.png" class="icon" />
                  </button>
                  <button class="icon-btn danger" @click.stop="deletingBoardId = board.id">
                    <img src="/src/assets/icons/delete.png" class="icon" />
                  </button>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </main>

  <CreateBoardModal
    v-if="showCreateModal"
    @close="showCreateModal = false"
    @create="createBoard"
  />

  <EditBoardModal
    v-if="editingBoard"
    :board="editingBoard"
    @close="editingBoard = null"
    @update="updateBoard"
  />

  <DeleteBoardModal
    v-if="deletingBoardId"
    @close="deletingBoardId = null"
    @confirm="deleteBoard"
  />

  </div>
</template>