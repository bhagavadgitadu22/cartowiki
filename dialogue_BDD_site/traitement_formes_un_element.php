<?php

header('Content-type: text/plain; charset=utf-8');

$id_element = $_POST["id_element"];
$couleur_element = $_POST["couleur_element"];
$type_element = $_POST["type_element"];

$lignes = $_POST["lignes"];
$nombre_lignes_elements = count($lignes);
$bool = $_POST["bool"];

$caracs = $_POST["caracs"];



try
{
	// sous MAMP
	include "acces_bdd.php";
} catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}



$aucun_ajout_carac = false;

// si on n'a pas affaire à la création d'un élément on peut mettre à jour la couleur de l'élément
if ($id_element != "creation")
{
	$prep = $bdd->prepare('UPDATE elements SET couleur = ? WHERE id = ?');
	$prep->execute(array($couleur_element, intval($id_element)));
}
// sinon s'il y a création et que des éléments géométriques ont été créé pour cet élément je le crée dans la bdd
else if ($nombre_lignes_elements > 0)
{
	$prep = $bdd->prepare('INSERT INTO elements (type, couleur) VALUES (?, ?)');
	$prep->execute(array($type_element, $couleur_element));
	$id_element = $bdd->lastInsertId();
}
else
{
	$aucun_ajout_carac = true;
}




// je modifie les éléments géométriques nécessaires dans la bdd, j'ajoute les nouveaux
for ($i = 0; $i < $nombre_lignes_elements; $i++) {
	$ligne = $lignes[$i];
	
    $shape = $ligne["shape"];
	$annee_debut = $ligne["annee_debut"];
	$annee_fin = $ligne["annee_fin"];
	$statut = $ligne["statut"];
	$id_bdd = $ligne["id_bdd"];
	
	switch ($statut) {
		case 1:
			$prep = $bdd->prepare('UPDATE formes SET champ = "geometry", valeur = ?, annee_debut = ?, annee_fin = ? WHERE id = ?');
			if ($type_element == "pays") 
			{
				$prep->execute(array('"geometry": {"type" : "MultiPolygon", "coordinates" : ' . $shape . '}', intval($annee_debut), intval($annee_fin), intval($id_bdd)));
			} else { 
				$prep->execute(array('"geometry": {"type" : "Point", "coordinates" : ' . $shape . '}', intval($annee_debut), intval($annee_fin), intval($id_bdd)));
			}
			$bool = true;
			break;
		case 2:
			$prep = $bdd->prepare('INSERT INTO formes (id_element, champ, valeur, annee_debut, annee_fin) VALUES (?, "geometry", ?, ?, ?)');
			if ($type_element == "pays") {
				$prep->execute(array(intval($id_element), '"geometry": {"type" : "MultiPolygon", "coordinates" : ' . $shape . '}', intval($annee_debut), intval($annee_fin))); 
			} else {
				$prep->execute(array(intval($id_element), '"geometry": {"type" : "Point", "coordinates" : ' . $shape . '}', intval($annee_debut), intval($annee_fin)));
			}
			$bool = true;
			break;
		case 3:
			$prep = $bdd->prepare('DELETE FROM formes WHERE id = ?');
			$prep->execute(array(intval($id_bdd)));
			break;
	}
}

// si mon élément ne compte plus aucune forme, je le supprime de la bdd et je supprime les caracs correspondantes
if (!$bool)
{
	$prep = $bdd->prepare('DELETE FROM elements WHERE id = ?');
	$prep->execute(array(intval($id_element)));
	
	$prep = $bdd->prepare('DELETE FROM formes WHERE id_element = ?');
	$prep->execute(array(intval($id_element)));
}



if (!$aucun_ajout_carac)
{
	// je modifie les caractéristiques nécessaires dans la bdd, j'ajoute les nouvelles
	foreach ($caracs as $key_carac => $elmts_carac)
	{
		$nombre_elmts_carac = count($elmts_carac); 
		
		for ($i = 0; $i < $nombre_elmts_carac; $i++) {
			
			$elmt = $elmts_carac[$i];
			
			$annee_debut = $elmt[0];
			$annee_fin = $elmt[1];
			$valeur = $elmt[2];
			$statut = $elmt[3];
			
			switch ($statut) {
				case 1:
					$prep = $bdd->prepare('UPDATE formes SET champ = ?, valeur = ?, annee_debut = ?, annee_fin = ? WHERE id = ?');
					$prep->execute(array($key_carac, $valeur, intval($annee_debut), intval($annee_fin), intval($elmt[4])));
					break;
				case 2:
					$prep = $bdd->prepare('INSERT INTO formes (id_element, champ, valeur, annee_debut, annee_fin) VALUES (?, ?, ?, ?, ?)');
					$prep->execute(array(intval($id_element), $key_carac, $valeur, intval($annee_debut), intval($annee_fin)));
					break;
				case 3:
					$prep = $bdd->prepare('DELETE FROM formes WHERE id = ?');
					$prep->execute(array(intval($elmt[4])));
					break;
			}
		}
	}
}



$bdd = NULL;

?>