export function isCompleted(todo) {
  return todo.is_completed === true ||
    todo.is_completed === 1 ||
    todo.is_completed === "1";
}