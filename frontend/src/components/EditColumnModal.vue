<script setup>
import { ref, watch } from "vue";

const props = defineProps({
  column: { type: Object, required: true }
});

const emit = defineEmits(["close", "update"]);

const localTitle = ref(props.column.title);

watch(
  () => props.column,
  (newCol) => {
    localTitle.value = newCol.title;
  }
);

const submitEdit = () => {
  emit("update", {
    id: props.column.id,
    title: localTitle.value,
  });
};
</script>

<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal">
      <h2>Rename Column</h2>

      <input
        v-model="localTitle"
        placeholder="Column Title"
        class="input"
      />

      <div class="actions">
        <button class="btn" @click="submitEdit">Save</button>
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
.input {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #d1d5db;
  padding: 10px;
  border-radius: 8px;
}
.actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
}
.btn {
  padding: 8px 14px;
  border-radius: 8px;
  border: none;
  background: #6366f1;
  color: white;
  cursor: pointer;
}
.cancel {
  background: #9ca3af;
}
</style>