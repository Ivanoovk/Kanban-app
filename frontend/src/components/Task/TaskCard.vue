<script setup lang="ts">
import type { Task } from "../../types/Task";

const props = defineProps<{
  task: Task;
}>();
</script>

<template>
  <div class="task-card">
    <div class="task-header">
      <h4 class="task-title">{{ task.title }}</h4>
      <span class="priority" :class="task.priority">{{ task.priority }}</span>
    </div>

    <p class="task-desc" v-if="task.description">
      {{ task.description }}
    </p>

    <div class="task-footer">
      <span class="due" v-if="task.due_date">📅 {{ task.due_date }}</span>
      <span class="tags" v-if="task.tags">🏷️ {{ task.tags }}</span>
    </div>
  </div>
</template>

<style scoped>
.task-card {
  background: #ffffff;
  padding: 12px 14px;
  margin-bottom: 10px;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.07);
  cursor: pointer;
  display: flex;
  flex-direction: column;
  gap: 6px;
  transition: all 0.15s ease;
  border: 1px solid #e5e7eb;
}

.task-card:hover {
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
  transform: translateY(-1px);
}

.task-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.task-title {
  margin: 0;
  font-size: 15px;
  font-weight: 600;
  color: #111827;
}

.task-desc {
  font-size: 13px;
  color: #4b5563;
  margin: 0;
  white-space: normal;  
  overflow: hidden;
}

.task-footer {
  display: flex;
  gap: 10px;
  font-size: 12px;
  color: #6b7280;
  margin-top: 4px;
}

.priority {
  padding: 3px 8px;
  font-size: 11px;
  border-radius: 8px;
  color: white;
  font-weight: 600;
  text-transform: capitalize;
}

.priority.low {
  background: #3b82f6; 
}

.priority.medium {
  background: #f59e0b; 
}

.priority.high {
  background: #ef4444; 
}

.tags {
  background: #eef2ff;
  padding: 2px 6px;
  border-radius: 6px;
  color: #4338ca;
  font-size: 11px;
}

.due {
  background: #f3f4f6;
  padding: 2px 6px;
  border-radius: 6px;
}
</style>