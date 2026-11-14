<template>
  <div
    v-if="show"
    class="modal-overlay"
    @click.self="closeModal"
  >
    <section
      class="todo-modal"
      role="dialog"
      aria-modal="true"
      aria-labelledby="modal-title"
    >
      <div class="modal-header">
        <h2 id="modal-title">
          {{ isEditing ? "Edit Task" : "Create Task" }}
        </h2>

        <button
          class="close-button"
          :disabled="saving"
          @click="closeModal"
        >
          ×
        </button>
      </div>

      <form @submit.prevent="saveTodo">
        <div v-if="error" class="error-message">
          {{ error }}
        </div>

        <div class="form-group">
          <label for="todo-title">Title</label>
          <input
            id="todo-title"
            v-model="form.title"
            type="text"
            maxlength="255"
            placeholder="What needs to be done?"
            required
            autofocus
          />
        </div>

        <div class="form-group">
          <label for="todo-description">Description</label>
          <textarea
            id="todo-description"
            v-model="form.description"
            rows="4"
            placeholder="Add some details (optional)"
          ></textarea>
        </div>

        <div class="modal-actions">
          <button
            type="button"
            class="secondary-button"
            :disabled="saving"
            @click="closeModal"
          >
            Cancel
          </button>

          <button
            type="submit"
            class="primary-button"
            :disabled="saving"
          >
            {{ saving ? "Saving..." : "Save Task" }}
          </button>
        </div>
      </form>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";

import {
  createTodo,
  updateTodo,
} from "@/services/todoService";

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  todo: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(["close", "saved"]);

const saving = ref(false);
const error = ref("");

const form = ref({
  title: "",
  description: "",
});

const isEditing = computed(() => props.todo !== null);

watch(
  () => [props.show, props.todo],
  ([show, todo]) => {
    if (!show) return;

    error.value = "";

    form.value = {
      title: todo?.title || "",
      description: todo?.description || "",
    };
  },
  { immediate: true }
);

function getErrorMessage(response, fallback) {
  return response.data?.message || fallback;
}

function closeModal() {
  if (saving.value) return;

  emit("close");
}

async function saveTodo() {
  const title = form.value.title.trim();

  if (!title) {
    error.value = "Please enter a title.";
    return;
  }

  saving.value = true;
  error.value = "";

  const payload = {
    title,
    description: form.value.description.trim(),
  };

  try {
    const response = isEditing.value
      ? await updateTodo(props.todo.id, payload)
      : await createTodo(payload);

    if (!response.ok) {
      throw new Error(
        getErrorMessage(response, "Failed to save todo.")
      );
    }

    emit("saved");
  } catch (err) {
    error.value = err.message || "Something went wrong.";
  } finally {
    saving.value = false;
  }
}
</script>