-- phpMyAdmin SQL Dump
-- Database: team_01

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `team_01` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `team_01`;

-- create table genedataitem

CREATE TABLE `genedataitem` (
  `id` int(11) NOT NULL,
  `genename` varchar(255) NOT NULL,
  `genesymbol` varchar(100) NOT NULL,
  `aliases` varchar(255) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `function` varchar(500) DEFAULT NULL,
  `organism` varchar(150) NOT NULL,
  `reviewed` BOOLEAN NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- add test data

INSERT INTO `genedataitem`
(`id`, `genename`, `genesymbol`, `aliases`, `position`, `function`, `organism`, `reviewed`)
VALUES
(1, 'Tumor Protein P53', 'TP53', 'P53', '17p13.1',
 'Tumor suppressor involved in cell cycle regulation and apoptosis',
 'Homo sapiens', 1),

(2, 'Breast Cancer Type 1 Susceptibility Protein', 'BRCA1', NULL,
 '17q21.31',
 'DNA repair and maintenance of genomic stability',
 'Homo sapiens', 1),

(3, 'Cystic Fibrosis Transmembrane Conductance Regulator', 'CFTR',
 'ABCC7',
 '7q31.2',
 NULL,
 'Homo sapiens', 0);

-- create table user 

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(65) NOT NULL,
  `is_admin` BOOLEAN NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- add fh_webphp user

INSERT INTO `user`
(`id`, `username`, `password`, `is_admin`)
VALUES
 
 (1,
 'fh_webphp',
 '$2y$10$MylXELhq/xiye/a/xzlyzuB7nbtN/ipkPwAZ.4BX1uh.b8Ptzx3W6',
 1);

-- set primary keys and unique username

ALTER TABLE `genedataitem`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

-- Add AutoIncrement

ALTER TABLE `genedataitem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

COMMIT;