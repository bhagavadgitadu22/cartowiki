<?php

header('Content-type: text/plain; charset=utf-8');

try
{
	// sous MAMP
	include "acces_bdd.php";
} catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

$sql = 'SELECT * FROM formes JOIN elements ON formes.id_element = elements.id';
$reponse = $bdd->query($sql);

$bool_geojson = false;
$geojson = '{"type": "FeatureCollection", "features": [';

$caracs = array(
    "population" => [],
	"population_etat" => [],
    "nom" => [],
	"wikipedia" => [],
    "capitale" => [],
	"nomade" => [],
    "source" => [],
	"latLng" => []
);

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
		$geojson .= '"annee_debut" : ' . $donnees["annee_debut"] . ', ';
		$geojson .= '"annee_fin" : ' . $donnees["annee_fin"] . ', ';
		$geojson .= '"couleur" : "' . $donnees["couleur"] . '", ';
		$geojson .= '"type_element" : "' . $donnees["type"] . '", ';
		$geojson .= '"id_element" : ' . $donnees["id_element"];
		$geojson .= '}';

		$geojson .= ', ';
		
		$geojson .= $donnees["valeur"];
		
		$geojson .= '}';
		
		$bool_geojson = true;
		
		if ($donnees["type"] == "ville")
		{
			$coor = explode("[", $donnees["valeur"])[1];
			$coor = explode("]", $coor)[0];
			
			if (!array_key_exists($donnees["id_element"], $caracs["latLng"]))
			{
				$caracs["latLng"][$donnees["id_element"]] = [];
			}
			array_push($caracs["latLng"][$donnees["id_element"]], [$donnees["annee_debut"], $donnees["annee_fin"], "[" . $coor . "]"]);
		}
	}
		
	else
	{
		$champ = $donnees["champ"];
		
		if (!array_key_exists($donnees["id_element"], $caracs[$champ]))
		{
			$caracs[$champ][$donnees["id_element"]] = [];
		}
		if ($champ == "nom" or $champ == "source" or $champ == "wikipedia")
		{
			array_push($caracs[$champ][$donnees["id_element"]], [$donnees["annee_debut"], $donnees["annee_fin"], '\"' . $donnees["valeur"] . '\"']);
		}
		else
		{
			array_push($caracs[$champ][$donnees["id_element"]], [$donnees["annee_debut"], $donnees["annee_fin"], $donnees["valeur"]]);
		}
	}
}

$geojson .= ']}';

echo $geojson;

echo ';;;';



function cmp($a, $b)
{
    if ($a[0] == $b[0]) {
        return 0;
    }
    return ($a[0] < $b[0]) ? -1 : 1;
}

echo '{';

$bool_tab = false;

foreach ($caracs as $key_carac => $elmts_carac)
{
	if ($bool_tab) {
		echo ', ';
	}
	
	echo '"' . $key_carac . '"';
	
	echo ":";
	
	echo '{';

	$bool_carac = false;

	foreach ($elmts_carac as $id_elmt => $elmt)
	{
		if ($bool_carac) {
			echo ', ';
		}
		echo '"' . $id_elmt . '"';
		echo ':';

		echo '"[';
		
		usort($elmt, "cmp");
		
		$bool_elmt = false;
		foreach ($elmt as $atome)
		{
			if ($bool_elmt) {
				echo ', ';
			}
			echo '[';
			
			$bool = false;
			foreach ($atome as $noyau)
			{
				if ($bool) {
					echo ', ';
				}
				echo $noyau;
				$bool = true;
			}
			
			echo ']';
			$bool_elmt = true;
		}

		echo ']"';
		
		$bool_carac = true;
	}

	echo '}';

	$bool_tab = true;
	
}

echo "}";



$bdd = NULL;