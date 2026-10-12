# TaskFlow — Backend (PHP 8, no framework)

A tiny JSON REST API for the TaskFlow task manager. Plain PHP with PDO + MySQL — no framework, no dependencies.

## Setup

1. **Create the database and import the schema**

   ```bash
   mysql -u root < schema.sql
   ```

   (On XAMPP you can also import `schema.sql` through phpMyAdmin.)

2. **Configure the database connection**

   Edit `config.php` and adjust `DB_HOST`, `DB_NAME`, `DB_USER` and `DB_PASS` if needed. Defaults target a local MySQL with user `root` and an empty password (XAMPP default).

3. **Run the dev server**

   From this `backend/` directory:

   ```bash
   php -S localhost:8000
   ```

   The API is then available at `http://localhost:8000/api/tasks`.

   > With XAMPP/Apache, place the `backend/` folder inside `htdocs` instead — the router works from any base path.

## Endpoints

| Method | URL              | Description            |
| ------ | ---------------- | ---------------------- |
| GET    | `/api/tasks`     | List all tasks         |
| GET    | `/api/tasks/1`   | Get a single task      |
| POST   | `/api/tasks`     | Create a task          |
| PUT    | `/api/tasks/1`   | Update a task          |
| DELETE | `/api/tasks/1`   | Delete a task          |

Example create request:

```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"title":"Buy groceries","description":"Milk and eggs","status":"todo"}'
```
