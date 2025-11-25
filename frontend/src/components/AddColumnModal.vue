
<script setup>
import { ref } from "vue";
const emit = defineEmits(["close", "create"]);
const title = ref("");

const submit = () => {
  if (!title.value.trim()) return;

  emit("create", { title: title.value.trim() });
  title.value = "";
};
</script>



<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal">
      <h2>Add Column</h2>

      <input
        class="input"
        placeholder="Column title..."
        v-model="title"
      />

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
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal {
  width: 340px;
  background: white;
  padding: 22px;
  border-radius: 12px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.input {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
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