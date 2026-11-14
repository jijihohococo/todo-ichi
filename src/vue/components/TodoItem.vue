<template>
  <article
    class="todo-item"
    :class="{ completed: isCompleted(todo) }"
  >
    <button
      class="complete-button"
      :class="{ checked: isCompleted(todo) }"
      :aria-label="isCompleted(todo) ? 'Mark active' : 'Mark completed'"
      @click="$emit('toggle', todo)"
    >
      <span v-if="isCompleted(todo)">✓</span>
    </button>

    <div class="todo-content">
      <h3>{{ todo.title }}</h3>

      <p v-if="todo.description">
        {{ todo.description }}
      </p>

      <span class="todo-status">
        {{ isCompleted(todo) ? "Completed" : "Active" }}
      </span>
    </div>

    <div class="todo-actions">
      <button
        class="action-button"
        title="Edit"
        aria-label="Edit task"
        @click="$emit('edit', todo)"
      >
        Edit
      </button>

      <button
        class="action-button delete-button"
        title="Delete"
        aria-label="Delete task"
        @click="$emit('remove', todo)"
      >
        Delete
      </button>
    </div>
  </article>
</template>

<script setup>
import { isCompleted } from "@/utils/todoUtils";

defineProps({
  todo: {
    type: Object,
    required: true,
  },
});

defineEmits([
  "toggle",
  "edit",
  "remove",
]);
</script>