<script setup>
import TodoItem from "@/components/TodoItem.vue";

defineProps({
  todos: {
    type: Array,
    default: () => [],
  },
  loading: {
    type: Boolean,
    default: false,
  },
  search: {
    type: String,
    default: "",
  },
  filter: {
    type: String,
    default: "all",
  },
});

defineEmits(["create", "toggle", "edit", "remove"]);
</script>

<template>
  <div v-if="loading" class="state-message">
    Loading tasks...
  </div>

  <div
    v-else-if="todos.length === 0"
    class="state-message empty-state"
  >
    <div class="empty-icon">✓</div>

    <h3>
      {{ search ? "No matching tasks" : "No tasks found" }}
    </h3>

    <p>
      {{
        search
          ? "Try a different search."
          : "Create your first task to get started."
      }}
    </p>

    <button
      v-if="!search && filter === 'all'"
      class="primary-button"
      @click="$emit('create')"
    >
      Add your first task
    </button>
  </div>

  <div v-else class="todo-list">
    <TodoItem
      v-for="todo in todos"
      :key="todo.id"
      :todo="todo"
      @toggle="$emit('toggle', todo)"
      @edit="$emit('edit', todo)"
      @remove="$emit('remove', todo)"
    />
  </div>
</template>
