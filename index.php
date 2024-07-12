<html>

<head>

	<meta charset=utf-8 />
	<title>CartoWiki</title>
	<meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />

	<!-- Load Leaflet from CDN -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
		integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
		crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
		integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
		crossorigin=""></script>
		
	<!-- side panel leaflet -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/leaflet-sidebar.css" />
	<script src="js/leaflet-sidebar.js"></script>
	
	<script src="js/leaflet.pattern.js"></script>
	
	<!-- JQuery -->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" type="text/css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	
	<link rel="stylesheet" href="css/style.css" />
	
</head>

<body>
	
	<div id="conteneur_gauche" class="leaflet-sidebar collapsed">
	
        <div class="leaflet-sidebar-tabs">
            <!-- top aligned tabs -->
            <ul role="tablist">
                <li><a href="#creation" role="tab"><i class="fa fa-plus active" style="line-height: inherit;"></i></a></li>
				<li><a href="#player" role="tab"><i class="fa fa-play" style="line-height: inherit;"></i></a></li>
            </ul>
        </div>

        <div class="leaflet-sidebar-content">
		
            <div class="leaflet-sidebar-pane" id="creation">
			
               <h1 class="leaflet-sidebar-header">
                    Création
                    <span class="leaflet-sidebar-close"><i class="fa fa-caret-left" style="line-height: inherit;"></i></span>
                </h1>
				
				<br/>
				<form id="formulaire_element" class='form-style-5' method="post">
				
					<label>
						<label style="padding-bottom:6px;"><input type="radio" id="type_element" name="type_element" value="pays">Pays</input></label>
						<label><input type="radio" id="type_element" name="type_element" value="ville">Ville</input></label>
					</label>
					<button type="button" onclick="valider()">Valider</button>
					
				</form>
				
			</div>

				
            <div class="leaflet-sidebar-pane" id="player">
			
                <h1 class="leaflet-sidebar-header">
                    Animation
                    <span class="leaflet-sidebar-close"><i class="fa fa-caret-left" style="line-height: inherit;"></i></span>
                </h1>
			
				</br>
				<div id="conteneur_gauche_player" class="form-style-5">

					<div class="slidecontainer">
						<label><span id="valeur_ans_par_s" style="font-weight:bold;">50</span> ans/s</label>
						<br/>
						<input type="range" min="1" max="100" value="50" class="slider_play" id="slider_play">
					</div>
					<button type="button" id="bouton_player" value="play" onclick="play_geojson()">Play</button>
					<label><input type="checkbox" id="my-toggle" onclick="reset_figures()">Afficher populations pays</label>
				
				</div>
				
			</div>
		</div>
		
	</div>
	
	
	<div id="map"></div>
	
	<div id="charte_cartowiki" title="Charte de Cartowiki" class="form-style-5" style="display: none;">
		<h1 style="padding-top: 0;">Qu’est-ce que Cartowiki ?</h1>
		<p>C’est un projet visant à représenter le monde de manière cartographique de -3000 à aujourd’hui. Il s’agit donc d’une carte du monde au 31 décembre de chaque année depuis 5000 ans. Cette carte représente deux types d’éléments : les villes et les états.</p>
		<h1>Voilà pour la carto, mais Wiki ? </h1>
		<p>En effet, nous l’assumons, Wikipédia est la principale source de données, et c'est pourquoi c'est vers <a href="https://fr.wikipedia.org/">Wikipédia</a> que renvoie chaque élément créé.</p>
		<h1>Les villes telles que représentées dans Cartowiki</h1>
		<h2>Définition d’une ville</h2>
		<p>Le terme de ville dans Cartowiki est à prendre au sens d’agglomération d’êtres humains ayant vocation à être pérenne. Ainsi, un camp de réfugiés comptant des milliers d’habitants, mais n’ayant pas vocation à être pérenne ne sera pas représenté sur Cartowiki. Autre exemple : le camp d’une tribu nomade, revenant annuellement au même endroit, même s’il est effectivement présent au 31 décembre de chaque année, ne sera pas représenté sur Cartowiki.</p>
		<p>La taille de l’établissement humain n’a pas d’importance pour Cartowiki. On peut compter comme ville un hameau d’une centaine d’habitants, pour peu qu’il soit pérenne et qu’il soit réellement une agglomération d’êtres humains (une densité minimum est donc requise).</p>
		<h2>Population d’une ville</h2>
		<p>Pour les populations, Cartowiki vise à représenter la population de l’agglomération. Autrement dit, l’unité urbaine dans sa définition française. Il ne s’agit donc ni des populations des limites administratives, ni des populations des aires métropolitaines.</p>
		<p>Les agglomération transfrontalières constituent l’unique cas exceptionnel, ou les limites administratives, ici les frontières des états, sont prises en compte. En effet, dans le cas d’une agglomération s’étendant sur deux états, Cartowiki représente deux villes distinctes.</p>
		<p>Dans le cas d’une ville qui, avec le temps, finit par se faire absorber par une autre, le choix a été fait de supprimer la ville qui se fait absorber à la date de la fusion.</p>
		<h2>Définition d’une capitale</h2>
		<p>Dans la plupart des cas, il est aisé de définir ou se trouve le pouvoir d’un état. Dans certains autres, les pouvoirs législatifs et exécutifs peuvent être dispatchés dans différentes villes. Pour Cartowiki, c’est le pouvoir exécutif qui l’emporte sur tout autre pouvoir, qu’il soit judiciaire, législatif, ou symbolique.</p>
		<h2>Normes de toponymie</h2>
		<p>Cartowiki est un projet francophone. Le nom des états est indiqué en français, tel que l’histoire les appellent, et non tel que les habitants d’alors les appelaient.</p>
		<p>Pour les villes, la règle est de mettre le nom de la ville d’alors, suivi entre parenthèses du nom actuel de cette même ville.</p>
		<h1>Les états tels que représentés dans Cartowiki</h1>
		<h2>Définition d’un état</h2>
		<p>Un état est une société politique résultant de la fixation, sur un territoire délimité par des frontières, d'un groupe humain régi par un pouvoir institutionnalisé.</p>
		<p>Cartowiki ne vise à représenter que les états souverains. La difficulté est donc de définir la souveraineté, avec un objectif de constance à travers les âges.</p>
		<p>Pour définir si un état est souverain, le critère qui est retenu est sa capacité à entretenir une diplomatie. C’est donc la politique étrangère qui définira ou non si l’état figure dans Cartowiki. Si la politique étrangère d’un état est très fortement aligné sur celle d’un empire plus puissant, l’état est tout de même considéré comme ayant une politique étrangère propre.</p>
		<h2>Définition d’un proto-état</h2>
		<p>Dans les temps anciens, de nombreux groupes humains se sont unis sans pour autant pouvoir être qualifié d’état, au sens de la définition sus-énoncée.</p>
		<p>Cette forme proto-étatique, qui couvrait un territoire, sans réel pouvoir institutionnalisé, est tout de même représenté dans Cartowiki. Il s’agit par exemple des peuples dont on reconnaît le territoire et une forme d’unité culturelle, mais dont on ne retrouve pas d’institution politique associée.</p>
	</div>
	
	
	<div id="conteneur_droite" class="leaflet-sidebar collapsed">
	
		<div class="leaflet-sidebar-tabs">
            <!-- top aligned tabs -->
            <ul role="tablist">
                <li><a href="#caracs" role="tab"><i class="fa fa-bars active" style="line-height: inherit;"></i></a></li>
            </ul>
        </div>

        <div class="leaflet-sidebar-content">
		
            <div class="leaflet-sidebar-pane" id="caracs">
			
               <h1 class="leaflet-sidebar-header">
                    Caractéristiques
                    <span class="leaflet-sidebar-close"><i class="fa fa-caret-right" style="line-height: inherit;"></i></span>
                </h1>
				
				<br/>
				<div id="conteneur_droite_caracs" class="form-style-5"></div>
			</div>
		</div>
	</div>


