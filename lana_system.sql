-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15-Set-2026 às 13:49
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `lana_system`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('lana-system-cache-mcbalves@gmail.com|127.0.0.1', 'i:1;', 1789327944),
('lana-system-cache-mcbalves@gmail.com|127.0.0.1:timer', 'i:1789327944;', 1789327944);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_13_183408_create_stages_table', 2),
(5, '2026_09_13_183420_create_whatsapp_messages_table', 2),
(6, '2026_09_13_183428_create_tasks_table', 2),
(7, '2026_09_13_183436_create_task_logs_table', 2),
(8, '2026_09_13_184302_create_personal_access_tokens_table', 3),
(9, '2026_09_14_190701_add_slug_to_stages_table', 4),
(10, '2026_09_14_192110_add_summary_and_reply_fields_to_tasks_table', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('61W2xaKMiMwsi1P7yy9Fg464ur3T2r98Nh7RrFyc', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiU3JvWkpnZlNTSFhxRldTbjBtekNWZlJoMWZyYlBIM0xoNVEwaGgzUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9rYW5iYW4iO3M6NToicm91dGUiO3M6MTI6ImthbmJhbi5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1789469580);

-- --------------------------------------------------------

--
-- Estrutura da tabela `stages`
--

CREATE TABLE `stages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#e5e7eb',
  `order` int(11) NOT NULL DEFAULT 0,
  `is_final` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `stages`
--

INSERT INTO `stages` (`id`, `name`, `slug`, `color`, `order`, `is_final`, `created_at`, `updated_at`) VALUES
(1, 'Pendentes', NULL, '#ef4444', 1, 0, '2026-09-13 21:39:36', '2026-09-14 22:46:30'),
(2, 'Em Andamento', NULL, '#84cc16', 3, 0, '2026-09-13 21:39:36', '2026-09-14 22:48:24'),
(6, 'A Fazer', 'a-fazer', '#3b82f6', 2, 0, '2026-09-14 22:42:51', '2026-09-14 22:46:30'),
(8, 'Concluído', 'concluido', '#10b981', 6, 1, '2026-09-14 22:42:51', '2026-09-14 22:46:30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `whatsapp_message_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stage_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `due_date` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `last_replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tasks`
--

INSERT INTO `tasks` (`id`, `whatsapp_message_id`, `user_id`, `stage_id`, `title`, `description`, `summary`, `priority`, `due_date`, `completed_at`, `last_replied_at`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 'Segunda via boleto - João Silva', 'Olá, preciso urgentemente que seja emitida a segunda via do boleto de manutenção do compressor de ar que venceu ontem.', NULL, 'high', NULL, NULL, NULL, '2026-09-13 22:56:41', '2026-09-14 00:30:38'),
(2, 4, 1, 1, 'Atendimento - Carlos Eduardo', 'Boa tarde! Gostaria de solicitar um orçamento urgente para a manutenção preventiva de dois compressores de ar na nossa fábrica.', NULL, 'medium', NULL, '2026-09-14 00:00:00', NULL, '2026-09-13 23:13:04', '2026-09-14 00:00:04'),
(3, NULL, NULL, 2, 'Teste de Integração Kanban', 'Verificando se o drag & drop, edição de resumo e alteração de cores funcionam corretamente.', 'Resumo gerado para a exibição do resumo inteligente.', 'high', NULL, '2026-09-14 22:49:23', NULL, '2026-09-14 22:42:52', '2026-09-14 22:49:29'),
(4, NULL, 1, 1, 'Cliente WhatsApp: Atendimento Agendado', NULL, NULL, 'high', '2026-09-15 18:00:50', NULL, NULL, '2026-09-15 13:37:50', '2026-09-15 13:37:50'),
(5, NULL, 1, 8, 'Cliente WhatsApp: Atendimento Agendado, 14 h', NULL, NULL, 'high', '2026-09-15 18:00:32', '2026-09-15 13:41:46', NULL, '2026-09-15 13:40:32', '2026-09-15 13:41:46'),
(6, NULL, 1, 8, 'Cliente WhatsApp: Atendimento Agendado, 14:30 h', NULL, NULL, 'high', '2026-09-15 18:00:20', '2026-09-15 13:41:45', NULL, '2026-09-15 13:41:20', '2026-09-15 13:41:45');

-- --------------------------------------------------------

--
-- Estrutura da tabela `task_logs`
--

CREATE TABLE `task_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `task_logs`
--

INSERT INTO `task_logs` (`id`, `task_id`, `user_id`, `action`, `note`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Moveu da etapa \'Em Andamento\' para \'Concluídas\'', NULL, '2026-09-13 23:16:07', '2026-09-13 23:16:07'),
(2, 2, 1, 'Moveu da etapa \'Concluídas\' para \'Pendentes\'', NULL, '2026-09-13 23:16:13', '2026-09-13 23:16:13'),
(3, 2, 1, 'Deu baixa na tarefa (Concluída)', NULL, '2026-09-14 00:00:00', '2026-09-14 00:00:00'),
(4, 2, 1, 'Moveu da etapa \'Concluídos\' para \'Pendentes\'', NULL, '2026-09-14 00:00:04', '2026-09-14 00:00:04'),
(5, 1, 1, 'Alterou a prioridade de \'high\' para \'medium\'', NULL, '2026-09-14 00:26:18', '2026-09-14 00:26:18'),
(6, 1, 1, 'Alterou a prioridade de \'medium\' para \'low\'', NULL, '2026-09-14 00:26:20', '2026-09-14 00:26:20'),
(7, 1, 1, 'Alterou a prioridade de \'low\' para \'high\'', NULL, '2026-09-14 00:26:22', '2026-09-14 00:26:22'),
(8, 1, 1, 'Moveu da etapa \'Em Atendimento\' para \'Pendentes\'', NULL, '2026-09-14 00:30:38', '2026-09-14 00:30:38'),
(9, 3, 1, 'Editou manualmente o título/resumo do card.', NULL, '2026-09-14 22:48:07', '2026-09-14 22:48:07'),
(10, 3, 1, 'Moveu da etapa \'A Fazer\' para \'Em Andamento\'', NULL, '2026-09-14 22:48:16', '2026-09-14 22:48:16'),
(11, 3, 1, 'Moveu da etapa \'Em Andamento\' para \'Concluído\'', NULL, '2026-09-14 22:49:23', '2026-09-14 22:49:23'),
(12, 3, 1, 'Moveu da etapa \'Concluído\' para \'Em Andamento\'', NULL, '2026-09-14 22:49:30', '2026-09-14 22:49:30'),
(13, 6, 1, 'Moveu da etapa \'Pendentes\' para \'Concluído\'', NULL, '2026-09-15 13:41:45', '2026-09-15 13:41:45'),
(14, 5, 1, 'Moveu da etapa \'Pendentes\' para \'Concluído\'', NULL, '2026-09-15 13:41:46', '2026-09-15 13:41:46');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Mauro Alves', 'admin@teste.com', NULL, '$2y$12$O.FaJ01N1W6oBkMK.C.yvOxoLwbNnQXVJovdlRiLOpzq5Oa7EX7BW', NULL, '2026-09-13 22:50:05', '2026-09-13 22:50:05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `whatsapp_messages`
--

CREATE TABLE `whatsapp_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `raw_content` text NOT NULL,
  `passed_filter` tinyint(1) NOT NULL DEFAULT 0,
  `ai_analysis` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ai_analysis`)),
  `status` varchar(255) NOT NULL DEFAULT 'received',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `whatsapp_messages`
--

INSERT INTO `whatsapp_messages` (`id`, `message_id`, `phone_number`, `sender_name`, `raw_content`, `passed_filter`, `ai_analysis`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MSG_TESTE_12345', '5511999998888', 'João Silva', 'Olá, preciso urgentemente que seja emitida a segunda via do boleto de manutenção do compressor de ar que venceu ontem.', 1, NULL, 'filtered', '2026-09-13 22:17:24', '2026-09-13 22:17:24'),
(3, 'MSG_TESTE_12346', '5511999998888', 'João Silva', 'Olá, preciso urgentemente que seja emitida a segunda via do boleto de manutenção do compressor de ar que venceu ontem.', 1, NULL, 'filtered', '2026-09-13 22:25:52', '2026-09-13 22:25:52'),
(4, 'MSG_TESTE_99001', '5511988887777', 'Carlos Eduardo', 'Boa tarde! Gostaria de solicitar um orçamento urgente para a manutenção preventiva de dois compressores de ar na nossa fábrica.', 1, '[]', 'processed', '2026-09-13 23:01:01', '2026-09-13 23:13:04');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Índices para tabela `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Índices para tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices para tabela `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Índices para tabela `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Índices para tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Índices para tabela `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Índices para tabela `stages`
--
ALTER TABLE `stages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stages_slug_unique` (`slug`);

--
-- Índices para tabela `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_whatsapp_message_id_foreign` (`whatsapp_message_id`),
  ADD KEY `tasks_user_id_foreign` (`user_id`),
  ADD KEY `tasks_stage_id_foreign` (`stage_id`);

--
-- Índices para tabela `task_logs`
--
ALTER TABLE `task_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_logs_task_id_foreign` (`task_id`),
  ADD KEY `task_logs_user_id_foreign` (`user_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Índices para tabela `whatsapp_messages`
--
ALTER TABLE `whatsapp_messages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `whatsapp_messages_message_id_unique` (`message_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `stages`
--
ALTER TABLE `stages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `task_logs`
--
ALTER TABLE `task_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `whatsapp_messages`
--
ALTER TABLE `whatsapp_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `stages` (`id`),
  ADD CONSTRAINT `tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_whatsapp_message_id_foreign` FOREIGN KEY (`whatsapp_message_id`) REFERENCES `whatsapp_messages` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `task_logs`
--
ALTER TABLE `task_logs`
  ADD CONSTRAINT `task_logs_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
