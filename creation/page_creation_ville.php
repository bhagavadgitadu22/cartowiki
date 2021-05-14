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
	<link rel="stylesheet" href="../css/leaflet-sidebar.css" />
	<script src="../js/leaflet-sidebar.js"></script>
	
	<!-- JQuery -->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" type="text/css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

	<!-- module de dessin -->
	<link rel="stylesheet" href="../css/leaflet.pm.css" type="text/css">
	<script src="../js/leaflet.pm.js"></script>
	
	<script src="../js/Leaflet.ImageOverlay.Rotated.js"></script>
	
	<link rel="stylesheet" href="../css/style.css" />
</head>

<body>

	<div id="conteneur_gauche" class="leaflet-sidebar collapsed">
	
        <div class="leaflet-sidebar-tabs">
            <!-- top aligned tabs -->
            <ul role="tablist">
				<li><a href="#image" role="tab"><i class="fa fa-picture-o active" style="line-height: inherit;"></i></a></li>
				<li><a href="#arbre" role="tab"><i class="fa fa-tree" style="line-height: inherit;"></i></a></li>
            </ul>
        </div>

        <div class="leaflet-sidebar-content">	
		
            <div class="leaflet-sidebar-pane" id="image">
			
                <h1 class="leaflet-sidebar-header">
                    Image
                    <span class="leaflet-sidebar-close"><i class="fa fa-caret-left" style="line-height: inherit;"></i></span>
                </h1>
				
				<br/>
				<div class="form-style-5">
					<label style="margin-bottom: 10px">Lien URL de l'image :</label><input type="text" id="loadImageHelp"></input>
				</div>
				
			</div>
			
			
			<div class="leaflet-sidebar-pane" id="arbre">
			
                <h1 class="leaflet-sidebar-header">
                    Modifications
                    <span class="leaflet-sidebar-close"><i class="fa fa-caret-left" style="line-height: inherit;"></i></span>
                </h1>
				
				<div id="titre_overflow" class="form-style-5">
					<p id="overflow" style="overflow: auto; text-align: left;">
						<button type="button" id="bouton_latLng" onclick="bouton_affichage_annees(this.id, 'latLng')";>+</button> <b>Formes</b>
						<label id="latLng" class="liste_modifications"></label>
						
						<button type="button" id="bouton_nom" onclick="bouton_affichage_annees(this.id, 'nom')";>+</button> <b>Noms</b>
						<label id="nom" class="liste_modifications"></label>
						
						<button type="button" id="bouton_capitale" onclick="bouton_affichage_annees(this.id, 'capitale')";>+</button> <b>Capitales</b>
						<label id="capitale" class="liste_modifications"></label>
						
						<button type="button" id="bouton_population" onclick="bouton_affichage_annees(this.id, 'population')";>+</button> <b>Populations</b>
						<label id="population" class="liste_modifications"></label>
						
						<button type="button" id="bouton_source" onclick="bouton_affichage_annees(this.id, 'source')";>+</button> <b>Sources</b>
						<label id="source" class="liste_modifications"></label>
					</p>
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
				
				<div id="conteneur_droite" class="form-style-5" style="margin-top: 20px">
					<label class="formulaire_caracs">Nom :</label>
						<input type="text" id="conteneur_droite_nom"></input>
					
					<label class="formulaire_caracs">Wikipédia :</label>
						<input type="text" id="conteneur_droite_wikipedia" style="font-size: 12px;"></input>
					
					<label style="margin-bottom: 18px;">Capitale : <input type="checkbox" id="conteneur_droite_capitale"></label>

					<label class="formulaire_caracs"><input type="checkbox" id="zero_info_pop" onclick="population_inc()"> Population :</label>
						<input type="text" id="conteneur_droite_population"></input>
					
					<label class="formulaire_caracs">Sources :</label>
						<textarea class="form-control" rows="1" id="conteneur_droite_source" style="font-size: 12px;"></textarea>
					
					<button type="button" onclick="envoi_forme()">Valider</button>
				</div>
			</div>
		</div>
	</div>	

		
	<div id="slider_range"></div>
	
	<div id="slider_date"><input type="number" id="slider_date_annee" min="-3000" max="2020" value="0"></input></div>
	
