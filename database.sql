-- ================================================
-- Milano Studio - Base de données
-- Importer ce fichier dans phpMyAdmin
-- ================================================


-- Table des utilisateurs (admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des réservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_homme VARCHAR(100) NOT NULL,
    nom_femme VARCHAR(100) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    
    choix_package VARCHAR(50),
    statut ENUM('en attente', 'confirmée', 'annulée') DEFAULT 'en attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin par défaut: admin / milano2024
-- (le mot de passe est hashé avec password_hash)
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@milanostudio.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- mot de passe: password (à changer!)
