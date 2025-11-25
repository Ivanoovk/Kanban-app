<template>
  <div class="menu" @click.stop>
    <button @click="$emit('edit')" class="menu-btn">
      <img src="/src/assets/icons/pen.png" class="icon icon-pen" />
      Rename
    </button>

    <button @click="$emit('delete')" class="menu-btn danger">
      <img src="/src/assets/icons/delete.png" class="icon icon-trash" />
      Delete
    </button>
  </div>
</template>

<script setup>
defineEmits(["edit", "delete"]);
</script>

<style scoped>
.menu {
  position: absolute;
  top: 24px;
  right: 0;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  padding: 8px 0;
  display: flex;
  flex-direction: column;
  width: 140px;
  z-index: 20;
}

.menu-btn {
  width: 100%;
  padding: 8px 12px;
  font-size: 14px;
  text-align: left;
  background: none;
  border: none;
  cursor: pointer;

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
</style>