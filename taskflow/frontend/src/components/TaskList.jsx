import { useMemo, useState } from 'react';

const STATUSES = ['todo', 'doing', 'done'];
const NEXT_STATUS = { todo: 'doing', doing: 'done', done: 'todo' };
const STATUS_LABEL = { todo: 'To do', doing: 'Doing', done: 'Done' };

export default function TaskList({ tasks, onStatusChange, onDelete }) {
  const [filter, setFilter] = useState('all');

  const visibleTasks = useMemo(() => {
    if (filter === 'all') return tasks;
    return tasks.filter((t) => t.status === filter);
  }, [tasks, filter]);

  const counts = useMemo(() => {
    const c = { all: tasks.length, todo: 0, doing: 0, done: 0 };
    for (const t of tasks) c[t.status] += 1;
    return c;
  }, [tasks]);

  if (tasks.length === 0) {
    return <p className="muted">No tasks yet — add your first one above.</p>;
  }

  return (
    <section>
      <div className="filters">
        {['all', ...STATUSES].map((s) => (
          <button
            key={s}
            type="button"
            className={filter === s ? 'filter-btn active' : 'filter-btn'}
            onClick={() => setFilter(s)}
          >
            {s === 'all' ? 'All' : STATUS_LABEL[s]} ({counts[s]})
          </button>
        ))}
      </div>

      <ul className="task-list">
        {visibleTasks.map((task) => (
          <li key={task.id} className={`card task task-${task.status}`}>
            <div className="task-main">
              <h3>{task.title}</h3>
              {task.description && (
                <p className="task-desc">{task.description}</p>
              )}
              <span className={`badge badge-${task.status}`}>
                {STATUS_LABEL[task.status]}
              </span>
            </div>
            <div className="task-actions">
              <button
                type="button"
                className="btn-secondary"
                onClick={() => onStatusChange(task.id, NEXT_STATUS[task.status])}
                title="Move to next status"
              >
                → {STATUS_LABEL[NEXT_STATUS[task.status]]}
              </button>
              <button
                type="button"
                className="btn-danger"
                onClick={() => {
                  if (window.confirm('Delete this task?')) onDelete(task.id);
                }}
              >
                Delete
              </button>
            </div>
          </li>
        ))}
      </ul>

      {visibleTasks.length === 0 && (
        <p className="muted">No tasks with this status.</p>
      )}
    </section>
  );
}
