-- Добавляет недостающие поля в таблицу
ALTER TABLE `dle_users`
ADD COLUMN `mc_uuid` CHAR(36) UNIQUE DEFAULT NULL,
ADD COLUMN `mc_access_token` CHAR(32) DEFAULT NULL,
ADD COLUMN `mc_client_token` CHAR(32) DEFAULT NULL,
ADD COLUMN `mc_server_id` VARCHAR(41) DEFAULT NULL,
ADD COLUMN `mc_skin_hash` CHAR(36) DEFAULT NULL,
ADD COLUMN `mc_cloak_hash` CHAR(36) DEFAULT NULL,
ADD COLUMN `mc_skin_type` INT(1) NOT NULL DEFAULT '0';

-- Создаёт триггер на генерацию UUID для новых пользователей
DELIMITER //
CREATE TRIGGER setUUID BEFORE INSERT ON `dle_users`
FOR EACH ROW BEGIN
IF NEW.mc_uuid IS NULL THEN
SET NEW.mc_uuid = REPLACE(UUID(), '-', '');
END IF;
END; //
DELIMITER ;

-- Генерирует UUID для уже существующих пользователей
UPDATE `dle_users` SET `mc_uuid`=(SELECT UUID()) WHERE `mc_uuid` IS NULL;