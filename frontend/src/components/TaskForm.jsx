import { useState } from 'react';

export default function TaskForm({ onAdd }) {
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!title.trim()) {
      setError('Please enter a title.');
      return;
    }
    setSubmitting(true);
    setError('');
    try {
      await onAdd({ title: title.trim(), description: description.trim() });
      setTitle('');
      setDescription('');
    } catch (err) {
      setError(err.message || 'Could not create the task.');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <form className="card task-form" onSubmit={handleSubmit}>
      <h2>Add a task</h2>
      <input
        type="text"
        placeholder="Task title"
        value={title}
        onChange={(e) => setTitle(e.target.value)}
        maxLength={255}
      />
      <textarea
        placeholder="Description (optional)"
        value={description}
        onChange={(e) => setDescription(e.target.value)}
        rows={2}
      />
      {error && <div className="alert alert-error">{error}</div>}
      <button type="submit" disabled={submitting}>
        {submitting ? 'Adding…' : 'Add task'}
      </button>
    </form>
  );
}
