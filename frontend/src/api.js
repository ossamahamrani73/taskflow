// Tiny fetch wrapper for the TaskFlow PHP API.
const BASE_URL = 'http://localhost:8000/api';

async function request(path, options = {}) {
  const res = await fetch(`${BASE_URL}${path}`, {
    headers: { 'Content-Type': 'application/json' },
    ...options,
  });

  if (res.status === 204) return null; // DELETE returns no content

  const data = await res.json().catch(() => ({}));

  if (!res.ok) {
    throw new Error(data.error || `Request failed (${res.status})`);
  }
  return data;
}

export const api = {
  listTasks: () => request('/tasks'),
  getTask: (id) => request(`/tasks/${id}`),
  createTask: (task) =>
    request('/tasks', { method: 'POST', body: JSON.stringify(task) }),
  updateTask: (id, task) =>
    request(`/tasks/${id}`, { method: 'PUT', body: JSON.stringify(task) }),
  deleteTask: (id) => request(`/tasks/${id}`, { method: 'DELETE' }),
};