<script>
// changement Leaflet

L.Map.include({
  _initControlPos: function () {
    var corners = this._controlCorners = {},
      l = 'leaflet-',
      container = this._controlContainer =
        L.DomUtil.create('div', l + 'control-container', this._container);

    function createCorner(vSide, hSide) {
      var className = l + vSide + ' ' + l + hSide;

      corners[vSide + hSide] = L.DomUtil.create('div', className, container);
    }

    createCorner('top', 'left');
    createCorner('top', 'right');
    createCorner('bottom', 'left');
    createCorner('bottom', 'right');

    createCorner('top', 'center');
    createCorner('middle', 'center');
    createCorner('middle', 'left');
    createCorner('middle', 'right');
    createCorner('bottom', 'center');
  }
});


// définition variables

var figures;
var centroids = [];
var geoJSONlayer_pays;
var geoJSONlayer_villes;
var geoJSONlayer_population_pays;

var id_actif = "";
var type_actif;

var caracs = {};

var annee;

var min_annee = -3000;
var max_annee = 2022;

var caracs_a_cette_date = {};

var myPlayer;

var max_villes_simultanees = 50;


// initialisation

var map = L.map('map', {
	attributionControl: false
}).setView([46.988332, 2.605527], 5);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
	noWrap: true,
	attribution: '',
	subdomains: 'abcd',
	minZoom: 1,
	maxZoom: 18
}).addTo(map);

// patterns for the capitals
//var backgroundNoPopulation = new L.PatternPath({ d: 'M 0 0 L 0 1 L 1 1 L 1 0 Z', fill: true, stroke: false, fillColor: "#A0A0A0", fillOpacity: 1.0 });
var starNoPopulation = new L.PatternPath({ d: 'M 0.500 0.750 L 0.794 0.905 L 0.738 0.577 L 0.976 0.345 L 0.647 0.298 L 0.500 0.000 L 0.353 0.298 L 0.024 0.345 L 0.262 0.577 L 0.206 0.905 L 0.500 0.750', fill: true, stroke: false, fillColor: "#A0A0A0", fillOpacity: 1.0 });
var star = new L.PatternPath({ d: 'M 0.500 0.750 L 0.794 0.905 L 0.738 0.577 L 0.976 0.345 L 0.647 0.298 L 0.500 0.000 L 0.353 0.298 L 0.024 0.345 L 0.262 0.577 L 0.206 0.905 L 0.500 0.750', fill: true, stroke: false, fillColor: "#000000", fillOpacity: 1.0 });
var starSelected = new L.PatternPath({ d: 'M 0.500 0.750 L 0.794 0.905 L 0.738 0.577 L 0.976 0.345 L 0.647 0.298 L 0.500 0.000 L 0.353 0.298 L 0.024 0.345 L 0.262 0.577 L 0.206 0.905 L 0.500 0.750', fill: true, stroke: false, fillColor: "#f416d7", fillOpacity: 1.0 });

map.createPane("capitalPane");
map.getPane("capitalPane").style.zIndex = 620;

// tout ce qui concerne la charte

L.control.attribution({
  position: 'topcenter'
}).addTo(map);

map.attributionControl.setPrefix("<a id=\"charte\" href=\"\">Charte d'utilisation de Cartowiki</a>");

var dialogue_charte = $( "#charte_cartowiki" ).dialog({
		create: function(e, ui) {
			// 'this' is #dialog
			// get the whole widget (.ui-dialog) with .dialog('widget')
			$(this).dialog('widget')
				// find the title bar element
				.find('.ui-dialog-titlebar')
				// alter the css classes
				.removeClass('ui-corner-all')
				.addClass('ui-corner-top');
		},
		autoOpen: false,
		position: { my: "center top", at: "center top+25", collision: 'none', of: "#charte" },
		draggable: false,
		resizable: false,
		width: "42%",
		height: window.innerHeight - 90,
		classes: {
			"ui-dialog-titlebar": "highlight-titlebar"
		}
	});
	
