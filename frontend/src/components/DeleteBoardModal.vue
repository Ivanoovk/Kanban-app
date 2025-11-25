<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal">
      <h2>Delete board?</h2>
      <p>This action cannot be undone!</p>

      <div class="buttons">
        <button class="btn danger" @click="$emit('confirm')">Delete</button>
        <button class="btn cancel" @click="$emit('close')">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script setup>
const emit = defineEmits(["close", "confirm"]);
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal {
  width: 340px;
  background: white;
  padding: 24px;
  border-radius: 12px;
  text-align: center;
}

.buttons {
  margin-top: 28px;
  display: flex;
  justify-content: center;
  gap: 12px;
}

.btn {
  padding: 10px 16px;
  border-radius: 999px;
  border: none;
  cursor: pointer;
  color: white;
}

.cancel {
  background: #e5e7eb;
  color: #374151;
}

.cancel:hover {
  background: #d1d5db;
}

.danger {
  background: #e63946;
}

.danger:hover {
  background: #c9333f;
}
</style>