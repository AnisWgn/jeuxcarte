-- Ajouter la colonne coins si elle n'existe pas
ALTER TABLE users ADD COLUMN IF NOT EXISTS coins INT DEFAULT 1000;

-- Mettre à jour tous les utilisateurs existants avec un solde initial
UPDATE users SET coins = 1000 WHERE coins IS NULL; 