$('#charte').click( function(e) {
  e.preventDefault();
  
  dialogue_charte.dialog( "option", "height", window.innerHeight - 90 );
  dialogue_charte.dialog('open');
});

dialogue_charte.keydown(function (e) {
	if (e.keyCode == 13) {
		var inputs = $(this).parents("form").eq(0).find(":input");
		if (inputs[inputs.index(this) + 1] != null) {                    
			inputs[inputs.index(this) + 1].focus();
		}
		e.preventDefault();
		return false;
	}
});


// ajout éléments carte

var sidebar = L.control.sidebar({
    autopan: false,       // whether to maintain the centered map point when opening the sidebar
    closeButton: true,    // whether t add a close button to the panes
    container: 'conteneur_droite', // the DOM container or #ID of a predefined sidebar container that should be used
    position: 'right',    // left or right
}).addTo(map).open('caracs');

var sidebar2 = L.control.sidebar({
    autopan: false,       // whether to maintain the centered map point when opening the sidebar
    closeButton: true,    // whether t add a close button to the panes
    container: 'conteneur_gauche', // the DOM container or #ID of a predefined sidebar container that should be used
    position: 'left',     // left or right
}).addTo(map);


var bottom_slider = L.Control.extend({
    options: {
        position: 'bottomcenter',
    },

    onAdd: function (map) {
        var sliderContainer = L.DomUtil.create('div', 'slider_container');
		
		dims_map = map.getPixelBounds();
		width_map = dims_map.max.x - dims_map.min.x;
		
		$(sliderContainer).css("width", 0.9*width_map);
		
		$(sliderContainer).css("display", "flex");
		$(sliderContainer).css("align-items", "center");
		
		$(sliderContainer).append('<div id="slider_range" style="float:left; width:88%;"></div><div id="slider_date" style="float: right; width: 120px; padding-left:1%;"><input type="number" id="slider_date_annee" min="-3000" max="2022" value="2020" style="font-family: Georgia; color: black; font-size:30px; padding-top: 5px; padding-bottom: 7px; padding-left: 11px; padding-right: 7px;"></input></div>');
		
		L.DomEvent.disableClickPropagation(sliderContainer);
		L.DomEvent.disableScrollPropagation(sliderContainer);
		
        return sliderContainer;
    }
});

map.on('resize', function () {
	dims_map = map.getPixelBounds();
	width_map = dims_map.max.x - dims_map.min.x;
	
	$(".slider_container").css("width", 0.9*width_map);
});

map.addControl(new bottom_slider());

$( "#slider_range" ).slider({
	min: min_annee, 
	max: max_annee,
	slide: function( event, ui ) {
		$( "#slider_date_annee" ).val(ui.value);
		
		map.removeLayer(geoJSONlayer_pays);
		map.removeLayer(geoJSONlayer_villes);
		map.removeLayer(geoJSONlayer_population_pays);
		
		annee = parseInt($( "#slider_date_annee" ).val());
		determination_caracs_a_cette_date();
		affichage_figures();
		
		if (id_actif != "") { actualisation_conteneur_droite(); };
	}
});

$( "#slider_date_annee" ).bind('change', function(){
	$( "#slider_range" ).slider('value', $( "#slider_date_annee" ).val());
	
	map.removeLayer(geoJSONlayer_pays);
	map.removeLayer(geoJSONlayer_villes);
	map.removeLayer(geoJSONlayer_population_pays);
	
	annee = parseInt($( "#slider_date_annee" ).val());
	determination_caracs_a_cette_date();
	affichage_figures();
	
	if (id_actif != "") { actualisation_conteneur_droite(); };
});


// pour régler la vitesse du play
$( "#slider_play" ).change(function(e) {
	var vitesse_play = document.getElementById("slider_play").value;
	var affichage_vitesse_play = document.getElementById("valeur_ans_par_s");
	affichage_vitesse_play.innerHTML = vitesse_play;
});


$.post('dialogue_BDD_site/recuperation_formes.php', function(result) {
	
		result = result.split(";;;");
        // console.log(result);
		
		figures = JSON.parse(result[0]);

		caracs = JSON.parse(result[1].replace(/\n/g, "</br>"));
		
		Object.keys(caracs).forEach(function (key) {
			for (it in caracs[key])
			{
				caracs[key][it] = JSON.parse(caracs[key][it]);
			}
		});
		
		determination_liste_centroid();

		annee = parseInt($( "#slider_date_annee" ).val());
		determination_caracs_a_cette_date();
		affichage_figures();
	});
	
map.on('moveend', function() { 
	reset_figures();
});
	
function reset_figures()
{
	map.removeLayer(geoJSONlayer_pays);
	map.removeLayer(geoJSONlayer_villes);
	map.removeLayer(geoJSONlayer_population_pays);
	
	affichage_figures();
}

// fonctions

function valider()
{
	var radioValue = $("input[name='type_element']:checked").val();
	
	if (typeof(radioValue) != 'undefined')
	{
		if (radioValue == "pays")
		{
			window.location.href = "creation/page_creation_pays.php";
		}
		else
		{
			window.location.href = "creation/page_creation_ville.php";
		}
	}
}

