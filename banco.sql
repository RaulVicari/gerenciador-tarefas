SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `tarefas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `status` enum('Pendente','Concluída') DEFAULT 'Pendente',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tarefas` (`id`, `titulo`, `descricao`, `status`, `data_criacao`) VALUES
(1, 'Dormir', 'dsad', 'Concluída', '2026-11-16 04:05:10');


ALTER TABLE `tarefas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tarefas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
