-- Insérer des utilisateurs dans la table "user"
INSERT INTO "user" ("id", "nickname", "email") VALUES
('123e4567-e89b-12d3-a456-426614174000', 'user1', 'user1@example.com'),
('123e4567-e89b-12d3-a456-426614174001', 'user2', 'user2@example.com');

-- Insérer des jeux dans la table "game"
INSERT INTO "game" ("id", "userId", "photoIds", "serieId", "score", "state", "currentPhotoIndex", "startTime") VALUES
('game1', '123e4567-e89b-12d3-a456-426614174000', '[1, 2, 3]', '1', 100, 'completed', 3, '2023-01-01 10:00:00'),
('game2', '123e4567-e89b-12d3-a456-426614174001', '[4, 5, 6]', '1', 150, 'completed', 3, '2023-01-02 11:00:00');