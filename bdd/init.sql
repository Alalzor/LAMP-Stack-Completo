USE empresa;

-- Drop table if exists to recreate with new structure
DROP TABLE IF EXISTS users;

-- Create enhanced users table with professional fields
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    full_name VARCHAR(200) GENERATED ALWAYS AS (CONCAT(first_name, ' ', last_name)) STORED,
    job_title VARCHAR(150) NOT NULL,
    department VARCHAR(100) NOT NULL,
    employee_id VARCHAR(20) UNIQUE NOT NULL,
    hire_date DATE NOT NULL,
    contract_end_date DATE NULL,
    salary DECIMAL(10,2) NOT NULL,
    phone VARCHAR(20),
    office_location VARCHAR(100),
    manager_id INT NULL,
    status ENUM('active', 'inactive', 'terminated') DEFAULT 'active',
    profile_image VARCHAR(255) DEFAULT 'default-avatar.png',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert realistic test users with professional data
INSERT INTO users (email, password, first_name, last_name, job_title, department, employee_id, hire_date, contract_end_date, salary, phone, office_location, manager_id, status) VALUES 

-- CEO and Top Management
('admin@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Alexander', 'Rodriguez', 'Chief Executive Officer', 'Executive', 'EMP001', '2020-01-15', NULL, 150000.00, '+34-611-234-567', 'Madrid HQ - Floor 10', NULL, 'active'),

('cto@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Maria', 'Garcia', 'Chief Technology Officer', 'Technology', 'EMP002', '2020-03-01', NULL, 120000.00, '+34-622-345-678', 'Madrid HQ - Floor 8', 1, 'active'),

-- Development Team
('dev1@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Carlos', 'Martinez', 'Senior Full Stack Developer', 'Technology', 'EMP003', '2021-06-15', NULL, 65000.00, '+34-633-456-789', 'Madrid HQ - Floor 5', 2, 'active'),

('dev2@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Ana', 'Lopez', 'Frontend Developer', 'Technology', 'EMP004', '2022-01-10', NULL, 55000.00, '+34-644-567-890', 'Madrid HQ - Floor 5', 2, 'active'),

('dev3@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'David', 'Fernandez', 'Backend Developer', 'Technology', 'EMP005', '2022-09-01', '2025-08-31', 58000.00, '+34-655-678-901', 'Barcelona Office', 2, 'active'),

-- HR and Administration
('hr@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Laura', 'Sanchez', 'HR Director', 'Human Resources', 'EMP006', '2020-08-01', NULL, 70000.00, '+34-666-789-012', 'Madrid HQ - Floor 3', 1, 'active'),

('admin1@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Pedro', 'Ruiz', 'Administrative Assistant', 'Administration', 'EMP007', '2023-02-15', NULL, 35000.00, '+34-677-890-123', 'Madrid HQ - Floor 2', 6, 'active'),

-- Sales and Marketing
('sales@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Sofia', 'Torres', 'Sales Manager', 'Sales', 'EMP008', '2021-04-01', NULL, 60000.00, '+34-688-901-234', 'Madrid HQ - Floor 4', 1, 'active'),

('marketing@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Miguel', 'Jimenez', 'Marketing Specialist', 'Marketing', 'EMP009', '2022-11-01', NULL, 45000.00, '+34-699-012-345', 'Valencia Office', 8, 'active'),

-- Finance
('finance@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Elena', 'Moreno', 'Finance Director', 'Finance', 'EMP010', '2020-05-15', NULL, 85000.00, '+34-610-123-456', 'Madrid HQ - Floor 6', 1, 'active'),

-- Interns and Junior Staff
('intern1@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Lucia', 'Vega', 'Junior Developer', 'Technology', 'EMP011', '2024-06-01', '2024-12-31', 28000.00, '+34-621-234-567', 'Madrid HQ - Floor 5', 3, 'active'),

-- Former Employee (terminated)
('former@projectdelta.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Roberto', 'Herrera', 'Former Project Manager', 'Technology', 'EMP012', '2021-01-15', '2024-08-31', 55000.00, '+34-632-345-678', 'Madrid HQ - Floor 5', 2, 'terminated');

-- Create departments table for better organization
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    budget DECIMAL(12,2),
    head_of_department_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (head_of_department_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert department data
INSERT INTO departments (name, description, budget, head_of_department_id) VALUES
('Executive', 'Executive management and strategic planning', 500000.00, 1),
('Technology', 'Software development and IT infrastructure', 800000.00, 2),
('Human Resources', 'Employee management and organizational development', 200000.00, 6),
('Sales', 'Sales operations and customer acquisition', 300000.00, 8),
('Marketing', 'Brand management and marketing campaigns', 250000.00, 9),
('Finance', 'Financial planning and accounting', 150000.00, 10),
('Administration', 'General administration and support services', 100000.00, 6);

-- Create projects table for dashboard metrics
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    status ENUM('planning', 'active', 'completed', 'on_hold', 'cancelled') DEFAULT 'planning',
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    start_date DATE,
    end_date DATE,
    budget DECIMAL(10,2),
    spent_budget DECIMAL(10,2) DEFAULT 0.00,
    project_manager_id INT,
    department_id INT,
    progress_percentage INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (project_manager_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Insert sample projects
INSERT INTO projects (name, description, status, priority, start_date, end_date, budget, spent_budget, project_manager_id, department_id, progress_percentage) VALUES
('Project Delta Platform', 'Main web platform development', 'active', 'high', '2024-01-15', '2024-12-31', 150000.00, 45000.00, 3, 2, 75),
('Mobile App Development', 'Cross-platform mobile application', 'active', 'medium', '2024-03-01', '2024-10-15', 80000.00, 25000.00, 4, 2, 45),
('Security Audit 2024', 'Comprehensive security assessment', 'completed', 'critical', '2024-01-01', '2024-08-31', 25000.00, 23500.00, 2, 2, 100),
('Marketing Campaign Q4', 'End-of-year marketing initiatives', 'planning', 'medium', '2024-10-01', '2024-12-31', 35000.00, 0.00, 9, 5, 10),
('HR System Upgrade', 'Human resources management system update', 'on_hold', 'low', '2024-06-01', '2024-09-30', 15000.00, 5000.00, 6, 3, 25);

-- Show created data
SELECT 'Users:' as table_name;
SELECT id, full_name, job_title, department, employee_id, hire_date, status FROM users;

SELECT 'Departments:' as table_name;
SELECT * FROM departments;

SELECT 'Projects:' as table_name;
SELECT id, name, status, priority, progress_percentage, budget FROM projects;