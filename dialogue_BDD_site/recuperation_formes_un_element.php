<?php

header('Content-type: text/plain; charset=utf-8');

$id_element = $_POST["id_element"];
$type_element = $_POST["type_element"];


try
{
	// sous MAMP
	include "acces_bdd.php";
} catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}



$sql_noms_autres_formes = "";

if ($id_element != "creation")
{
	$sql = 'SELECT couleur FROM elements WHERE id=' . $id_element;
	$reponse = $bdd->query($sql);
	
	$couleur = $reponse->fetch()[0];
	
	echo $couleur;
	
	echo ';;;';
	
	
	
	$sql = 'SELECT * FROM formes WHERE id_element=' . $id_element;

	$reponse = $bdd->query($sql);

	$bool_geojson = false;
	$geojson = '{"type": "FeatureCollection", "features": [';
	
	$caracs;
	
	if ($type_element == "pays")
	{
		$caracs = array(
			"population_etat" => [],
			"nom" => [],
			"wikipedia" => [],
			"nomade" => [],
			"source" => [],
			"latLng" => []
		);
	}
	if ($type_element == "ville")
	{
		$caracs = array(
			"population" => [],
			"nom" => [],
			"wikipedia" => [],
			"capitale" => [],
			"source" => [],
			"latLng" => []
		);
	}

	$inc = 0;

	while ($donnees = $reponse->fetch(PDO::FETCH_ASSOC))
	{
		if ($donnees["champ"] == "geometry")
		{
			if ($bool_geojson)
			{
				$geojson .= ', ';
			}
			
			$geojson .= '{"type": "Feature", ';
			
			$geojson .= '"properties" : {';
			$geojson .= '"statut" : 0,';
			$geojson .= '"id_bdd" : ' . $donnees["id"] . ', ';
			$geojson .= '"id" : ' . $inc . ', ';
			$geojson .= '"annee_debut" : ' . $donnees["annee_debut"] . ', ';
			$geojson .= '"annee_fin" : ' . $donnees["annee_fin"];
			$geojson .= '}';

			$geojson .= ', ';
			
			$geojson .= $donnees["valeur"];
			
			$geojson .= '}';
			
			$bool_geojson = true;
			
			// ajout dans caracs de années début et fin formes			
			if (!array_key_exists($inc, $caracs["latLng"]))
			{
				$caracs["latLng"][$inc] = [];
			}
			array_push($caracs["latLng"][$inc], [$donnees["annee_debut"], $donnees["annee_fin"]]);
			
			$inc += 1;
		}
		
		else
		{
			$champ = $donnees["champ"];

			if ($champ == "nom" or $champ == "source" or $champ == "wikipedia")
			{
				array_push($caracs[$champ], [$donnees["annee_debut"], $donnees["annee_fin"], '"' . $donnees["valeur"] . '"', 0, $donnees["id"]]);
			}
			else
			{
				array_push($caracs[$champ], [$donnees["annee_debut"], $donnees["annee_fin"], $donnees["valeur"], 0, $donnees["id"]]);
			}
		}
	}

	$geojson .= ']}';

	echo $geojson;

	echo ';;;';
	
	
	echo '{';

	$bool_tab = false;
	
	foreach ($caracs as $key_carac => $elmts_carac)
	{
		if ($bool_tab) {
			echo ', ';
		}
		
		echo '"' . $key_carac . '"';
		
		echo ":";
		
		if ($key_carac != "latLng")
		{		
			echo '[';
			
			$bool_carac = false;
			
			foreach ($elmts_carac as $elmt)
			{
				if ($bool_carac)
				{
					echo ', ';
				}

				echo '[';
				
				$bool = false;
				
				foreach ($elmt as $atome)
				{
					if ($bool)
					{
						echo ', ';
					}
					
					echo $atome;
					
					$bool = true;
				}
					
				echo ']';
				
				$bool_carac = true;
			}
			
			echo ']';
			
			$bool_tab = true;
		}
		else
		{
			echo '{';
			
			$bool_carac = false;
			
			foreach ($elmts_carac as $id_elmt => $elmt)
			{
				if ($bool_carac)
				{
					echo ', ';
				}
				
				echo '"' . $id_elmt . '"';
				echo ':';

				echo '[';
				
				$bool_elmt = false;
				foreach ($elmt as $atome)
				{
					if ($bool_elmt) {
						echo ', ';
					}
					
					$bool = false;
					foreach ($atome as $noyau)
					{
						if ($bool) {
							echo ', ';
						}
						echo $noyau;
						$bool = true;
					}
					
					$bool_elmt = true;
				}

				echo ']';
				
				$bool_carac = true;
			}

			echo '}';

			$bool_tab = true;
		}
	}

	echo "}";
	
	echo ";;;";
	
	

	echo $inc-1;

	echo ';;;';
	
	$sql_noms_autres_formes = 'SELECT elements.id, formes.valeur FROM `formes` JOIN `elements` ON formes.id_element=elements.id WHERE champ="nom" AND type="pays" AND elements.id!=' . $id_element . ' ORDER BY elements.id, formes.annee_debut';
}

else
{
	// couleur initialisé à noir pour un nouvel élément
	echo "#000000";
	echo ';;;';
	echo "{\"type\": \"FeatureCollection\", \"features\": []}";
	echo ";;;";
	if ($type_element == "pays") { echo '{"population_etat": [], "nom":[], "wikipedia": [], "nomade":[], "source":[], "latLng":{}}'; }
	if ($type_element == "ville") { echo '{"population": [], "nom":[], "wikipedia": [], "capitale":[], "source":[], "latLng":{}}'; }
	echo ";;;";
	echo -1;
	echo ";;;";
	
	$sql_noms_autres_formes = 'SELECT elements.id, formes.valeur FROM `formes` JOIN `elements` ON formes.id_element=elements.id WHERE champ="nom" AND type="pays" ORDER BY elements.id, formes.annee_debut';
}



if ($type_element == "pays" && !(isset($_POST["decalque"])))
{
	$reponse = $bdd->query($sql_noms_autres_formes);

	$noms_autres_formes = '{';
	
	$bool = false;
	$id_en_cours = false;

	while ($donnees = $reponse->fetch(PDO::FETCH_ASSOC))
	{
		# si on est au début de tableau
		if (!$bool)
		{
			$id_en_cours = $donnees["id"];
			
			$noms_autres_formes .= '"' . $donnees["valeur"];
			
			$bool = true;
		}
		# si on vient de passer à un nouvel id (mais pas le premier)
		else if ($donnees["id"] != $id_en_cours)
		{
			$noms_autres_formes .= '":' . $id_en_cours . ',';
			
			$id_en_cours = $donnees["id"];
			
			$noms_autres_formes .= '"' . $donnees["valeur"];
		}
		# sinon c'est qu'on est toujours sur même idée qu'avant
		else
		{
			$noms_autres_formes .= '/' . $donnees["valeur"];
		}
		
	}

	$noms_autres_formes .= '":' . $id_en_cours . '}';

	echo $noms_autres_formes;
}



$bdd = NULL;