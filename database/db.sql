CREATE DATABASE devtask;
USE devtask;
CREATE TABLE projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    description TEXT,
    deadline DATE,
    est_archive TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
CREATE TABLE project_user (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    projet_id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    role VARCHAR(50) DEFAULT 'developer',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (projet_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE tasks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    description TEXT,
    statut ENUM('todo','in_progress','done') DEFAULT 'todo',
    projet_id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (projet_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
