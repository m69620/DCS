-- Création de la base (optionnel)
CREATE DATABASE IF NOT EXISTS campus_it;
USE campus_it;

-- =========================
-- Table : application
-- =========================
CREATE TABLE application (
    app_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(80) NOT NULL
);

-- =========================
-- Table : ressource
-- =========================
CREATE TABLE ressource (
    res_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(30) NOT NULL,
    unite VARCHAR(10) NOT NULL
);

-- =========================
-- Table : consommation
-- =========================
CREATE TABLE consommation (
    conso_id INT AUTO_INCREMENT PRIMARY KEY,
    app_id INT NOT NULL,
    res_id INT NOT NULL,
    mois DATE NOT NULL,
    volume DECIMAL(10,2) NOT NULL,

    -- Clés étrangères
    CONSTRAINT fk_conso_app
        FOREIGN KEY (app_id) 
        REFERENCES application(app_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_conso_res
        FOREIGN KEY (res_id) 
        REFERENCES ressource(res_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
-- =========================
-- Index
-- =========================
CREATE INDEX idx_conso_mois ON consommation(mois);
CREATE INDEX idx_conso_app_id ON consommation(app_id);
CREATE INDEX idx_conso_volume ON consommation(volume);
