<script setup lang="ts">
import { ref } from "vue";

const emit = defineEmits(["create", "close"]);

const title = ref("");
const description = ref("");

const submit = () => {
  emit("create", {
    title: title.value.trim(),
    description: description.value.trim(),
  });

  title.value = "";
  description.value = "";
};
</script>

<template>
  <div class="modal-overlay">
    <div class="modal">
      <h2>Create Board</h2>

      <input
        class="input"
        placeholder="Board title..."
        v-model="title"/>

      <textarea
        class="textarea"
        placeholder="Short description..."
        v-model="description"/>

      <div class="actions">
        <button class="btn" @click="submit">Create</button>
        <button class="btn cancel" @click="$emit('close')">Cancel</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}
.modal {
  width: 350px;
  padding: 20px;
  background: #fff;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.input, .textarea {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #d1d5db;
  padding: 10px;
  border-radius: 8px;
}
.textarea {
  resize: vertical;
  min-height: 100px;
}
.actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
}
.btn {
  padding: 8px 14px;
  border-radius: 999px;
  border: none;
  background: #6366f1;
  color: white;
  cursor: pointer;
}

.btn.primary:hover {
  background: #4f46e5;
}

.cancel {
  background: #e5e7eb;
  color: #374151;
}

.cancel:hover {
  background: #d1d5db;
}
</style>