/// Détermine la liste des centroides de chaque pays avec leurs dates et populations correspondantes
function determination_liste_centroid()
{
	for (var id_multipolygon in figures.features) 
	{
		// Si le multipolygon est une ville, on passe au multipolygon suivant
		if (figures.features[id_multipolygon].properties.type_element == "ville")
		{
			continue;
		}
		for (var polygon in figures.features[id_multipolygon].geometry.coordinates)
		{
			// Si le multipolygon n'a pas de coordonnées, on passe au polygon suivant
			if ((figures.features[id_multipolygon].geometry.coordinates[polygon][0]) == "undefined")
			{
				continue;
			}
			var id_pays = figures.features[id_multipolygon].properties.id_element;
			var centroid = get_polygon_centroid(figures.features[id_multipolygon].geometry.coordinates[polygon][0]);
			var has_capitale = [];
			// var annee_debut_polygone = figures.features[id_multipolygon].properties.annee_debut;
			// var annee_fin_polygone = figures.features[id_multipolygon].properties.annee_fin;
			// for (var instance_capitale in caracs["capitale"])
			// {
			// 	var annee_debut_capitale = caracs["capitale"][instance_capitale][0];
			// 	var annee_fin_capitale = caracs["capitale"][instance_capitale][1];
			// 	// Si la ville correspond aux dates du polygone et que c'est une capitale
			// 	if (!caracs["capitale"][instance_capitale][2] && !( annee_debut_polygone <= annee_debut_capitale <= annee_fin_polygone || annee_debut_polygone <= annee_fin_capitale <= annee_fin_polygone || annee_debut_capitale <= annee_debut_polygone <= annee_fin_capitale || annee_debut_capitale <= annee_fin_polygone <= annee_fin_capitale))
			// 	{
			// 		continue;
			// 	}
			// 	// Si la capitale se trouve dans le polygone aux bonnes dates
			// 	for (var position_id in caracs["latLng"][instance_capitale]){
			// 		var annee_debut_position_capitale = caracs["latLng"][instance_capitale][position_id][0];
			// 		var annee_fin_position_capitale = caracs["latLng"][instance_capitale][position_id][1];
			// 		if (!( annee_debut_polygone <= annee_debut_position_capitale <= annee_fin_polygone || annee_debut_polygone <= annee_fin_position_capitale <= annee_fin_polygone || annee_debut_position_capitale <= annee_debut_polygone <= annee_fin_position_capitale || annee_debut_position_capitale <= annee_fin_polygone <= annee_fin_position_capitale))
			// 		{
			// 			continue;
			// 		}
			// 		if (!( annee_debut_capitale <= annee_debut_position_capitale <= annee_fin_capitale || annee_debut_capitale <= annee_fin_position_capitale <= annee_fin_capitale|| annee_debut_position_capitale <= annee_debut_capitale <= annee_fin_position_capitale || annee_debut_position_capitale <= annee_fin_capitale <= annee_fin_position_capitale))
			// 		{
			// 			continue;
			// 		}
			// 		min_annee_fin = Math.min(annee_fin_polygone, annee_fin_capitale, annee_fin_position_capitale);
			// 		max_annee_debut = Math.max(annee_debut_polygone, annee_debut_capitale, annee_debut_position_capitale);
			// 		if (min_annee_fin < max_annee_debut)
			// 		{
			// 			continue;
			// 		}
			// 		if (isMarkerInsidePolygon(caracs["latLng"][instance_capitale],figures.features[id_multipolygon].geometry.coordinates[polygon]))
			// 		{
			// 			has_capitale.push([max_annee_debut, min_annee_fin]);
			// 			console.log("capitale");
			// 			console.log(has_capitale);
			// 			break;
			// 		}
			// 	}
			// }
			var geojsonFeature = {
				"type": "Feature",
				"properties": {
					"id_element": id_pays,
					"id_multipolygon": id_multipolygon,
					"id_polygon": polygon,
					"annee_debut": figures.features[id_multipolygon].properties.annee_debut,
					"annee_fin": figures.features[id_multipolygon].properties.annee_fin,
					"couleur": figures.features[id_multipolygon].properties.couleur,
					"taille_polygone": figures.features[id_multipolygon].geometry.coordinates[polygon][0].length,
					"has_capitale": has_capitale
				},
				"geometry": {
					"type": "Point",
					"coordinates": centroid
				}
			};
			centroids.push(geojsonFeature);
		}
	}
	console.log("centroids");
	console.log(centroids);
}

function isMarkerInsidePolygon(marker, poly) {
    var inside = false;
    var x = marker[0], y = marker[1];
    for (var ii=0;ii<poly.length;ii++){
        var polyPoints = poly[ii];
        for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
            var xi = polyPoints[i][0], yi = polyPoints[i][1];
            var xj = polyPoints[j][0], yj = polyPoints[j][1];

            var intersect = ((yi > y) != (yj > y))
                && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }
    }

    return inside;
};

