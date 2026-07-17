DROP TABLE IF EXISTS admin_logs;
DROP TABLE IF EXISTS soldes;
DROP TABLE IF EXISTS pertes;
DROP TABLE IF EXISTS gains;
DROP TABLE IF EXISTS finances;
DROP TABLE IF EXISTS login_requests;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) DEFAULT NULL,
    classe ENUM('simple', 'gold', 'plus') NOT NULL DEFAULT 'simple',
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    verification_code VARCHAR(6) DEFAULT NULL,
    code_expiration DATETIME DEFAULT NULL,
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login DATETIME DEFAULT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_classe (classe),
    INDEX idx_role (role)
) ENGINE=InnoDB;

CREATE TABLE login_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(6) NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    is_used TINYINT(1) NOT NULL DEFAULT 0,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_expiration DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_code (user_id, code),
    INDEX idx_expiration (date_expiration)
) ENGINE=InnoDB;

CREATE TABLE finances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('gain', 'perte', 'solde') NOT NULL,
    montant DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    description TEXT DEFAULT NULL,
    date_transaction DATE NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_type (user_id, type),
    INDEX idx_date (date_transaction),
    INDEX idx_user_date (user_id, date_transaction)
) ENGINE=InnoDB;

CREATE TABLE gains (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    montant DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    description TEXT DEFAULT NULL,
    source VARCHAR(255) DEFAULT NULL,
    date_gain DATE NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_date (user_id, date_gain)
) ENGINE=InnoDB;

CREATE TABLE pertes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    montant DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    description TEXT DEFAULT NULL,
    categorie VARCHAR(255) DEFAULT NULL,
    date_perte DATE NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_date (user_id, date_perte)
) ENGINE=InnoDB;

CREATE TABLE soldes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    solde_initial DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    solde_actuel DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    date_mise_a_jour DATE NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uk_user_solde (user_id),
    INDEX idx_user_date (user_id, date_mise_a_jour)
) ENGINE=InnoDB;

CREATE TABLE admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    description TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    date_action DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_admin_date (admin_id, date_action)
) ENGINE=InnoDB;

INSERT INTO users (nom, prenom, email, password, classe, role, is_verified, is_active) VALUES 
('Admin', 'System', 'admin@fujirak.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'plus', 'admin', 1, 1);

INSERT INTO users (nom, prenom, email, password, classe, role, is_verified, is_active) VALUES 
('Dupont', 'Jean', 'jean.dupont@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'simple', 'user', 1, 1), 
('Martin', 'Sophie', 'sophie.martin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gold', 'user', 1, 1),
('Bernard', 'Pierre', 'pierre.bernard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'plus', 'user', 1, 1);

INSERT INTO soldes (user_id, solde_initial, solde_actuel, date_mise_a_jour) VALUES
(2, 5000.00, 7500.00, '2024-01-15'),
(3, 10000.00, 15000.00, '2024-01-15'),
(4, 20000.00, 25000.00, '2024-01-15');

INSERT INTO gains (user_id, montant, description, source, date_gain) VALUES
(2, 1500.00, 'Commission janvier', 'Commission', '2024-01-15'),
(2, 2000.00, 'Bonus performance', 'Bonus', '2024-02-01'),
(3, 3000.00, 'Commission janvier', 'Commission', '2024-01-20'),
(4, 5000.00, 'Bonus trimestriel', 'Bonus', '2024-01-25');

INSERT INTO pertes (user_id, montant, description, categorie, date_perte) VALUES
(2, 500.00, 'Frais administratifs', 'Frais', '2024-01-18'),
(3, 800.00, 'Ajustement marché', 'Marché', '2024-01-22'),
(4, 1200.00, 'Frais transaction', 'Frais', '2024-01-28');