<script type='text/javascript'>
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

var id_element = <?php if(isset($_POST['id_element'])) { echo "'" . $_POST['id_element'] . "'"; } else { echo "'" . "creation" . "'"; } ?>;
var type_element = "ville";

// define toolbar options
var options = {
	position: 'topright', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
	drawMarker: true, // adds button to draw markers
	drawPolyline: false, // adds button to draw a polyline
	drawRectangle: false, // adds button to draw a rectangle
	drawPolygon: false, // adds button to draw a polygon
	drawCircle: false, // adds button to draw a cricle
	cutPolygon: false, // adds button to cut a hole in a polygon
	editMode: true, // adds button to toggle edit mode for all layers
	removalMode: true, // adds a button to remove layers
};

var figures;
var id_max;
var ecoute_remove = true;

var caracs = {};

var anciennes_caracs = {};

var annee;

var min_annee = -3000;
var max_annee = 2020;

var overlay;
var marker1;
var marker2;
var marker3;

var blackIcon = L.icon({
    iconUrl: '../images_css/marker-icon-noir.png',
	
    iconSize:     [20, 20], // size of the icon
    iconAnchor:   [10, 10], // point of the icon which will correspond to marker's location
});


// initialisation

var map = L.map('map', {
	attributionControl: false
}).setView([46.988332, 2.605527], 5);

L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/terrain-background/{z}/{x}/{y}{r}.{ext}', {
	noWrap: true,
	attribution: '',
	subdomains: 'abcd',
	minZoom: 1,
	maxZoom: 18,
	ext: 'png'
}).addTo(map);


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


map.doubleClickZoom.disable();

// add leaflet.pm controls to the map
map.pm.addControls(options);


// autres boutons

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


// le slider temporel
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
		
		$(sliderContainer).append('<div id="slider_range" style="float:left; width:88%;"></div><div id="slider_date" style="float: right; width: 120px; padding-left:1%;"><input type="number" id="slider_date_annee" min="-3000" max="2020" value="0" style="font-family: Georgia; color: black; font-size:30px; padding-top: 5px; padding-bottom: 7px; padding-left: 11px; padding-right: 7px;"></input></div>');
		
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
		if (securite_ok())
		{
			$( "#slider_date_annee" ).val(ui.value);

			mise_a_jour_carte();
		}
		else
		{
			return false;
		}
	}
});

$( "#slider_date_annee" ).bind('change', function(){
	if (securite_ok())
	{
		$( "#slider_range" ).slider('value', $( "#slider_date_annee" ).val());
		
		mise_a_jour_carte();
	}
	else
	{
		$( "#slider_date_annee" ).val(annee);
	}
});

$.post('../dialogue_BDD_site/recuperation_formes_un_element.php', { id_element:id_element, type_element:type_element }, function(result) {
		result = result.split(";;;");

		figures = result[1];
		
		caracs = JSON.parse(result[2].replace(/\n/g, "\\n"));
		
		id_max = parseInt(result[3]);

		affichage_figures_from_scratch();
		actualisation_conteneur_droite();
	});



// listen to when a layer is modified

