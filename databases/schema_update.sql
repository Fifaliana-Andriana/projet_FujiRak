CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) DEFAULT NULL UNIQUE,
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

CREATE TABLE IF NOT EXISTS login_requests (
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

-- Table for registration requests submitted by prospective users (email only)
CREATE TABLE IF NOT EXISTS registration_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    is_processed TINYINT(1) NOT NULL DEFAULT 0,
    processed_by INT DEFAULT NULL,
    processed_at DATETIME DEFAULT NULL,
    user_id INT DEFAULT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_processed (is_processed),
    INDEX idx_email (email)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS finances (
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

CREATE TABLE IF NOT EXISTS gains (
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

CREATE TABLE IF NOT EXISTS pertes (
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

CREATE TABLE IF NOT EXISTS soldes (
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

CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    description TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    date_action DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_admin_date (admin_id, date_action)
) ENGINE=InnoDB;

