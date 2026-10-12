# TaskFlow — Frontend (React + Vite)

The React frontend for the TaskFlow task manager. Talks to the PHP API at `http://localhost:8000/api`.

## Setup

1. **Install dependencies**

   ```bash
   npm install
   ```

2. **Start the dev server**

   ```bash
   npm run dev
   ```

   Open the URL Vite prints (default `http://localhost:5173`).

> The PHP backend must be running on port 8000 first — see `../backend/README.md`.

## Features

- View all tasks
- Add a task (title + optional description)
- Move a task through statuses: To do → Doing → Done
- Delete tasks (with confirmation)
- Filter the list by status, with per-status counters