$("#loadImageHelp").on('change', function(e) {
	if (map.hasLayer(overlay)) { map.removeLayer(overlay); }
	if (map.hasLayer(marker1)) { map.removeLayer(marker1); }
	if (map.hasLayer(marker2)) { map.removeLayer(marker2); }
	if (map.hasLayer(marker3)) { map.removeLayer(marker3); }
	
	imageUrl = $("#loadImageHelp").val();
	
	if (imageUrl != "")
	{
		var bounds_map = map.getBounds();

		var ne = bounds_map._northEast,
			so = bounds_map._southWest;

		var point1 = L.latLng(5*ne.lat/6+so.lat/6, 5*so.lng/6+ne.lng/6),
			point2 = L.latLng(5*ne.lat/6+so.lat/6, 5*ne.lng/6+so.lng/6),
			point3 = L.latLng(ne.lat/6+5*so.lat/6, 5*so.lng/6+ne.lng/6);

		marker1 = L.marker(point1, {draggable: true, icon:blackIcon} ).addTo(map);
		marker2 = L.marker(point2, {draggable: true, icon:blackIcon} ).addTo(map);
		marker3 = L.marker(point3, {draggable: true, icon:blackIcon} ).addTo(map);
			
		var	bounds = new L.LatLngBounds(point1, point2).extend(point3);

		imageUrl = $("#loadImageHelp").val();
		
		overlay = L.imageOverlay.rotated(imageUrl, point1, point2, point3, {
			opacity: 0.4,
			interactive: true
		});

		marker1.on('drag dragend', repositionImage);
		marker2.on('drag dragend', repositionImage);
		marker3.on('drag dragend', repositionImage);

		map.addLayer(overlay);

		overlay.on('dblclick',function (e) {
			console.log('Double click on image.');
			e.stop();
		});

		overlay.on('click',function (e) {
			console.log('Click on image.');
		});
	}
	
});

function repositionImage() {
	overlay.reposition(marker1.getLatLng(), marker2.getLatLng(), marker3.getLatLng());
};

map.on('pm:create', function(e) {
	// voir si un marqueur est déjà présent à cette époque
	// si oui, le supprimer pour les périodes suivant l'année en cours
	
	figures = JSON.parse(figures);
	
	var fin_marqueur = max_annee;
	
	for (index in figures.features)
	{
		id_feature = figures.features[index].properties.id;
		
		if (figures.features[index].properties.statut != 3)
		{
			if (figures.features[index].properties.annee_debut <= annee && figures.features[index].properties.annee_fin >= annee)
			{
				if (figures.features[index].properties.annee_debut < annee)
				{
					if (figures.features[index].properties.statut != 2)
					{
						figures.features[index].properties.statut = 1;
					}
					
					figures.features[index].properties.annee_fin = annee-1;
					caracs["latLng"][id_feature][1] = annee-1;
				}
				else
				{
					if (figures.features[index].properties.statut != 2)
					{
						figures.features[index].properties.statut = 3;
					}
					else
					{
						figures.features.splice(index, 1);
					}
					delete caracs["latLng"][id_feature];
				}
			}
			else if (figures.features[index].properties.annee_debut > annee && figures.features[index].properties.annee_debut < fin_marqueur)
			{
				fin_marqueur = figures.features[index].properties.annee_debut-1;
			}
		}
	}
	
	figures = JSON.stringify(figures);
	
	id_max += 1;
	
	var nouvelle_forme = e.layer.toGeoJSON();
	
	L.extend(nouvelle_forme.properties, {
		statut: 2,
		id_bdd: '',
		id: id_max,
		annee_debut: annee,
		annee_fin: fin_marqueur
	});
	
	figures = JSON.parse(figures);
	figures.features.push(nouvelle_forme);
	figures = JSON.stringify(figures);
	
	caracs["latLng"][id_max] = [annee, fin_marqueur];
	if ($("#bouton_latLng").text() == "-")
	{
		affichage_annees("latLng");
	}

	ecoute_remove = false;
	map.removeLayer(e.layer);
	ecoute_remove = true;
	
	affichage_figures();
});

