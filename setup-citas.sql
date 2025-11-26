-- Create citas table for appointment management
CREATE TABLE IF NOT EXISTS citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    use_insurance BOOLEAN DEFAULT FALSE,
    is_underage BOOLEAN DEFAULT FALSE,
    birth_certificate VARCHAR(500),
    cedula_titular VARCHAR(500),
    cedula_beneficiario VARCHAR(500),
    referencia_medica TEXT,
    indicaciones_medicas TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create uploads directory structure
-- Note: You'll need to create the directory 'admin/uploads/citas/' on your server
-- and set proper permissions (755 or 777)
