import { useCallback, useEffect, useState } from 'react';
import { api } from './api.js';
import TaskForm from './components/TaskForm.jsx';
import TaskList from './components/TaskList.jsx';

export default function App() {
  const [tasks, setTasks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const loadTasks = useCallback(async () => {
    try {
      setError('');
      const data = await api.listTasks();
      setTasks(data);
    } catch (err) {
      setError('Could not load tasks. Is the PHP API running on port 8000?');
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    loadTasks();
  }, [loadTasks]);

  const handleAdd = async (task) => {
    const created = await api.createTask(task);
    setTasks((prev) => [created, ...prev]);
  };

  const handleStatusChange = async (id, status) => {
    const updated = await api.updateTask(id, { status });
    setTasks((prev) => prev.map((t) => (t.id === id ? updated : t)));
  };

  const handleDelete = async (id) => {
    await api.deleteTask(id);
    setTasks((prev) => prev.filter((t) => t.id !== id));
  };

  return (
    <div className="app">
      <header className="header">
        <h1>TaskFlow</h1>
        <p>A simple full-stack task manager</p>
      </header>

      <main className="container">
        <TaskForm onAdd={handleAdd} />

        {error && <div className="alert alert-error">{error}</div>}
        {loading ? (
          <p className="muted">Loading tasks…</p>
        ) : (
          <TaskList
            tasks={tasks}
            onStatusChange={handleStatusChange}
            onDelete={handleDelete}
          />
        )}
      </main>

      <footer className="footer">
        <span>PHP 8 + MySQL · React + Vite</span>
      </footer>
    </div>
  );
}
