<?php

header('Location: ../index.php');

// Sous MAMP
$bdd = new PDO('mysql:host=localhost; dbname=base_cartowiki; charset=utf8', 'root', 'root');


// Suppression des anciennes tables si elles existaient
$bdd->exec('DROP TABLE IF EXISTS elements, formes;');


// Création de la table elements
$bdd->exec('CREATE TABLE elements (	id int NOT NULL AUTO_INCREMENT,

									type varchar(200),
									couleur varchar(200),
									
									PRIMARY KEY (id))
									ENGINE = InnoDB;');
							
							
// Création de la table villes													
$bdd->exec('CREATE TABLE formes (	id int NOT NULL AUTO_INCREMENT,

									id_element int,
									champ varchar(200),
									valeur text,
									annee_debut int,
									annee_fin int,

									PRIMARY KEY (id))
									ENGINE = InnoDB;');


$bdd = NULL;

?>