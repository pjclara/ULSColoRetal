-- Sincroniza o esquema de `centro_referencia` com `centro_referencia_back`.
-- Só altera esquema, permissões e 1 linha de catálogo; NÃO mexe nos dados clínicos.
-- Idempotente: pode ser corrido mais de uma vez.
-- FAZER BACKUP ANTES (mysqldump centro_referencia > backup.sql). O DDL do MySQL não é revertível.

USE `centro_referencia`;

-- 1. Tabelas novas --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profiles_user_id_unique` (`user_id`),
  CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `centro_de_referencias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Coluna nova: resolucao_complicacaos.clavien_dindo_id ------------------------------------

SET @existe = (SELECT COUNT(*) FROM information_schema.columns
               WHERE table_schema = DATABASE() AND table_name = 'resolucao_complicacaos' AND column_name = 'clavien_dindo_id');
SET @sql = IF(@existe = 0,
  'ALTER TABLE `resolucao_complicacaos`
     ADD COLUMN `clavien_dindo_id` bigint unsigned DEFAULT NULL,
     ADD KEY `resolucao_complicacaos_clavien_dindo_id_foreign` (`clavien_dindo_id`),
     ADD CONSTRAINT `resolucao_complicacaos_clavien_dindo_id_foreign` FOREIGN KEY (`clavien_dindo_id`) REFERENCES `clavien_dindos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE',
  'DO 0');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3. Colunas alteradas -----------------------------------------------------------------------

-- Agendamento sem período passa a ser permitido.
ALTER TABLE `agendamentos` MODIFY `periodo_de_agendamento_id` bigint unsigned DEFAULT NULL;

-- enum('1','2','3','4') -> int. O MySQL converte o enum pelo índice, que aqui coincide com o valor
-- ('1'..'4' pela mesma ordem), por isso os dados mantêm-se.
ALTER TABLE `agendamentos`      MODIFY `estado_de_agendamento` int NOT NULL DEFAULT 2;
ALTER TABLE `lista_de_esperas`  MODIFY `estado_lista_espera`   int NOT NULL DEFAULT 1;

-- 3b. Permissões -----------------------------------------------------------------------------

INSERT INTO `permissions` (`name`, `guard_name`, `created_at`, `updated_at`)
SELECT p.name, 'web', NOW(), NOW()
FROM (SELECT 'agendamento.view' AS name UNION ALL SELECT 'agendamento.create' UNION ALL SELECT 'agendamento.update'
      UNION ALL SELECT 'agendamento.delete' UNION ALL SELECT 'resolucao-complicacao.view'
      UNION ALL SELECT 'resolucao-complicacao.create' UNION ALL SELECT 'resolucao-complicacao.update'
      UNION ALL SELECT 'resolucao-complicacao.delete' UNION ALL SELECT 'estatisticas.view') p
WHERE NOT EXISTS (SELECT 1 FROM `permissions` x WHERE x.name = p.name AND x.guard_name = 'web');

-- Só o role `admin` recebe as permissões novas (como em centro_referencia_back).
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id
FROM `permissions` p
JOIN `roles` r ON r.name = 'admin' AND r.guard_name = 'web'
WHERE p.name IN ('agendamento.view','agendamento.create','agendamento.update','agendamento.delete',
                 'resolucao-complicacao.view','resolucao-complicacao.create','resolucao-complicacao.update',
                 'resolucao-complicacao.delete','estatisticas.view')
  AND NOT EXISTS (SELECT 1 FROM `role_has_permissions` rp WHERE rp.permission_id = p.id AND rp.role_id = r.id);

-- 4. Catálogo: intervenção em falta -----------------------------------------------------------

INSERT INTO `intervencaos` (`nome`, `grupo_intervencao_id`, `abrev`, `centro_de_referencia`, `cirurgia_de_ressecao`)
SELECT 'Colpo-procto-sacropexia laparoscópica', 1, 'Colpo-procto-sacropexia Lap', 0, 0
WHERE NOT EXISTS (SELECT 1 FROM `intervencaos` WHERE nome = 'Colpo-procto-sacropexia laparoscópica');

-- Depois de correr: php artisan permission:cache-reset
