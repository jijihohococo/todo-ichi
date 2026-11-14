import {
  apiGet,
  apiPost,
  apiPut,
  apiDelete,
} from "@/services/apiClient";

export function getTodos() {
  return apiGet("/api/todos");
}

export function getTodoById(id) {
  return apiGet(`/api/todos/${encodeURIComponent(id)}`);
}

export function createTodo(todo) {
  return apiPost("/api/todos", todo);
}

export function updateTodo(id, todo) {
  return apiPut(`/api/todos/${encodeURIComponent(id)}`, todo);
}

export function deleteTodo(id) {
  return apiDelete(`/api/todos/${encodeURIComponent(id)}`);
}