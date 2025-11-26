-- Database setup for Especialidades Medicas Turmero Admin Panel
-- Run this SQL in your MySQL/MariaDB database

CREATE DATABASE IF NOT EXISTS turmero_clinic;
USE turmero_clinic;

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data (optional)
INSERT INTO contact_messages (name, email, phone, message) VALUES
('Juan Pérez', 'juan@example.com', '0412-1234567', 'Quisiera información sobre cardiología'),
('María González', 'maria@example.com', '0414-7654321', 'Necesito agendar una cita de pediatría');
