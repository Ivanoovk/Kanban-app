<template>
  <div class="overlay" @click.self="$emit('close')">
    <div class="panel">
     
      <!-- HEADER -->
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

      <!-- CONTENT -->
      <div class="content">

        <!-- DESCRIPTION -->
        <section>
          <label>Description</label>
          <p class="wrapped">
            {{ task.description || "No description" }}
          </p>
        </section>

        <!-- PRIORITY -->
        <section>
          <label>Priority</label>
          <p class="priority" :class="task.priority">{{ task.priority }}</p>
        </section>

        <!-- DUE DATE -->
        <section>
          <label>Due date</label>
          <p>{{ task.due_date || "No due date" }}</p>
        </section>

        <!-- TAGS -->
        <section>
          <label>Tags</label>
          <p>{{ task.tags || "—" }}</p>
        </section>

        <!-- ATTACHMENTS -->
        <section>
          <label>Attachments</label>

          <div class="attachments-box">
            <p class="empty">No attachments</p>
          </div>

          <div class="attachments-actions">
            <button class="attach-btn">
              <img src="/src/assets/icons/attach.png" class="attach-icon" />
              Add attachment
            </button>
          </div>
        </section>

        <!-- COMMENTS WRITE AREA -->
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

<!-- COMMENT LIST SECTION -->
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

<script setup lang="ts">
import { ref } from "vue";
import type { Task } from "../../types/Task";

const props = defineProps<{
  task: Task;
}>();

const emit = defineEmits(["close", "edit", "delete"]);

const showMenu = ref(false);
const newComment = ref("");

const toggleMenu = () => {
  showMenu.value = !showMenu.value;
};
</script>

<style scoped>
/* BACKDROP */
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.25);
  display: flex;
  justify-content: flex-end;
  z-index: 1000;
}

/* MAIN PANEL */
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

/* HEADER */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
}

.title {
  word-break: break-word;
}

/* MENU BTN */
.menu {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
}

/* DROPDOWN */
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

/* CONTENT */
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

/* FIX DESCRIPTION WRAP */
.wrapped {
  white-space: pre-wrap;
  overflow-wrap: break-word;
}

/* PRIORITY COLORS */
.priority.low { color: #10b981; }
.priority.medium { color: #6366f1; }
.priority.high { color: #ef4444; }

/* ATTACHMENTS */
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

/* COMMENTS */
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

</style>