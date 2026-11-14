import { apiGet } from "@/services/apiClient";

export function getTodos() {
  return apiGet("/api/todos");
}

export function getTodoById(id) {
  return apiGet(`/api/todos/${encodeURIComponent(id)}`);
}