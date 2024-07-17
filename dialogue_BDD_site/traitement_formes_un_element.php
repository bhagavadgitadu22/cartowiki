<?php
// Get the raw POST data
$rawData = file_get_contents("php://input");

// Decode the JSON data into a PHP array
$data = json_decode($rawData, true);

// Now you can access the data like a regular array
$id_element = $data["id_element"];
$couleur_element = $data["couleur_element"];
$type_element = $data["type_element"];

// If the JSON contains an array, you can access it like this
$lignes = $data["lignes"];
$nombre_lignes_elements = count($lignes);
$bool = $data["bool"];

$caracs = $data["caracs"];


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
	$prep->execute([$couleur_element, intval($id_element)]);
}
// sinon s'il y a création et que des éléments géométriques ont été créé pour cet élément je le crée dans la bdd
else if ($nombre_lignes_elements > 0)
{
	$prep = $bdd->prepare('INSERT INTO elements (type, couleur) VALUES (?, ?)');
	$prep->execute([$type_element, $couleur_element]);
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
				$prep->execute(['"geometry": {"type" : "MultiPolygon", "coordinates" : ' . $shape . '}', intval($annee_debut), intval($annee_fin), intval($id_bdd)]);
			} else { 
				$prep->execute(['"geometry": {"type" : "Point", "coordinates" : ' . $shape . '}', intval($annee_debut), intval($annee_fin), intval($id_bdd)]);
			}
			$bool = true;
			break;
		case 2:
			$prep = $bdd->prepare('INSERT INTO formes (id_element, champ, valeur, annee_debut, annee_fin) VALUES (?, "geometry", ?, ?, ?)');
			if ($type_element == "pays") {
				$prep->execute([intval($id_element), '"geometry": {"type" : "MultiPolygon", "coordinates" : ' . $shape . '}', intval($annee_debut), intval($annee_fin)]); 
			} else {
				$prep->execute([intval($id_element), '"geometry": {"type" : "Point", "coordinates" : ' . $shape . '}', intval($annee_debut), intval($annee_fin)]);
			}
			$bool = true;
			break;
		case 3:
			$prep = $bdd->prepare('DELETE FROM formes WHERE id = ?');
			$prep->execute([intval($id_bdd)]);
			break;
	}
}

// si mon élément ne compte plus aucune forme, je le supprime de la bdd et je supprime les caracs correspondantes
if (!$bool)
{
	$prep = $bdd->prepare('DELETE FROM elements WHERE id = ?');
	$prep->execute([intval($id_element)]);
	
	$prep = $bdd->prepare('DELETE FROM formes WHERE id_element = ?');
	$prep->execute([intval($id_element)]);
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
					$prep->execute([$key_carac, $valeur, intval($annee_debut), intval($annee_fin), intval($elmt[4])]);
					break;
				case 2:
					$prep = $bdd->prepare('INSERT INTO formes (id_element, champ, valeur, annee_debut, annee_fin) VALUES (?, ?, ?, ?, ?)');
					$prep->execute([intval($id_element), $key_carac, $valeur, intval($annee_debut), intval($annee_fin)]);
					break;
				case 3:
					$prep = $bdd->prepare('DELETE FROM formes WHERE id = ?');
					$prep->execute([intval($elmt[4])]);
					break;
			}
		}
	}
}



$bdd = NULL;