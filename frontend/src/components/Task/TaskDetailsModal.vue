
<script setup lang="ts">
import { ref, onMounted } from "vue";
import type { Task } from "../../types/Task";
import { api } from "../../api";

const props = defineProps<{
  task: Task;
}>();

const emit = defineEmits(["close", "edit", "delete"]);

const showMenu = ref(false);
const attachments = ref<any[]>([]);
const newComment = ref("");

const toggleMenu = () => {
  showMenu.value = !showMenu.value;
};


onMounted(async () => {
  loadAttachments();
});

const loadAttachments = async () => {
  try {
    const res = await api.get(`/tasks/${props.task.id}/attachments`);
    attachments.value = res.data.map((a: any) => ({
      ...a,
      url: `${import.meta.env.VITE_PUBLIC_API_URL}/storage/${a.file_path}`
    }));
  } catch {
    attachments.value = [];
  }
};

const uploadAttachment = async (e: any) => {
  const file = e.target.files[0];
  if (!file) return;

  const form = new FormData();
  form.append("file", file);

  const res = await api.post(`/tasks/${props.task.id}/attachments`, form, {
    headers: { "Content-Type": "multipart/form-data" }
  });

  attachments.value.push({
    ...res.data,
    url: `${import.meta.env.VITE_PUBLIC_API_URL}/storage/${res.data.file_path}`
  });
};

const removeAttachment = async (id: number) => {
  await api.delete(`/attachments/${id}`);
  attachments.value = attachments.value.filter(f => f.id !== id);
};
</script>







<template>
  <div class="overlay" @click.self="$emit('close')">
    <div class="panel">
     

      <div class="header">
        <h2 class="title">{{ task.title }}</h2>

        <button class="menu" @click="toggleMenu">⋯</button>

        <div v-if="showMenu" class="dropdown" @click.stop>
          <button @click="$emit('edit', task)" class="menu-btn">
            <img src="/src/assets/icons/pen.png" class="icon icon-pen" />
            Edit
          </button>
          <button @click="$emit('delete', task)" class="menu-btn danger">
            <img src="/src/assets/icons/delete.png" class="icon icon-trash" />
            Delete
          </button>
        </div>
      </div>


      <div class="content">

   
        <section>
          <label>Description</label>
          <p class="wrapped">
            {{ task.description || "No description" }}
          </p>
        </section>

        
        <section>
          <label>Priority</label>
          <p class="priority" :class="task.priority">{{ task.priority }}</p>
        </section>

        
        <section>
          <label>Due date</label>
          <p>{{ task.due_date || "No due date" }}</p>
        </section>

        
        <section>
          <label>Tags</label>
          <p>{{ task.tags || "—" }}</p>
        </section>

        
        <section>
          <label>Attachments</label>

          <div class="attachments-box">
            <template v-if="attachments.length">
            <div class="file-row" v-for="file in attachments" :key="file.id">
              <a :href="file.url" target="_blank">{{ file.file_name }}</a>
              <button @click="removeAttachment(file.id)" class="file-delete">✕</button>
            </div>
            </template>
          
            <p v-else class="empty">No attachments</p>
          </div>

          <div class="attachments-actions">
            <label class="attach-btn">
              <img src="/src/assets/icons/attach.png" class="attach-icon" />
                Add attachment
              <input type="file" @change="uploadAttachment" hidden />
            </label>
          </div>
        </section>

        
<section>
  <label>Comment</label>

  <textarea
    v-model="newComment"
    class="comment-input"
    placeholder="Write a comment..."
  ></textarea>

  <div class="comment-actions">
    <button class="send-btn">Send</button>
  </div>
</section>


<section>
  <h3 class="comments-title">Comment section</h3>

  <div class="comments-box">
    <p class="empty">No comments yet</p>
  </div>
</section>

      </div>

    </div>
  </div>
</template>


<style scoped>
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.25);
  display: flex;
  justify-content: flex-end;
  z-index: 1000;
}

.panel {
  width: 50vw;
  max-width: 700px;
  height: 100%;
  background: white;
  padding: 24px;
  box-shadow: -4px 0 14px rgba(0,0,0,0.12);
  overflow-y: auto;
  box-sizing: border-box;
}


.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
}

.title {
  word-break: break-word;
}

.menu {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
}

.dropdown {
  position: absolute;
  top: 36px;
  right: 0;
  background: white;
  border-radius: 8px;
  padding: 6px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  display: flex;
  flex-direction: column;
  z-index: 20;
}

.menu-btn {
  padding: 8px 12px;
  font-size: 14px;
  background: none;
  border: none;
  cursor: pointer;
  text-align: left;
  display: flex;
  align-items: center;
  gap: 8px;
}

.menu-btn:hover {
  background: #f2f2f2;
}

.icon {
  width: 16px;
  height: 16px;
  object-fit: contain;
}

.icon-pen {
  filter: none;
}

.icon-trash {
  filter: invert(45%) sepia(90%) saturate(3000%) hue-rotate(-10deg) brightness(90%) contrast(100%);
}

.danger {
  color: #e63946;
}


.content {
  margin-top: 20px;
  display: flex;
  flex-direction: column;
  gap: 22px;
}

section label {
  font-size: 12px;
  font-weight: 600;
  color: #6b7280;
}

section p {
  margin: 0;
  font-size: 14px;
}


.wrapped {
  white-space: pre-wrap;
  overflow-wrap: break-word;
}


.priority.low { color: #10b981; }
.priority.medium { color: #6366f1; }
.priority.high { color: #ef4444; }


.attachments-box {
  margin-top: 10px;
  padding: 12px;
  border-radius: 8px;
  background: #f9fafb;
}

.attachments-actions {
  margin-top: 8px;
  display: flex;
  justify-content: flex-end;
}

.attach-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 10px;
  background: #eef2ff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  color: #4f46e5;
}

.attach-btn:hover {
  background: #e0e7ff;
}

.attach-icon {
  width: 16px;
  height: 16px;
}

.empty {
  color: #9ca3af;
  font-size: 13px;
}


.comment-input {
  width: 100%;
  min-height: 90px;
  resize: vertical;
  padding: 10px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  box-sizing: border-box;
}

.comment-actions {
  margin-top: 8px;
  display: flex;
  justify-content: flex-end;
}

.send-btn {
  padding: 8px 14px;
  border: none;
  border-radius: 999px;
  background: #6366f1;
  color: white;
  cursor: pointer;
  font-weight: 600;
  font-size: 13px;
}

.send-btn:hover {
  background: #4f46e5;
}

.comments-box {
  margin-top: 12px;
  background: #f9fafb;
  padding: 12px;
  border-radius: 8px;
}

.comments-title {
  margin: 0 0 6px;
  text-align: center;
  font-size: 14px;
  font-weight: 600;
  color: #4b5563;
}




.file-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #eef2ff;
  padding: 6px 10px;
  border-radius: 6px;
  margin-bottom: 6px;
}

.file-row a { color: #4f46e5; text-decoration:none; font-size:14px; }
.file-delete { border:none; background:none; color:#e11d48; font-size:18px; cursor:pointer; }

.attach-btn {
  display:flex; align-items:center; gap:6px;
  background:#eef2ff; padding:8px 12px;
  border-radius:6px; cursor:pointer; font-size:14px; color:#4f46e5;
}

.attach-btn:hover { background:#e5e7ff; }
.attach-btn input { display:none; }

</style>