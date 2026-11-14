<template>
  <main class="dashboard-view">
    <header class="page-header">
      <div>
        <h1>My Tasks</h1>
        <p>Organize your day and get things done.</p>
      </div>

      <button class="primary-button" @click="openCreateModal">
        <span>+</span>
        Add Task
      </button>
    </header>

    <TodoTable
      ref="todoTable"
      @create="openCreateModal"
      @edit="openEditModal"
    />

    <TodoModal
      :show="showModal"
      :todo="editingTodo"
      @close="closeModal"
      @saved="handleSaved"
    />
  </main>
</template>

<style src="@/styles/app.css"></style>

<script setup>
import { ref } from "vue";

import TodoTable from "@/components/TodoTable.vue";
import TodoModal from "@/components/TodoModal.vue";

const todoTable = ref(null);

const showModal = ref(false);
const editingTodo = ref(null);

function openCreateModal() {
  editingTodo.value = null;
  showModal.value = true;
}

function openEditModal(todo) {
  editingTodo.value = todo;
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
  editingTodo.value = null;
}

async function handleSaved() {
  closeModal();
  await todoTable.value?.fetchTodos();
}
</script>