function determination_caracs_a_cette_date()
{
	Object.keys(caracs).forEach(function (key) {
		
		// on sauvegarde les caracs qu'on avait trouvé pour l'année précédente
		var copie_caracs_a_cette_date = JSON.parse(JSON.stringify(caracs_a_cette_date));
		
		// et on réinitialise le tableau pour la nouvelle année
		caracs_a_cette_date[key] = {};
		
		// on applique la procédure pour tous les éléments qui ont des caracs pour ce champ-là
		for (iden in caracs[key])
		{
			// mais on limite les calculs aux caractéristiques qu'on va figurer ou écrire
			if (key == "capitale" || key == "nomade" || key == "population" || key == "population_etat" || key == "nom" || iden == id_actif)
			{
				actualisation_carac = "";
				
				var max_so_far = min_annee;
				var id_so_far = "undefined";
				
				// pour limiter les calculs, on vérifie si les caractéristiques de la copie sauvegardée sont toujours adaptés
				if (typeof copie_caracs_a_cette_date[key] != "undefined" && typeof copie_caracs_a_cette_date[key][iden] != "undefined")
				{
					// on recupère l'id de la carac précédente...
					essai_id = copie_caracs_a_cette_date[key][iden][1];
					
					// si ce champ pour cet id avait une carac bien sûr
					if (essai_id != "undefined")
					{
						// si l'année de début et de fin de la carac antérieure sont plus petites que la nouvelle année
						// c'est que le bon id de carac s'il y en a un est plus loin
						if (key != "population" || key != "population_etat")
						{
							if (caracs[key][iden][essai_id][0] <= annee && annee <= caracs[key][iden][essai_id][1])
							{
								id_so_far = essai_id;
							}
						}
						// si l'année de début de la carac antérieure est plus petite que la nouvelle année et que 
						// cette carac est la dernière du tableau ou que la carac d'après commence après la nouvelle année
						// 
						else
						{
							if ((caracs[key][iden][essai_id][0] <= annee && (essai_id+1) == caracs[key][iden].length) || (caracs[key][iden][essai_id][0] <= annee && caracs[key][iden][essai_id+1][0] > annee))
							{
								id_so_far = essai_id;
							}
						}
					}
				}

				// si à ce stade on a toujours aucune piste pour situer la bonne carac dans le tableau
				if (id_so_far == "undefined")
				{
					// on teste toutes les caracs valides pour ce champ et cet id
					for (it in caracs[key][iden])
					{
						// si une carac commence avant la nouvelle année et que ce début est au-delà du maximum checké jusqu'à présent
						// alors cette carac a de bonnes chances d'être celles que l'on cherche, on peut la sauvegarder comme meilleure candidate
						if (caracs[key][iden][it][0] <= annee && caracs[key][iden][it][0] >= max_so_far)
						{
							max_so_far = caracs[key][iden][it][0];
							id_so_far = it;
						}
					}
				}
				
				// si on a toujours pas de piste, c'est qu'il n'y a pas de valeur pour ce champ et cet id cette année là
				// sinon...
				if (id_so_far != "undefined")
				{
					id_so_far = parseInt(id_so_far);
					
					// si on a affaire à une population
					if (key == "population" || key == "population_etat") {
						// si le candidat n'est pas le dernier du tableau...
						
						if (caracs[key][iden][id_so_far][2] == -1)
						{
							if (caracs[key][iden][id_so_far][1] >= annee)
							{
								actualisation_carac = "inconnu";
							}
						}
						else if ((id_so_far+1) < caracs[key][iden].length)
						{
							// on peut extraire la prochaine population et son année de début pour le figuré
							prochaine_pop = caracs[key][iden][id_so_far+1][2];
							derniere_pop = caracs[key][iden][id_so_far][2];
							prochaine_pop_debut = caracs[key][iden][id_so_far+1][0];
							derniere_pop_fin = caracs[key][iden][id_so_far][0];
							
							if (caracs[key][iden][id_so_far+1][2] == -1)
							{
								// afin de faire le figuré de la population pour l'année qui nous occupe
								actualisation_carac = caracs[key][iden][id_so_far][2];
							} else {
								// afin de faire le figuré de la population pour l'année qui nous occupe
								actualisation_carac = Math.round((prochaine_pop - derniere_pop) / (prochaine_pop_debut - derniere_pop_fin) * (annee - derniere_pop_fin) + derniere_pop);
							}
							
						}
						
						else
						{
							// sinon on prend juste la population sans l'ajuster
							actualisation_carac = caracs[key][iden][id_so_far][2];
						}
					}
					
					// et si on n'a pas affaire à une population et que la carac est valide au moins jusqu'à la nouvelle année
					// on la valide comme nouvelle carac
					else if (caracs[key][iden][id_so_far][1] >= annee) {
						actualisation_carac = caracs[key][iden][id_so_far][2];
					}
				}
				
				caracs_a_cette_date[key][iden] = [actualisation_carac, id_so_far];
			}
		}
	});
	
}

function affichage_figures()
{
	var map_bounds = map.getBounds();
	
	// pour ne garder que les villes les plus grandes de la région concernée
	var populations_villes_triees = [];
	var iden_populations_villes_triees = [];
	tri_populations_villes(populations_villes_triees, iden_populations_villes_triees, map_bounds);

	// détermination du max à cette année
	var max_pop_local_ville;
	if (populations_villes_triees.length != 0){
		max_pop_local_ville = populations_villes_triees[0][1];
	}

	// pour ne garder que les pays aux populations les plus grandes de la région concernée
	var populations_pays_triees = [];
	tri_populations_pays(populations_pays_triees, map_bounds);

	// détermination du max à cette année
	var max_pop_local_pays;
	if (populations_pays_triees.length != 0){
		max_pop_local_pays = populations_pays_triees[0][1];
	}

	affichage_pays();

	// Access the checkbox element
	var checkbox = document.getElementById('my-toggle');

	// Check if the checkbox is checked
	var is_checked = checkbox.checked;

	// Log the checked status
	console.log(is_checked);
	if (is_checked){
		affichage_populations_pays(populations_pays_triees, max_pop_local_pays);
		geoJSONlayer_villes = new L.geoJSON();
		map.addLayer(geoJSONlayer_villes);
	}
	else{
		affichage_villes(iden_populations_villes_triees, max_pop_local_ville);
		geoJSONlayer_population_pays = new L.geoJSON();
		map.addLayer(geoJSONlayer_population_pays);
	}
}

function tri_populations_villes(populations_villes_triees, iden_populations_villes_triees, map_bounds){
	for (var iden in caracs["latLng"]) 
	{
		for (var instance_ville in caracs["latLng"][iden])
		{
			// Si l'année est comprise dans la période de validité de la carac et que la ville est dans la région affichée
			if (caracs["latLng"][iden][instance_ville][0] <= annee && annee <= caracs["latLng"][iden][instance_ville][1] && L.latLngBounds(map_bounds).contains([caracs["latLng"][iden][instance_ville][2][1], caracs["latLng"][iden][instance_ville][2][0]]))
			{
				var population_id = [0,0];
				if (iden in caracs_a_cette_date["population"])
				{
					population_id = caracs_a_cette_date["population"][iden];
				}
				
				if (typeof(population_id) != "undefined" && population_id[0] != "" && population_id[0] != "inconnu")
				{
					populations_villes_triees.push([iden, population_id[0]]);
				}
				
				else
				{
					populations_villes_triees.push([iden, 0]);
				}
			}
		}
	}

	populations_villes_triees.sort(function(a, b) {
		return b[1] - a[1];
	});
	
	for (var id in populations_villes_triees)
	{
		id_pop = parseInt(populations_villes_triees[id][0]);
		valeur = populations_villes_triees[id][1];
		
		iden_populations_villes_triees.push(id_pop);
	}
}

