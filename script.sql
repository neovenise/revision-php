-- Création de la table pour le travail de révision
-- De Damien RODRIGUEZ


CREATE DATABASE IF NOT EXISTS db_etudiants;
USE db_etudiants;

DROP TABLE IF EXISTS etudiant;
DROP TABLE IF EXISTS section;


CREATE TABLE IF NOT EXISTS SECTION (
    `idSection` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `libelleSection` varchar(50) DEFAULT NULL
    );

    INSERT INTO SECTION(`libelleSection`) VALUES('BTS SIO 1 SLAM'),('BTS SIO 1 SISR'),('TERMINAL');

CREATE TABLE IF NOT EXISTS ETUDIANT (
    `idEtudiant` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `nom` VARCHAR(25) NOT NULL,
    `prenom` VARCHAR(20) NOT NULL,
    `datenaissance` date default null,
    `email` varchar(100) default null,
    `telmobile` varchar(20) default null,
    `idSection` integer default null
    );

ALTER TABLE ETUDIANT ADD CONSTRAINT `fk_etudiant_idsection` FOREIGN KEY (`idSection`) REFERENCES section(`idSection`);

INSERT INTO `ETUDIANT`(`nom`,`prenom`,`datenaissance`,`email`,`telmobile`,`idSection`) VALUES
('rodriguez','damien','2002-01-03','damien@damienrodriguez.fr','+330699887766',1),
('Morabet','Moumen','2003-01-01','moumen@jsp.fr','+330699887766',1),
('Youssef','En nour','2099-01-01','yous@sef.en','+330699887766',2),
('Julio','Morgan','2010-01-02','JULIoMorgan@gmail.com','+330699887766',2),
('AAAAAAAAAAAAA','AAAAAAAAAAA','2030-12-31','AAAAAAAA@gmail.com','+330699887766',3)