map.on('pm:remove', function(e) {
	
	if (ecoute_remove)
	{
		var modif = e.layer.toGeoJSON();
		var id_modifie = modif.properties.id;
		
		figures = JSON.parse(figures)
		
		for (index in figures.features)
		{
			id_feature = figures.features[index].properties.id;
			
			if (figures.features[index].properties.id == id_modifie)
			{
				if (figures.features[index].properties.annee_debut < annee)
				{
					if (figures.features[index].properties.statut != 2)
					{
						figures.features[index].properties.statut = 1;
					}
					figures.features[index].properties.annee_fin = annee-1;
					caracs["latLng"][id_feature][1] = annee-1;
				}
				else
				{
					if (figures.features[index].properties.statut != 2)
					{
						figures.features[index].properties.statut = 3;
					}
					else
					{
						figures.features.splice(index, 1);
					}
					delete caracs["latLng"][id_feature];
				}
			}
		}
		
		if ($("#bouton_latLng").text() == "-")
		{
			affichage_annees("latLng");
		}
		
		figures = JSON.stringify(figures);
		
		affichage_figures();
	}
});



// fonction

function validateURL(s) {
   var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
   return regexp.test(s);
}

function securite_ok()
{
	if ($("#conteneur_droite_population").val() != "" && isNaN(parseInt($("#conteneur_droite_population").val())))
	{
		$("#conteneur_droite_population").css("background-color", "#B22222");
		$("#conteneur_droite_population").css("color", "#ffffff");
		
		return false;
	}
	else
	{
		if ($("#conteneur_droite_population").val() != "") { $("#conteneur_droite_population").val(parseInt($("#conteneur_droite_population").val())); }
		
		$("#conteneur_droite_population").css("background-color", "#e8edf0");
		$("#conteneur_droite_population").css("color", "#8a97a0");
	}
	

	if ($("#conteneur_droite_wikipedia").val() != "" && !validateURL($("#conteneur_droite_wikipedia").val()))
	{
		$("#conteneur_droite_wikipedia").css("background-color", "#B22222");
		$("#conteneur_droite_wikipedia").css("color", "#ffffff");
		
		return false;
	}
	else
	{
		$("#conteneur_droite_wikipedia").css("background-color", "#e8edf0");
		$("#conteneur_droite_wikipedia").css("color", "#8a97a0");
	}
	
	
	return true;
}

function mise_a_jour_carte()
{
	enregistrement_caracteristiques();
	affichage_figures();
	actualisation_conteneur_droite();
}

function affichage_annees(key)
{
	tab_annees = [];
	for (i in caracs[key])
	{
		if (caracs[key][i][3] != 3)
		{
			if (typeof(tab_annees.find(element => element[0] == caracs[key][i][0] && element[1] == caracs[key][i][1])) == "undefined")
			{
				tab_annees.push([caracs[key][i][0], caracs[key][i][1]]);
			}
		}
	}
	tab_annees.sort(function(a, b){return a[0]-b[0]});
	
	chaine = "";
	for (i = 0; i < tab_annees.length; i++)
	{
		chaine += "<p>De <b onclick=\"clic_annee("+tab_annees[i][0]+")\">" + tab_annees[i][0] + "</b> à <b onclick=\"clic_annee("+tab_annees[i][1]+")\">" + tab_annees[i][1] + "</b></p>";
	}
	
	$("#"+key).empty();
	$("#"+key).html(chaine);
}

function bouton_affichage_annees(bouton, param)
{
	if ($("#"+bouton).text() == "-")
	{
		$("#"+bouton).text("+");
		$("#"+bouton).css("padding", "4px 8px 4px 8px");
		
		$("#"+param).empty();
	}
	else
	{
		$("#"+bouton).text("-");
		$("#"+bouton).css("padding", "4px 10px 4px 10px");
		
		affichage_annees(param);
	}
}

function clic_annee(annee)
{
	if (securite_ok())
	{
		$( "#slider_date_annee" ).val(annee);
		$( "#slider_range" ).slider('value', $( "#slider_date_annee" ).val());

		mise_a_jour_carte();
	}
}

function reinitialisation_palette_dessin()
{
	var bouton_clique = false;
	
	for (i in map.pm.Toolbar.buttons)
	{
		var bouton = map.pm.Toolbar.buttons[i];
		
		if (bouton._leaflet_id && bouton._button.toggleStatus)
		{
			bouton._button.afterClick();
			bouton_clique = bouton;
		}
	}
	
	return bouton_clique;
}

