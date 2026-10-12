# TaskFlow

A simple full-stack task manager: create tasks, move them through statuses (To do → Doing → Done), filter the list and delete tasks. Data is stored in MySQL and served by a small PHP REST API, with a React frontend.

## Features

- List all tasks (newest first)
- Add a task with title and optional description
- Change task status: `todo` → `doing` → `done`
- Filter tasks by status with live counters
- Delete tasks with confirmation
- REST API with proper HTTP status codes and JSON error messages

## Tech stack

- **Backend:** PHP 8 (no framework), PDO, MySQL
- **Frontend:** React 18, Vite 5

## Project structure

```
taskflow/
├── backend/
│   ├── config.php      # DB config + PDO connection + CORS headers
│   ├── index.php       # REST router (GET/POST/PUT/DELETE /api/tasks)
│   ├── schema.sql      # Database + table + sample data
│   └── README.md
├── frontend/
│   ├── index.html
│   ├── package.json
│   ├── vite.config.js
│   └── src/
│       ├── main.jsx
│       ├── App.jsx
│       ├── api.js            # fetch wrapper for the PHP API
│       ├── components/
│       │   ├── TaskForm.jsx  # add-task form
│       │   └── TaskList.jsx  # list + filter + status toggle + delete
│       └── styles.css
└── README.md
```

## Setup

**1. Backend** — see [backend/README.md](backend/README.md):

```bash
cd backend
mysql -u root < schema.sql   # create DB + sample data
php -S localhost:8000        # start the API
```

**2. Frontend** — see [frontend/README.md](frontend/README.md):

```bash
cd frontend
npm install
npm run dev                  # open http://localhost:5173
```

## Author

Ossama Hamrani
