DROP DATABASE IF EXISTS fujirak_dashboard;
CREATE DATABASE fujirak_dashboard
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE fujirak_dashboard;

-- ==========================================
-- TABLE USERS
-- ==========================================

CREATE TABLE users (

    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(100) NOT NULL UNIQUE,

    nom VARCHAR(100) NOT NULL,

    prenom VARCHAR(100) NOT NULL,

    email VARCHAR(255) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    photo VARCHAR(255) DEFAULT 'default.png',

    classe ENUM('simple','gold','plus')
        NOT NULL DEFAULT 'simple',

    role ENUM('admin','user')
        NOT NULL DEFAULT 'user',

    is_active BOOLEAN DEFAULT TRUE,

    last_login DATETIME NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_email(email),
    INDEX idx_username(username),
    INDEX idx_classe(classe),
    INDEX idx_role(role)

) ENGINE=InnoDB;


-- ==========================================
-- TABLE GAINS
-- ==========================================

CREATE TABLE gains (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    montant DECIMAL(15,2) NOT NULL,

    description TEXT,

    source VARCHAR(255),

    date_gain DATE NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_gain_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_gain_user(user_id),
    INDEX idx_gain_date(date_gain)

) ENGINE=InnoDB;


-- ==========================================
-- TABLE PERTES
-- ==========================================

CREATE TABLE pertes (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    montant DECIMAL(15,2) NOT NULL,

    description TEXT,

    categorie VARCHAR(255),

    date_perte DATE NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_perte_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_perte_user(user_id),
    INDEX idx_perte_date(date_perte)

) ENGINE=InnoDB;


-- ==========================================
-- TABLE ADMIN LOGS
-- ==========================================

CREATE TABLE admin_logs (

    id INT AUTO_INCREMENT PRIMARY KEY,

    admin_id INT NOT NULL,

    action VARCHAR(100) NOT NULL,

    description TEXT,

    ip_address VARCHAR(45),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_admin_logs
        FOREIGN KEY(admin_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_admin(admin_id),
    INDEX idx_date(created_at)

) ENGINE=InnoDB;


-- ==========================================
-- TABLE NOTIFICATIONS
-- ==========================================

CREATE TABLE notifications (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    titre VARCHAR(255) NOT NULL,

    message TEXT NOT NULL,

    est_lu BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notification_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_notification_user(user_id),
    INDEX idx_notification_lu(est_lu)

) ENGINE=InnoDB;


INSERT INTO users
(
    username,
    nom,
    prenom,
    email,
    password,
    classe,
    role
)
VALUES
(
    'admin',
    'Super',
    'Admin',
    'finixiiasadmin@gmail.com',
    '$2y$12$BqLgue/MdWfHrMKO/a.zwOMtMb9PQ4AwKZKwZsdajnbJ2PMQbpO9u',
    'plus',
    'admin'
);