function population_inc()
{
	if ($("#zero_info_pop").prop('checked'))
	{
		$("#conteneur_droite_population").prop('disabled', true);
		$("#conteneur_droite_population").val(-1);
		
		$("#conteneur_droite_population").css("background-color", "#616466");
		$("#conteneur_droite_population").css("color", "#8a97a0");
	}
	else
	{
		$("#conteneur_droite_population").prop('disabled', false);
		$("#conteneur_droite_population").val("");
		
		$("#conteneur_droite_population").css("background-color", "#e8edf0");
		$("#conteneur_droite_population").css("color", "#8a97a0");
	}
}

function enregistrement_caracteristiques()
{
	Object.keys(caracs).forEach(function (key) {
		
		if (key != "latLng")
		{
			var nouvelle_carac;
			if (key == "capitale")
			{
				nouvelle_carac = $("#conteneur_droite_capitale").prop('checked');
			}
			else
			{
				nouvelle_carac = $("#conteneur_droite_" + key).val();
			}
			
			// si le nom dans la boîte est différent du nom prévu
			if (nouvelle_carac != anciennes_caracs[key])
			{
				var fin_carac = max_annee;
					
				// on modifie l'ancien élément (en le supprimant si besoin en changeant le statut, en changeant les années de fin/début et le statut sinon)
				for (it in caracs[key])
				{
					if (caracs[key][it][0] <= annee && caracs[key][it][1] >= annee)
					{
						if (caracs[key][it][3] != 2)
						{
							// si modification dès la première année, c'est que l'élément est supprimé
							if (annee == caracs[key][it][0])
							{
								caracs[key][it][3] = 3;
							}
							// sinon c'est que l'élément est juste modifié
							else
							{
								caracs[key][it][1] = annee-1;
								caracs[key][it][3] = 1;
							}
						}
						else
						{
							// si modification dès la première année, c'est que l'élément est supprimé
							if (annee == caracs[key][it][0])
							{
								caracs[key].splice( it, 1 );
							}
							// sinon c'est que l'élément est juste modifié
							else
							{
								caracs[key][it][1] = annee-1;
							}
						}
					}
					else if (caracs[key][it][0] > annee && caracs[key][it][0] < fin_carac)
					{
						// le -1 est important
						// avant correction, il y avait un problème de juxtaposition avec carac ajoutée avant ancienne, ça s'arrêtait trop loin !
						fin_carac = caracs[key][it][0]-1;
					}
				}
				
				if (nouvelle_carac != "")
				{
					// on ajoute le nouvel élément dans noms_elements
					caracs[key].push([annee, fin_carac, nouvelle_carac, 2]);
				}
				
				//on met à jour arbre pour cette key
				if ($("#bouton_"+key).text() == "-")
				{
					affichage_annees(key);
				}
			}
		}
	});
}

function affichage_figures()
{	
	ecoute_remove = false;
	map.removeLayer(geoJSONlayer);
	ecoute_remove = true;
	
	affichage_figures_from_scratch();
}

