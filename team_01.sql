-- phpMyAdmin SQL Dump
-- Database: team_01

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `team_01` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `team_01`;

-- create table organism

CREATE TABLE `organism` (
    `id` int(11) NOT NULL,
    `name` varchar(150) NOT NULL,
    `latin_name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- add test data

INSERT INTO `organism` (`id`, `name`, `latin_name`) VALUES
    (1, 'Human', 'Homo sapiens'),
    (2, 'Mouse', 'Mus musculus'),
    (3, 'Rat', 'Rattus norvegicus'),
    (4, 'Zebrafish', 'Danio rerio'),
    (5, 'Cow', 'Bos taurus');

-- create table user

CREATE TABLE `user` (
    `id` int(11) NOT NULL,
    `username` varchar(30) NOT NULL,
    `password` varchar(65) NOT NULL,
    `is_admin` BOOLEAN NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- add fh_webphp user

INSERT INTO `user` (`id`, `username`, `password`, `is_admin`) VALUES
    (1,
     'fh_webphp',
     '$2y$10$MylXELhq/xiye/a/xzlyzuB7nbtN/ipkPwAZ.4BX1uh.b8Ptzx3W6',
     1);

-- create table genedataitem

CREATE TABLE `genedataitem` (
  `id` int(11) NOT NULL,
  `genename` varchar(255) NOT NULL,
  `genesymbol` varchar(100) NOT NULL,
  `aliases` varchar(255) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `function` varchar(500) DEFAULT NULL,
  `organism_id` int(11) NOT NULL,
  `reviewed` BOOLEAN NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- add test data

INSERT INTO `genedataitem`
(`id`, `genename`, `genesymbol`, `aliases`, `position`, `function`, `organism_id`, `reviewed`, `created_by`)
VALUES
    (1, 'Tumor Protein P53', 'TP53', 'P53', '17p13.1',
     'Tumor suppressor involved in cell cycle regulation and apoptosis',
     1, 1, 1),

    (2, 'Breast Cancer Type 1 Susceptibility Protein', 'BRCA1', NULL, '17q21.31',
     'DNA repair and maintenance of genomic stability',
     1, 1, 1),

    (3, 'Cystic Fibrosis Transmembrane Conductance Regulator', 'CFTR',
     'ABCC7', '7q31.2', NULL,
     1, 0, 1),

    (4, 'Myosin Heavy Chain 7', 'MYH7', 'β-MHC', '14q11.2',
     'Motor protein involved in cardiac muscle contraction',
     1, 1, 1),

    (5, 'Insulin', 'INS', 'Insulin hormone', '11p15.5',
     'Regulates glucose uptake and metabolism',
     1, 1, 1),

    (6, 'p53 Tumor Suppressor (mouse)', 'Trp53', 'p53', '11B2',
     'Regulates cell cycle and apoptosis in response to DNA damage',
     2, 0, 1),

    (7, 'Albumin', 'Alb', 'Serum albumin', '5qE2',
     'Maintains oncotic pressure and transports molecules in blood plasma',
     2, 1, 1),

    (8, 'Hemoglobin subunit beta (rat)', 'Hbb', 'β-globin', '3q11',
     'Oxygen transport in blood',
     3, 1, 1),

    (9, 'Cytochrome c oxidase subunit I', 'COX1', 'COI', 'MT-CO1',
     'Essential component of mitochondrial electron transport chain',
     3, 0, 1),

    (10, 'GATA binding protein 1', 'gata1', 'GATA-1', 'chr1',
     'Transcription factor important for erythropoiesis',
     4, 1, 1),

    (11, 'Fibroblast growth factor 8', 'fgf8', 'FGF8', 'chr12',
     'Key role in embryonic development and cell signaling',
     4, 1, 1),

    (12, 'Casein alpha s1', 'CSN1S1', 'alpha-S1 casein', '6q31',
     'Milk protein involved in nutrition of offspring',
     5, 1, 1),

    (13, 'Growth hormone', 'GH1', 'Somatotropin', '19q13',
     'Stimulates growth, cell reproduction and regeneration',
     5, 0, 1);

-- set primary keys and unique keys
ALTER TABLE `organism`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `user`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `genedataitem`
    ADD PRIMARY KEY (`id`),
    ADD KEY `organism_id` (`organism_id`),
    ADD KEY `created_by` (`created_by`);

-- set foreign keys in genedataitem
ALTER TABLE `genedataitem`
    ADD CONSTRAINT `fk_genedataitem_organism`
        FOREIGN KEY (`organism_id`)
        REFERENCES `organism` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_genedataitem_user`
        FOREIGN KEY (`created_by`)
        REFERENCES `user` (`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE;

-- Add AutoIncrement
ALTER TABLE `organism`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `user`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `genedataitem`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;


COMMIT;
