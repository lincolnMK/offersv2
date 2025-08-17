-- Create database (optional)
-- CREATE DATABASE acl_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE acl_demo;

-- Users
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  status ENUM('active','disabled') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Roles
CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255)
);

-- User ↔ Role (many-to-many)
CREATE TABLE IF NOT EXISTS user_roles (
  user_id INT NOT NULL,
  role_id INT NOT NULL,
  PRIMARY KEY (user_id, role_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Modules
CREATE TABLE IF NOT EXISTS modules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  slug VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255)
);

-- Permissions
CREATE TABLE IF NOT EXISTS permissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  module_id INT NOT NULL,
  action ENUM('view','add','edit','update','delete','export') NOT NULL,
  UNIQUE KEY (module_id, action),
  FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);

-- Role ↔ Permission
CREATE TABLE IF NOT EXISTS role_permissions (
  role_id INT NOT NULL,
  permission_id INT NOT NULL,
  PRIMARY KEY (role_id, permission_id),
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

-- User overrides
CREATE TABLE IF NOT EXISTS user_permissions (
  user_id INT NOT NULL,
  permission_id INT NOT NULL,
  allow TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (user_id, permission_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

-- Login throttling
CREATE TABLE IF NOT EXISTS login_attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL,
  ip VARCHAR(64) NOT NULL,
  attempts INT NOT NULL DEFAULT 0,
  last_attempt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY (email, ip)
);

-- Audit (optional)
CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  module_slug VARCHAR(100),
  action VARCHAR(50),
  entity_id VARCHAR(100),
  details TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (user_id, module_slug, action),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Seed
INSERT INTO roles (name, description) VALUES ('Admin','System administrator with full access')
ON DUPLICATE KEY UPDATE description=VALUES(description);

INSERT INTO users (name,email,password_hash) VALUES (
  'Admin','admin@example.com',
  -- password: admin123
  '$2y$10$4mLQK/4ZrZ9s8hC8vC6Ece0o6p9uGk9iKk2Tz6m7WzXfQ8m3y6bR6'
) ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO user_roles (user_id, role_id) VALUES (1,1)
ON DUPLICATE KEY UPDATE role_id=VALUES(role_id);

INSERT INTO modules (name, slug, description) VALUES
 ('Users','users','Manage system users'),
 ('Roles','roles','Manage roles'),
 ('Permissions','permissions','Manage permissions')
ON DUPLICATE KEY UPDATE description=VALUES(description);

INSERT INTO permissions (module_id, action)
SELECT m.id, a.action
FROM modules m
JOIN (
  SELECT 'view' AS action UNION ALL
  SELECT 'add' UNION ALL
  SELECT 'edit' UNION ALL
  SELECT 'update' UNION ALL
  SELECT 'delete'
) a
ON 1=1
ON DUPLICATE KEY UPDATE action=action;

-- Grant Admin everything
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT 1, p.id FROM permissions p;
