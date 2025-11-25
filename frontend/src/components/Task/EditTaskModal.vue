<script setup lang="ts">
import { ref, watch } from "vue";

const props = defineProps({
  task: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(["close", "update"]);

// local copy of the fields
const title = ref("");
const description = ref("");
const priority = ref<"low" | "medium" | "high">("medium");
const dueDate = ref<string | null>(null);
const tags = ref("");

// when the task is edited we update the fields
watch(
  () => props.task,
  (newTask) => {
    if (!newTask) return;

    title.value = newTask.title;
    description.value = newTask.description ?? "";
    priority.value = newTask.priority;
    dueDate.value = newTask.due_date ?? null;
    tags.value = newTask.tags ?? "";
  },
  { immediate: true }
);

const submit = () => {
  if (!title.value.trim()) return;

  emit("update", {
    id: props.task.id,
    title: title.value.trim(),
    description: description.value.trim() || null,
    priority: priority.value,
    due_date: dueDate.value || null,
    tags: tags.value.trim() || null,
  });
};
</script>

<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal">
      <h2 class="modal-title">Edit Task</h2>

      <div class="form-field">
        <label>Title <span class="required">*</span></label>
        <input v-model="title" class="input" placeholder="Task title..." />
      </div>

      <div class="form-field">
        <label>Description</label>
        <textarea
          v-model="description"
          class="textarea"
          placeholder="Describe the task..."
        ></textarea>
      </div>

      <div class="form-row">
        <div class="form-field">
          <label>Due date</label>
          <input v-model="dueDate" type="date" class="input" />
        </div>

        <div class="form-field">
          <label>Priority</label>
          <select v-model="priority" class="input">
            <option value="low">Low</option>
            <option value="medium">Medium (default)</option>
            <option value="high">High</option>
          </select>
        </div>
      </div>

      <div class="form-field">
        <label>Tags</label>
        <input
          v-model="tags"
          class="input"
          placeholder="e.g. frontend, bugfix"
        />
      </div>

      <div class="actions">
        <button class="btn primary" @click="submit">Save</button>
        <button class="btn secondary" @click="$emit('close')">Cancel</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  width: 780px;
  max-width: 95vw;
  background: #ffffff;
  padding: 24px 24px 20px;
  border-radius: 16px;
  box-shadow: 0 18px 45px rgba(15,23,42,0.18);
  display: flex;
  flex-direction: column;
  gap: 14px;
  box-sizing: border-box;
}

.modal-title {
  margin: 0 0 4px;
  font-size: 20px;
  font-weight: 700;
}

.form-field {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 4px;
  width: 100%;
}

.form-row {
  display: flex;
  gap: 12px;
  width: 100%;
}

.form-row .form-field {
  flex: 1;
}

label {
  font-size: 13px;
  font-weight: 600;
  color: #4b5563;
}

.required {
  color: #ef4444;
}

.input,
.textarea,
select {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  padding: 9px 11px;
  font-size: 14px;
  font-family: inherit;
  outline: none;
  transition: border-color 0.15s ease, box-shadow 0.15s ease;
  background: #f9fafb;
}

.input:focus,
.textarea:focus,
select:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.18);
  background: #ffffff;
}

.textarea {
  resize: vertical;
  min-height: 200px;
  max-height: 360px;
}

.actions {
  margin-top: 10px;
  display: flex;
  justify-content: flex-end;
  gap: 8px;
}

.btn {
  padding: 8px 16px;
  border-radius: 999px;
  border: none;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.btn.primary {
  background: #6366f1;
  color: white;
}

.btn.primary:hover {
  background: #4f46e5;
}

.btn.secondary {
  background: #e5e7eb;
  color: #374151;
}

.btn.secondary:hover {
  background: #d1d5db;
}
</style>