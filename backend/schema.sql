-- ============================================================
-- TaskFlow — Database schema + sample data
-- Import with:  mysql -u root < schema.sql
-- (or use phpMyAdmin on XAMPP)
-- ============================================================

CREATE DATABASE IF NOT EXISTS taskflow
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE taskflow;

CREATE TABLE IF NOT EXISTS tasks (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    status      ENUM('todo', 'doing', 'done') NOT NULL DEFAULT 'todo',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Sample data
INSERT INTO tasks (title, description, status) VALUES
('Set up the project', 'Install XAMPP, import schema.sql and start the PHP dev server.', 'done'),
('Build the React frontend', 'Task list, add-task form, status filter and delete button.', 'doing'),
('Write the README', 'Document the setup steps for backend and frontend.', 'todo');