function tri_populations_pays(populations_pays_triees, map_bounds){
	for (var id_multipolygon in figures.features) 
	{
		// Si l'année n'est pas comprise dans la période de validité de la carac, on passe au multipolygon suivant
		if(figures.features[id_multipolygon].properties.annee_debut > annee || annee > figures.features[id_multipolygon].properties.annee_fin)
		{
			continue;
		}
		// Si le multipolygon est une ville, on passe au multipolygon suivant
		if (figures.features[id_multipolygon].properties.type_element == "ville")
		{
			continue;
		}
		for (var polygon in figures.features[id_multipolygon].geometry.coordinates)
		{
			// Si le multipolygon n'a pas de coordonnées, on passe au polygon suivant
			if ((figures.features[id_multipolygon].geometry.coordinates[polygon][0]) == "undefined")
			{
				continue;
			}
			var centroid = centroids.find(x => (x.properties.id_multipolygon == id_multipolygon && x.properties.id_polygon == polygon)).geometry.coordinates;
			if (!L.latLngBounds(map_bounds).contains([centroid[1], centroid[0]]))
			{
				continue;
			}
			var id_pays = figures.features[id_multipolygon].properties.id_element;
			var population_id = [0,0];
			if (id_pays in caracs_a_cette_date["population_etat"])
			{
				population_id = caracs_a_cette_date["population_etat"][id_pays];
			}
			if(id_pays == 789){
				console.log("population_id");
				console.log(population_id);
				console.log("polygon");
				console.log(figures.features[iden]);
				console.log("centroid");
				console.log(centroid);
			}
			if(typeof(populations_pays_triees.find(x => x[0] == id_pays)) == "undefined"){
				if (typeof(population_id) != "undefined" && population_id[0] != "" && population_id[0] != "inconnu")
				{
					populations_pays_triees.push([id_pays, population_id[0], figures.features[id_multipolygon].geometry.coordinates[polygon][0].length]);
				}
				else
				{
					populations_pays_triees.push([id_pays, 0, figures.features[id_multipolygon].geometry.coordinates[polygon][0].length]);
				}
			}
			else{
				population_pays = populations_pays_triees.find(x => x[0] == id_pays);
				if(figures.features[id_multipolygon].geometry.coordinates[polygon][0].length > population_pays[2]){
					population_pays[2] = figures.features[id_multipolygon].geometry.coordinates[polygon][0].length;
				}
			}
		}
	}

	populations_pays_triees.sort(function(a, b) {
		return b[1] - a[1];
	});
}

function affichage_pays(){
	geoJSONlayer_pays = new L.geoJSON(figures, {
		smoothFactor: 0,
		style: 
			function(feature) {
				var nomade_id = caracs_a_cette_date["nomade"][feature.properties.id_element];
				
				if (typeof(nomade_id) == "undefined" || nomade_id[0] == false) {
					if (id_actif == feature.properties.id_element) {
						return { color: '#eb2ca8', weight: 2 };
					}
					else {
						return { color: feature.properties.couleur };
					}
				}
				else {
					if (id_actif == feature.properties.id_element) {
						return { fillPattern: new L.StripePattern({
									color: '#eb2ca8',
									opacity: 1.0,
									angle: -45
								}).addTo(map), color: '#eb2ca8', weight: 2 };
					}
					else {
						return { fillPattern: new L.StripePattern({
									color: feature.properties.couleur,
									opacity: 1.0,
									angle: -45
								}).addTo(map), color: feature.properties.couleur };
					}
				}
			},
		onEachFeature: function(feature, layer) {
			
			layer.addEventListener('click', function(e) {
				if 
				(id_actif == feature.properties.id_element) {
					id_actif = "";
				}
				else {
					id_actif = feature.properties.id_element;
				}
			
				type_actif = feature.properties.type_element;
				
				map.removeLayer(geoJSONlayer_pays);
				map.removeLayer(geoJSONlayer_villes);
				map.removeLayer(geoJSONlayer_population_pays);
				
				determination_caracs_a_cette_date();
				affichage_figures();
				
				if(id_actif == "") { 
					$("#conteneur_droite_caracs").html("");
				}
				else {
					actualisation_conteneur_droite();
				}
			});
			
			var nom_id;
			if (caracs_a_cette_date["nom"][feature.properties.id_element] != undefined)
			{
				nom_id = caracs_a_cette_date["nom"][feature.properties.id_element][0];
			}
			if (nom_id != undefined && nom_id != "") {
				layer.bindTooltip(nom_id);
			}

			},
		filter:
			function(feature) {
				return (feature.properties.annee_debut <= annee) && (annee <= feature.properties.annee_fin) && (feature.properties.type_element == "pays");
			},
		weight: 1,
		fillOpacity: 0.5
	});
	geoJSONlayer_pays.addTo(map);
}

