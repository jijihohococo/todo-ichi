<template>
  <section class="todo-table">
    <TodoStats
      :total="todos.length"
      :active="activeCount"
      :completed="completedCount"
    />

    <div v-if="error" class="error-message">
      {{ error }}
      <button @click="clearError">×</button>
    </div>

    <section class="todo-section">
      <TodoToolbar
        v-model:search="search"
        v-model:filter="filter"
      />

      <TodoList
        :todos="filteredTodos"
        :loading="loading"
        :search="search"
        :filter="filter"
        @create="emit('create')"
        @toggle="toggleTodo"
        @edit="emit('edit', $event)"
        @remove="removeTodo"
      />
    </section>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";

import {
  getTodos,
  updateTodo,
  deleteTodo,
} from "@/services/todoService";

import { isCompleted } from "@/utils/todoUtils";

import TodoStats from "@/components/TodoStats.vue";
import TodoToolbar from "@/components/TodoToolbar.vue";
import TodoList from "@/components/TodoList.vue";

const emit = defineEmits(["create", "edit"]);

const todos = ref([]);
const loading = ref(false);
const error = ref("");

const search = ref("");
const filter = ref("all");

const completedCount = computed(
  () => todos.value.filter(isCompleted).length
);

const activeCount = computed(
  () => todos.value.length - completedCount.value
);

const filteredTodos = computed(() => {
  let result = todos.value;

  if (filter.value === "active") {
    result = result.filter((todo) => !isCompleted(todo));
  } else if (filter.value === "completed") {
    result = result.filter((todo) => isCompleted(todo));
  }

  const keyword = search.value.trim().toLowerCase();

  if (keyword) {
    result = result.filter((todo) =>
      [todo.title, todo.description].some((value) =>
        String(value || "").toLowerCase().includes(keyword)
      )
    );
  }

  return result;
});

function extractTodos(response) {
  const result = response.data?.todos;

  if (Array.isArray(result)) return result;
  if (Array.isArray(result?.data)) return result.data;
  if (Array.isArray(result?.items)) return result.items;

  return [];
}

function getErrorMessage(response, fallback) {
  return response.data?.message || fallback;
}

function clearError() {
  error.value = "";
}

async function fetchTodos() {
  loading.value = true;
  error.value = "";

  try {
    const response = await getTodos();

    if (!response.ok) {
      throw new Error(
        getErrorMessage(response, "Failed to load todos.")
      );
    }

    todos.value = extractTodos(response);
  } catch (err) {
    error.value = err.message || "Something went wrong.";
  } finally {
    loading.value = false;
  }
}

async function toggleTodo(todo) {
  error.value = "";

  try {
    const response = await updateTodo(todo.id, {
      title: todo.title,
      description: todo.description || "",
      is_completed: !isCompleted(todo),
    });

    if (!response.ok) {
      throw new Error(
        getErrorMessage(response, "Failed to update todo.")
      );
    }

    await fetchTodos();
  } catch (err) {
    error.value = err.message || "Something went wrong.";
  }
}

async function removeTodo(todo) {
  if (!window.confirm(`Delete "${todo.title}"?`)) {
    return;
  }

  error.value = "";

  try {
    const response = await deleteTodo(todo.id);

    if (!response.ok) {
      throw new Error(
        getErrorMessage(response, "Failed to delete todo.")
      );
    }

    await fetchTodos();
  } catch (err) {
    error.value = err.message || "Something went wrong.";
  }
}

onMounted(fetchTodos);

defineExpose({
  fetchTodos,
});
</script>