function affichage_figures_from_scratch()
{
	var bouton_clique = reinitialisation_palette_dessin();
	
	annee = parseInt($( "#slider_date_annee" ).val());

	geoJSONlayer = new L.geoJSON(JSON.parse(figures), {
		filter:
			function(feature) {
				return (feature.properties.statut <= 2) && (feature.properties.annee_debut <= annee) && (annee <= feature.properties.annee_fin);
			}
	});
	
	geoJSONlayer.addTo(map);
	
	geoJSONlayer.on('pm:edit', function(e) {
		
		var modif = e.sourceTarget.toGeoJSON();
		
		var id_modifie = modif.properties.id;
		
		figures = JSON.parse(figures)
		
		for (index in figures.features)
		{
			id_feature = figures.features[index].properties.id;
			
			if (figures.features[index].properties.id == id_modifie)
			{
				if (figures.features[index].properties.annee_debut < annee)
				{
					if (figures.features[index].properties.statut != 2)
					{
						figures.features[index].properties.statut = 1;
					}
					figures.features[index].properties.annee_fin = annee-1;
					caracs["latLng"][id_feature][1] = annee-1;
				}
				else
				{
					if (figures.features[index].properties.statut != 2)
					{
						figures.features[index].properties.statut = 3;
					}
					else
					{
						figures.features.splice(index, 1);
					}
					delete caracs["latLng"][id_feature];
				}
				
				id_max += 1;
				modif.properties.statut = 2;
				modif.properties.id = id_max;
				modif.properties.annee_debut = annee;
				
				figures.features.push(modif);
				caracs["latLng"][id_max] = [annee, modif.properties.annee_fin];
			}
		}
		
		figures = JSON.stringify(figures);
		
		if ($("#bouton_latLng").text() == "-")
		{
			affichage_annees("latLng");
		}
		
		affichage_figures();
	});
	

	// on remet la palette de dessin telle qu'elle était avant le changement
	if (bouton_clique != false)
	{
		bouton_clique._button.afterClick();
	}
}


function actualisation_conteneur_droite()
{
	Object.keys(caracs).forEach(function (key) {
		
		anciennes_caracs[key] = "";	
		
		for (it in caracs[key])
		{
			if (caracs[key][it][0] <= annee && caracs[key][it][1] >= annee && caracs[key][it][3] != 3)
			{
				anciennes_caracs[key] = caracs[key][it][2];			
			}
		}
		
		if (key == "capitale")
		{
			$("#conteneur_droite_capitale").prop('checked', anciennes_caracs[key]);
		}
		else
		{
			$("#conteneur_droite_" + key).val(anciennes_caracs[key]);
		}
		
		
		if ($("#conteneur_droite_population").val() == -1)
		{
			$("#zero_info_pop").prop('checked', true);
			$("#conteneur_droite_population").prop('disabled', true);
			
			$("#conteneur_droite_population").css("background-color", "#616466");
			$("#conteneur_droite_population").css("color", "#8a97a0");
		}
		else
		{
			$("#zero_info_pop").prop('checked', false);
			$("#conteneur_droite_population").prop('disabled', false);
		}
		
	});
}


function envoi_forme()
{
	if (securite_ok())
	{
		enregistrement_caracteristiques();
		delete caracs["latLng"];
		
		figures = JSON.parse(figures);
		
		var bool = false;
		var lignes = [];
		for (i in figures.features)
		{
			if (figures.features[i].properties.statut != 0)
			{
				var shape = JSON.stringify(figures.features[i].geometry.coordinates);
				
				var annee_debut = figures.features[i].properties.annee_debut;
				var annee_fin = figures.features[i].properties.annee_fin;
				var statut = figures.features[i].properties.statut;
				var id_bdd = figures.features[i].properties.id_bdd;
				
				lignes.push({'shape': shape, 'annee_debut': annee_debut, 'annee_fin': annee_fin, 'statut': statut, 'id_bdd': id_bdd});
			}
			else
			{
				bool = true;
			}
		}
		
		// pour n'envoyer se faire changer que les caracs qui ont réellement été modifiés
		Object.keys(caracs).forEach(function (key) {
			a_supprimer = [];
			
			for (it in caracs[key])
			{
				if (caracs[key][it][3] == 0)
				{
					a_supprimer.push(it);
				}
			}	
			
			nombre_elements = a_supprimer.length;
			for (var i = 0; i < nombre_elements; i++) 
			{
				caracs[key].splice(a_supprimer[nombre_elements-1-i], 1);
			}
		});
		
		$.post("../dialogue_BDD_site/traitement_formes_un_element.php", { id_element: id_element, couleur_element: '#000000', type_element: type_element, lignes: lignes, caracs: caracs, bool:bool }, function(result) {
			window.location.href = "../index.php";
		});
	}
}

</script>

</body>

</html>