function affichage_villes(iden_populations_villes_triees, max_pop_local_ville){
	geoJSONlayer_villes = L.geoJSON(figures, {
		smoothFactor: 0,
		style: 
			function(feature) {
				return {color: feature.properties.couleur};
			},
		onEachFeature: function(feature, layer) {
			
			layer.addEventListener('click', function(e) {
				if (id_actif == feature.properties.id_element) {
					id_actif = "";
				}
				else {
					id_actif = feature.properties.id_element;
				}
			
				type_actif = feature.properties.type_element;
				
				map.removeLayer(geoJSONlayer_pays);
				map.removeLayer(geoJSONlayer_villes);
				map.removeLayer(geoJSONlayer_population_pays);
				
				determination_caracs_a_cette_date();
				affichage_figures();
				
				if(id_actif == "") { 
					$("#conteneur_droite_caracs").html("");
				}
				else {
					actualisation_conteneur_droite();
				}
			});
			
			},
		pointToLayer: function(feature, latLng) {
			
			var population_id = caracs_a_cette_date["population"][feature.properties.id_element];
			var capitale_id = caracs_a_cette_date["capitale"][feature.properties.id_element];
			var nom_id;
			if (caracs_a_cette_date["nom"][feature.properties.id_element] != undefined)
			{
				nom_id = caracs_a_cette_date["nom"][feature.properties.id_element][0];
			}
			
			var geojsonMarkerOptions = {};
			
			// if there is no population defined for this element we put a grey circle without any weight and with a star if it's a capital
			if (typeof(population_id) == "undefined" || population_id[0] == "" || population_id[0] == "inconnu")
			{	
				geojsonMarkerOptions.weight = 0;
				geojsonMarkerOptions.fillOpacity = 1;
				
				if (typeof(capitale_id) != "undefined" && capitale_id[0]) {
					geojsonMarkerOptions.pane = "capitalPane";
					geojsonMarkerOptions.radius = 5;
					
					var capitalPattern = new L.Pattern({ patternUnits: "objectBoundingBox", patternContentUnits: "objectBoundingBox", width: 1, height: 1 });
					
					if (id_actif == feature.properties.id_element) { 
						capitalPattern.addShape(starSelected); 
					}
					else {
						capitalPattern.addShape(starNoPopulation);
					}
					
					capitalPattern.addTo(map);
					
					geojsonMarkerOptions.fillPattern = capitalPattern;
				}
				else if (id_actif == feature.properties.id_element) {
					geojsonMarkerOptions.fillColor = "#f416d7";
					geojsonMarkerOptions.radius = 3;
					
				}
				else {
					geojsonMarkerOptions.fillColor = "#A0A0A0";
					geojsonMarkerOptions.radius = 3;
				}
			}
			else
			{
				geojsonMarkerOptions.radius = Math.max(15*Math.sqrt(population_id[0]/max_pop_local_ville), 3);
				geojsonMarkerOptions.weight = 1;
				geojsonMarkerOptions.fillOpacity = 1;
				
				if (typeof(capitale_id) != "undefined"  && capitale_id[0]) {
					geojsonMarkerOptions.pane = "capitalPane";
					
					var capitalPattern = new L.Pattern({ patternUnits: "objectBoundingBox", patternContentUnits: "objectBoundingBox", width: 1, height: 1 });
					
					if (id_actif == feature.properties.id_element) { 
						capitalPattern.addShape(starSelected); 
					}
					else {
						capitalPattern.addShape(star);
					}
					
					capitalPattern.addTo(map);
					
					geojsonMarkerOptions.fillPattern = capitalPattern;
				}
				else if (id_actif == feature.properties.id_element) {
					geojsonMarkerOptions.fillColor = "#f416d7";
				}
				else {
					geojsonMarkerOptions.fillColor = "#A0A0A0";
					geojsonMarkerOptions.fillOpacity = 0.1;
				}
			}
				
			if (nom_id == undefined || nom_id == "") {
				return L.circleMarker(latLng, geojsonMarkerOptions);
			}
			else {
				return L.circleMarker(latLng, geojsonMarkerOptions).bindTooltip(nom_id);
			}
		},
		filter:
			function(feature) {
				if ((feature.properties.annee_debut <= annee) && (annee <= feature.properties.annee_fin) && (feature.properties.type_element == "ville"))
				{
					id = iden_populations_villes_triees.indexOf(feature.properties.id_element);
					if (id != -1 && id < max_villes_simultanees)
					{
						return true;
					}
				}
			
				return false;
			},
		weight: 1,
		fillOpacity: 0.6
	});
	geoJSONlayer_villes.addTo(map);
}

function affichage_populations_pays(populations_pays_triees, max_pop_local_pays){
	geoJSONlayer_population_pays = L.geoJSON(centroids, {
		smoothFactor: 0,
		style: 
			function(feature) {
				var population_id = caracs_a_cette_date["population_etat"][feature.properties.id_element];
				var styles;
				if (id_actif == feature.properties.id_element) {
						return { color: "black", fillColor: '#eb2ca8', weight: 1 };
					}
					else {
						return { color: "black", fillColor: feature.properties.couleur, weight: 1 };
					}
			},
		onEachFeature: function(feature, layer) {
			layer.addEventListener('click', function(e) {
				if (id_actif == feature.properties.id_element) {
					id_actif = "";
				}
				else {
					id_actif = feature.properties.id_element;
				}
			
				type_actif = "pays";
				
				map.removeLayer(geoJSONlayer_pays);
				map.removeLayer(geoJSONlayer_villes);
				map.removeLayer(geoJSONlayer_population_pays);
				
				determination_caracs_a_cette_date();
				affichage_figures();
				
				if(id_actif == "") { 
					$("#conteneur_droite_caracs").html("");
				}
				else {
					actualisation_conteneur_droite();
				}
			});
			
			var nom_id;
			if (caracs_a_cette_date["nom"][feature.properties.id_element] != undefined)
			{
				nom_id = caracs_a_cette_date["nom"][feature.properties.id_element][0];
			}
			if (nom_id != undefined && nom_id != "") {
				layer.bindTooltip(nom_id);
			}
		},
		pointToLayer: function(feature, latLng) {
			var population_id = caracs_a_cette_date["population_etat"][feature.properties.id_element];
			var geojsonMarkerOptions = {};
			
			if (typeof(population_id) == "undefined" || population_id[0] == "" || population_id[0] == "inconnu") {
				geojsonMarkerOptions.radius = 0;
				geojsonMarkerOptions.fillOpacity = 0;
			}
			else {
				geojsonMarkerOptions.radius = Math.max(15*Math.sqrt(population_id[0]/max_pop_local_pays), 3);
				geojsonMarkerOptions.weight = 1;
				geojsonMarkerOptions.fillOpacity = 1;
			}
			
			if (id_actif == feature.properties.id_element) {
				geojsonMarkerOptions.fillColor = "#f416d7";
			}
			else {
				geojsonMarkerOptions.fillColor = feature.properties.couleur;
			}
			var nom_id;
			if (caracs_a_cette_date["nom"][feature.properties.id_element] != undefined)
			{
				nom_id = caracs_a_cette_date["nom"][feature.properties.id_element][0];
			}
			if (nom_id == undefined || nom_id == "") {
				return L.circleMarker(latLng, geojsonMarkerOptions);
			}
			else {
				return L.circleMarker(latLng, geojsonMarkerOptions).bindTooltip(nom_id);
			}
		},
		filter:
			function(feature) {
				var population_id = caracs_a_cette_date["population_etat"][feature.properties.id_element];
				if (typeof(population_id) == "undefined" || population_id[0] == "" || population_id[0] == "inconnu" || population_id[0] == 0)
				{
					return false;
				}
				if (!((feature.properties.annee_debut <= annee) && (annee <= feature.properties.annee_fin)))
				{
					return false;
				}
				if (typeof(populations_pays_triees.find(x => x[0] == feature.properties.id_element)) == "undefined"){
					return false;
				}
				if (populations_pays_triees.find(x => x[0] == feature.properties.id_element)[2] <= feature.properties.taille_polygone)
				{
					if (feature.properties.id_element == 789){
						console.log("feature");
						console.log(feature);
					}	
					return true;
				}
				return false;
			},
		weight: 1,
		fillOpacity: 1
	});
	geoJSONlayer_population_pays.addTo(map);
}

