-- phpMyAdmin SQL Dump
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET
    time_zone = "+00:00";

CREATE TABLE `posts`
(
    `id`         int(11)      NOT NULL,
    `title`      varchar(255) NOT NULL,
    `body`       text         NOT NULL,
    `created_at` datetime     NOT NULL DEFAULT current_timestamp(),
    `author_id`  int(11)      NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

CREATE TABLE `tokens`
(
    `id`         int(11)      NOT NULL,
    `token`      varchar(255) NOT NULL,
    `user_id`    int(11)      NOT NULL,
    `expires_in` datetime     NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

CREATE TABLE `users`
(
    `id`       int(11)      NOT NULL,
    `email`    varchar(255) NOT NULL,
    `name`     varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;
ALTER TABLE `posts`
    ADD PRIMARY KEY (`id`),
    ADD KEY `author_id` (`author_id`);

ALTER TABLE `tokens`
    ADD PRIMARY KEY (`id`),
    ADD KEY `user_id` (`user_id`);

ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `posts`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tokens`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `posts`
    ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

ALTER TABLE `tokens`
    ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

COMMIT;