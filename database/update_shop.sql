-- Ajouter la colonne coins à la table users si elle n'existe pas
ALTER TABLE users ADD COLUMN IF NOT EXISTS coins INT DEFAULT 1000;

-- Ajouter les colonnes pour la boutique à la table cards si elles n'existent pas
ALTER TABLE cards ADD COLUMN IF NOT EXISTS available_for_purchase BOOLEAN DEFAULT FALSE;
ALTER TABLE cards ADD COLUMN IF NOT EXISTS price INT DEFAULT 100;

-- Créer la table user_cards si elle n'existe pas
CREATE TABLE IF NOT EXISTS user_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    card_id INT NOT NULL,
    obtained_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (card_id) REFERENCES cards(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_card (user_id, card_id)
);

-- Mettre à jour quelques cartes pour qu'elles soient disponibles à l'achat
UPDATE cards SET available_for_purchase = TRUE, price = 100 WHERE id IN (1, 2, 3, 4, 5);
UPDATE cards SET available_for_purchase = TRUE, price = 200 WHERE id IN (6, 7, 8, 9, 10);
UPDATE cards SET available_for_purchase = TRUE, price = 300 WHERE id IN (11, 12, 13, 14, 15);

-- Ajouter un index pour améliorer les performances
CREATE INDEX IF NOT EXISTS idx_cards_available ON cards(available_for_purchase);
CREATE INDEX IF NOT EXISTS idx_user_cards_user ON user_cards(user_id);
CREATE INDEX IF NOT EXISTS idx_user_cards_card ON user_cards(card_id); 