function get_polygon_centroid(pts) {
	// make a copy of ths pts array that doesn't modify pts when modified
	var ptsCopy = JSON.parse(JSON.stringify(pts));
	var first = ptsCopy[0], last = ptsCopy[ptsCopy.length-1];
	if (first[0] != last[0] || first[1] != last[1]) {
		ptsCopy.push(first);
	}
	var twicearea=0,
	x=0, y=0,
	nPts = ptsCopy.length,
	p1, p2, f;
	for ( var i=0, j=nPts-1 ; i<nPts ; j=i++ ) {
		p1 = ptsCopy[i]; p2 = ptsCopy[j];
		f = p1[0]*p2[1] - p2[0]*p1[1];
		twicearea += f;
		x += ( p1[0] + p2[0] ) * f;
		y += ( p1[1] + p2[1] ) * f;
	}
	f = twicearea * 3;
	if(f == 0) {
		return [first[0],first[1]];
	}
	return [x/f, y/f];
}

function actualisation_conteneur_droite()
{
	nom = "";
	if (typeof(caracs_a_cette_date["nom"][id_actif]) != "undefined")
	{
		nom = caracs_a_cette_date["nom"][id_actif][0];
	}
	
	wikipedia = "";
	if (typeof(caracs_a_cette_date["wikipedia"][id_actif]) != "undefined")
	{
		wikipedia = caracs_a_cette_date["wikipedia"][id_actif][0];
	}
	
	population = "";
	if (typeof(caracs_a_cette_date["population"][id_actif]) != "undefined")
	{
		population = caracs_a_cette_date["population"][id_actif][0];
	}
	else if (typeof(caracs_a_cette_date["population_etat"][id_actif]) != "undefined")
	{
		population = caracs_a_cette_date["population_etat"][id_actif][0];
	}
	
	source = "";
	if (typeof(caracs_a_cette_date["source"][id_actif]) != "undefined")
	{
		source = caracs_a_cette_date["source"][id_actif][0];
	}
	
	chaine = "";
	if (wikipedia != "")
	{
		chaine += "<label style=\"font-size: 18px; margin-bottom: 8px;\"><b>" + nom + "</b><sup><a href='" + wikipedia + "'>wiki</a></sup></label>";
	}
	else
	{
		chaine += "<label style=\"font-size: 18px; margin-bottom: 8px;\"><b>" + nom + "</b></label>";
	}
	
	chaine += "<div><table class=\"table\">";
	chaine += "<tr><td><b>Population</b></td><td>" + population + "</td></tr></table>";
	chaine += "<label style='text-align:left; margin-top: 0px;'><b>Sources</b><label style='font-size: 10px; margin-top: 6px;'>" + source + "</label></label></div>";
	chaine += "<form id=\"formulaire_element\" action=\"creation/page_creation_" + type_actif + ".php\" method=\"post\"><input type=\"hidden\" id=\"id_element\" name=\"id_element\" value=\"" + id_actif + "\"><input type=\"submit\" value=\"Modifier\"></form>";
	
	$("#conteneur_droite_caracs").html(chaine);
}

function play_geojson()
{
	if ($("#bouton_player").val() == "play")
	{
		$("#bouton_player").val("pause");
		$("#bouton_player").html("Pause");
		
		var vitesse_play = document.getElementById("slider_play").value;
		var intervalle_play = 1;
		
		// si trop d'années par seconde ça rame solution pas afficher toutes années à chaque seconde
		if (vitesse_play > 10)
		{
			intervalle_play = ~~(vitesse_play/10);
			vitesse_play = 10;
		}
		
		myPlayer = setInterval(myTimer, 1000/vitesse_play, intervalle_play);
	}
	else
	{
		$("#bouton_player").val("play");
		$("#bouton_player").html("Play");

		clearInterval(myPlayer);
	}
}

function myTimer(intervalle_play) 
{
	var nouvelle_annee = parseInt($( "#slider_date_annee" ).val())+intervalle_play;
	
	if (nouvelle_annee > max_annee)
	{
		$("#bouton_player").val("play");
		$("#bouton_player").html("Play");

		clearInterval(myPlayer);
	}
	
	else
	{
		$( "#slider_range" ).slider('value', nouvelle_annee);
		$( "#slider_date_annee" ).val(nouvelle_annee);
		
		map.removeLayer(geoJSONlayer_pays);
		map.removeLayer(geoJSONlayer_villes);
		map.removeLayer(geoJSONlayer_population_pays);
		
		annee = nouvelle_annee;
		determination_caracs_a_cette_date();
		affichage_figures();
		
		if (id_actif != "") { actualisation_conteneur_droite(); };
	}
}
	
</script>

</body>

</html>