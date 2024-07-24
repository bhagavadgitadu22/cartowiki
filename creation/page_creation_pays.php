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
	
	<script src="https://unpkg.com/@turf/turf@5.1.6/turf.min.js"></script>

	<link rel="stylesheet" href="../css/style.css" />
</head>

<body>

	<div id="conteneur_gauche" class="leaflet-sidebar collapsed">
	
        <div class="leaflet-sidebar-tabs">
            <!-- top aligned tabs -->
            <ul role="tablist">
                <li><a href="#couleur" role="tab"><i class="fa fa-pencil" style="line-height: inherit;"></i></a></li>
				<li><a href="#image" role="tab"><i class="fa fa-picture-o active" style="line-height: inherit;"></i></a></li>
				<li><a href="#frontieres" role="tab"><i class="fa fa-book" style="line-height: inherit;"></i></a></li>
				<li><a href="#arbre" role="tab"><i class="fa fa-tree" style="line-height: inherit;"></i></a></li>
            </ul>
        </div>

        <div class="leaflet-sidebar-content">

            <div class="leaflet-sidebar-pane" id="couleur">
			
               <h1 class="leaflet-sidebar-header">
                    Couleur
                    <span class="leaflet-sidebar-close"><i class="fa fa-caret-left" style="line-height: inherit;"></i></span>
                </h1>
			
				<br/>
				<br/>
				<div class="form-style-5">
					<input type="color" id="html5colorpicker" name="html5colorpicker" onchange="clickColor(0, -1, -1, 5)" value="#000000"></input>
				</div>
				
			</div>
		
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
			
			
            <div class="leaflet-sidebar-pane" id="frontieres">
			
                <h1 class="leaflet-sidebar-header">
                    Import
                    <span class="leaflet-sidebar-close"><i class="fa fa-caret-left" style="line-height: inherit;"></i></span>
                </h1>
				
				<br/>
				<div class="form-style-5">
					<label style="margin-bottom: 10px">Nom du pays :</label>
					<input type="text" id="nom_pays_decalque">
				</div>
				
				<div class="form-style-5" style="text-align: center;">
					<label style="margin-bottom: 10px">Frontières en l'an :</label>
					<input type="number" id="input_decalque_annee" min="-3000" max="2022" value="0" style="width: 40%; display:inline-block;"></input>
					<input type="range" id="slider_decalque_annee" min="-3000" max="2022" value="0" class="slider_play"></input>
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
						
						<button type="button" id="bouton_nomade" onclick="bouton_affichage_annees(this.id, 'nomade')";>+</button> <b>Proto-états</b>
						<label id="nomade" class="liste_modifications"></label>
						
						<button type="button" id="bouton_population_etat" onclick="bouton_affichage_annees(this.id, 'population_etat')";>+</button> <b>Populations</b>
						<label id="population_etat" class="liste_modifications"></label>
						
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
				
				<div id="conteneur_droite" class='form-style-5' style="margin-top: 20px">
					<label class="formulaire_caracs">Nom :</label>
						<input type="text" id="conteneur_droite_nom"></input>
					
					<label class="formulaire_caracs">Wikipédia :</label>
						<input type="text" id="conteneur_droite_wikipedia" style="font-size: 12px;"></input>
						
					<label style="margin-bottom: 18px;">Proto-état : <input type="checkbox" id="conteneur_droite_nomade"></label>
					
					<label class="formulaire_caracs"><input type="checkbox" id="zero_info_pop" onclick="population_inc()"> Population :</label>
						<input type="text" id="conteneur_droite_population_etat"></input>
					
					<label class="formulaire_caracs">Sources :</label>
						<textarea class="form-control" rows="1" id="conteneur_droite_source" style="font-size: 12px;"></textarea>
					
					<button type="button" onclick="envoi_forme()">Valider</button>
				</div>
			</div>
			
		</div>
	</div>	
	
		
	<div id="slider_range"></div>
	
	<div id="slider_date"><input type="number" id="slider_date_annee" min="-3000" max="2022" value="0"></input></div>
	
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
var type_element = "pays";

// define toolbar options
var options = {
	position: 'topright', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
	drawMarker: false, // adds button to draw markers
	drawPolyline: false, // adds button to draw a polyline
	drawRectangle: false, // adds button to draw a rectangle
	drawPolygon: true, // adds button to draw a polygon
	drawCircle: false, // adds button to draw a cricle
	cutPolygon: true, // adds button to cut a hole in a polygon
	editMode: true, // adds button to toggle edit mode for all layers
	removalMode: true, // adds a button to remove layers
};

var couleur = '#000000';

var figures;
var id_max;
var autres_figures;

var autre_geoJSONlayer;
var geoJSONlayer;

var ecoute_remove = true;

var caracs = {};

var anciennes_caracs = {};

var annee;

var min_annee = -3000;
var max_annee = 2022;

// variables de copier-coller
var selection = [];
var copie = [];

// variables de undo-redo
var prev_actions = [];
var post_actions = [];

// pour limiter les réécritures dans la bdd
var id_changes = [];

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

// initialisation

var map = L.map('map', {
	attributionControl: false
}).setView([46.988332, 2.605527], 5);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
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


var autres_boutons = L.Control.extend({

	options: {
		position: 'topleft' 
		//control position - allowed: 'topleft', 'topright', 'bottomleft', 'bottomright'
	},
 
	onAdd: function (map) {
		var container = L.DomUtil.create('div', 'leaflet-bar');
		
		var copyButton = L.DomUtil.create('a', 'leaflet-buttons-control-button', container);
		
		var icone_copie = L.DomUtil.create('a', 'control-icon', copyButton);
		
		icone_copie.style.backgroundImage = "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAwCAYAAACFUvPfAAABNUlEQVRoge3ZsUoDQRRG4SNBLIwgaJ3Oh7CwEkSQYKlgCl9CECxEsE3na6Sx1MJOU9lZKNgJpogiiCIIWYsUhkuYuDOzMxH+A7eacOerNrALvy0AbeAJGABFglkkoHngNhE0GvokAzgYffcf0a9m2RnQSjCzIeg3g94NWZYqoVNVBl0H1oDNCmcDWImBngGOgA/8nxZl5wpohKCPE2JH55Hhv3Vp9BLwlQldAIc+6GZGcAFc+KD3zPkLsFzhtM19Nz7oljnvj1sSsVNzX3fcj4SOkNBCOxJaaEdCC+1IaKEdCS20I6GnCn0P9EZm25xPJXpSQv8hoYV2JLTQjqKg7VvTd2C/wjk39137oNfNktTT8UHPAc8Z0Ts+aIAt4DsDuMPwI5V3q8Al8FkxdAA8AAdAzSJ+ACxdDZ+V7Z/0AAAAAElFTkSuQmCC)";
		icone_copie.style.backgroundSize = "20px 20px";
		icone_copie.style.width = '30px';
		icone_copie.style.height = '30px';
		
		var pasteButton = L.DomUtil.create('a', 'leaflet-buttons-control-button', container);
	  
		var icone_paste = L.DomUtil.create('a', 'control-icon', pasteButton);
		
		icone_paste.style.backgroundImage = "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACcAAAAwCAYAAACScGMWAAABIUlEQVRYhe3ZPUoDQRgG4Ae0jzZ6ENELaGkpaCHGuwhCiOIPEUU7tRVbTxF/EE8gUfAEEk0sTCAuq1nJmg34ffA2A/POw5QzDD7zuMAz3vGCy856YTOOE7R/yDHGisDV+sC62R82bBatjLgWZgY9cA4VnOK8T+5SAAco4ygFfpuh8wxbHceXqWa8he+yl+g7HLCv2i1aG7CojeUEbjWHzjLUcyiqJHA7OXTW4TWxeI2rntxkKGpiA4vYxFuGPf3OaUrZtJS4hZUcbiEtWc4JXOAC9xvcNBb+IFN54IY1gQtc4AIXuMAFLnCBC1zgAhe4wAXu3+JqWC8gyRf4FjRSgKOQR9gdAUhatmECDyOA6c09Sjoz6fMD7algVMPnH0YJPgDSfhW71THSTwAAAABJRU5ErkJggg==)";
		icone_paste.style.backgroundSize = "17px 20px";
		icone_paste.style.width = '30px';
		icone_paste.style.height = '30px';
		
		copyButton.addEventListener('click', function(e) {
			
			if (selection.length != 0)
			{
				copie = [];
				
				for (i in selection)
				{
					copie.push(selection[i]);
				}
			}
		});
		
		pasteButton.addEventListener('click', function(e) {
			if (copie.length != 0)
			{	
				var prev = {annee : annee, changement : []};
				
				for (i in copie)
				{
					forme_copiee = copie[i].toGeoJSON();
					nouvelle_forme = JSON.parse(JSON.stringify(forme_copiee));
					
					id_max += 1;
					
					L.extend(nouvelle_forme.properties, {
						statut: 2,
						id_bdd: '',
						id: id_max,
						annee_debut: annee,
						annee_fin: max_annee
					});
					try {
						figures = JSON.parse(figures);
					}
					catch (error) {
						console.log("Figures était deja parse, pas besoin de le refaire");
					}
					figures.features.push(nouvelle_forme);
					figures = JSON.stringify(figures); //IMPORTANT : Sans ça les fonctions leaflet de modification de figure créent de nouvelles formes quand on modifie une forme existante
					
					prev.changement.push({id : id_max, prev : ""});
					
					caracs["latLng"][id_max] = [annee, max_annee];
				}
				
				copie = [];
				
				prev_actions.push(prev);
				
				post_actions = [];
				
				if ($("#bouton_latLng").text() == "-")
				{
					affichage_annees("latLng");
				}
				
				affichage_figures();
			}
		});

		var undoButton = L.DomUtil.create('a', 'leaflet-buttons-control-button', container);
		
		var icone_undo = L.DomUtil.create('a', 'control-icon', undoButton);
		
		icone_undo.style.backgroundImage = "url(data:image/png;base64,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)";
		icone_undo.style.backgroundSize = "23px 23px";
		icone_undo.style.width = '30px';
		icone_undo.style.height = '30px';
		
		var redoButton = L.DomUtil.create('a', 'leaflet-buttons-control-button', container);
	  
		var icone_redo = L.DomUtil.create('a', 'control-icon', redoButton);
		
		icone_redo.style.backgroundImage = "url(data:image/png;base64,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)";
		icone_redo.style.backgroundSize = "23px 23px";
		icone_redo.style.width = '30px';
		icone_redo.style.height = '30px';
		
		undoButton.addEventListener('click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			
			if (prev_actions.length != 0)
			{
				var elmt = prev_actions.pop();
				
				var post = {annee : annee, changement : []};
				
				var an = elmt.annee;
				
				$( "#slider_date_annee" ).val(an);
				$( "#slider_range" ).slider('value', an);
				
				try {
					figures = JSON.parse(figures);
				}
				catch (error) {
					console.log("Figures était deja parse, pas besoin de le refaire");
				}
				for (i in elmt.changement)
				{
					var creation = true;
					
					for (j in figures.features)
					{	
						if (elmt.changement[i].id == figures.features[j].properties.id)
						{	
							post.changement.push({id : elmt.changement[i].id, prev : JSON.stringify(figures.features[j])});
							
							if (elmt.changement[i].prev == "")
							{
								figures.features.splice(j, 1);
								delete caracs["latLng"][elmt.changement[i].id];
							}
							else
							{
								feature = JSON.parse(elmt.changement[i].prev);
								figures.features[j] = feature;
								
								caracs["latLng"][elmt.changement[i].id] = [feature.properties.annee_debut, feature.properties.annee_fin];
							}
							
							creation = false;
						}
					}
					
					// cas d'un polygone complètement supprimé, id_compris, et qui doit maintenant être recréé
					if (creation)
					{
						post.changement.push({id : elmt.changement[i].id, prev : ""});
						
						feature = JSON.parse(elmt.changement[i].prev);
						figures.features.push(feature);
						
						caracs["latLng"][elmt.changement[i].id] = [feature.properties.annee_debut, feature.properties.annee_fin];
					}
				}
				
				post_actions.push(post);
				figures = JSON.stringify(figures); //IMPORTANT : Sans ça les fonctions leaflet de modification de figure créent de nouvelles formes quand on modifie une forme existante
				if ($("#bouton_latLng").text() == "-")
				{
					affichage_annees("latLng");
				}
				
				affichage_figures();
			}
		});
		
		redoButton.addEventListener('click', function(e) {
			if (post_actions.length != 0)
			{	
				var elmt = post_actions.pop();
				
				var prev = {annee : annee, changement : []};
				
				var an = elmt.annee;
				
				$( "#slider_date_annee" ).val(an);
				$( "#slider_range" ).slider('value', an);
				
				try {
					figures = JSON.parse(figures);
				}
				catch (error) {
					console.log("Figures était deja parse, pas besoin de le refaire");
				}
				for (i in elmt.changement)
				{
					var creation = true;
					
					for (j in figures.features)
					{	
						if (elmt.changement[i].id == figures.features[j].properties.id)
						{
							prev.changement.push({id : elmt.changement[i].id, prev : JSON.stringify(figures.features[j])});
							
							if (elmt.changement[i].prev == "")
							{
								figures.features.splice(j, 1);
								delete caracs["latLng"][elmt.changement[i].id];
							}
							else
							{
								feature = JSON.parse(elmt.changement[i].prev);
								figures.features[j] = feature;
								
								caracs["latLng"][elmt.changement[i].id] = [feature.properties.annee_debut, feature.properties.annee_fin];
							}
							
							creation = false;
						}
					}
					
					// si on doit recréer un polygone qui avait été complètement supprimé, id_compris
					if (creation)
					{
						prev.changement.push({id : elmt.changement[i].id, prev : ""});
						
						feature = JSON.parse(elmt.changement[i].prev);
						figures.features.push(feature);
						
						caracs["latLng"][elmt.changement[i].id] = [feature.properties.annee_debut, feature.properties.annee_fin];
					}
				}
				
				prev_actions.push(prev);
				figures = JSON.stringify(figures); //IMPORTANT : Sans ça les fonctions leaflet de modification de figure créent de nouvelles formes quand on modifie une forme existante
				if ($("#bouton_latLng").text() == "-")
				{
					affichage_annees("latLng");
				}
				
				affichage_figures();
			}
		});
		
		
		var mergeButton = L.DomUtil.create('a', 'leaflet-buttons-control-button', container);
	  
		var icone_merge = L.DomUtil.create('a', 'control-icon', mergeButton);
		
		icone_merge.style.backgroundImage = "url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE5LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyIDUxMjsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPGc+DQoJCTxwYXRoIGQ9Ik0zOTAsMTIyVjBIMHYzOTBoMTIydjEyMmgzOTBWMTIySDM5MHogTTEyMiwzNjBIMzBWMzBoMzMwdjkySDEyMlYzNjB6IE00ODIsNDgySDE1MnYtOTJoMjM4VjE1Mmg5MlY0ODJ6Ii8+DQoJPC9nPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPC9zdmc+DQo=)";
		icone_merge.style.backgroundSize = "19px 23px";
		icone_merge.style.width = '30px';
		icone_merge.style.height = '30px';
		
		mergeButton.addEventListener('click', function(e) {
			if (selection.length == 2)
			{
				var prev = {annee : annee, changement : []};
				
				var poly1 = selection[0].toGeoJSON();
				var poly2 = selection[1].toGeoJSON();
				
				var copie_poly1 = JSON.parse(JSON.stringify(poly1));
				var copie_poly2 = JSON.parse(JSON.stringify(poly2));
				
				
				// on gère le nouveau polygone
				var union = turf.union(copie_poly1, copie_poly2);
				
				id_max += 1;
				
				var annee_fin_union = Math.min(poly1.properties.annee_fin, poly2.properties.annee_fin);
				
				union.properties.statut = 2;
				union.properties.id = id_max;
				union.properties.annee_debut = annee;
				union.properties.annee_fin = annee_fin_union;
				
				prev.changement.push({id : id_max, prev : ""});
				
				try {
					figures = JSON.parse(figures);
				}
				catch (error) {
					console.log("Figures était deja parse, pas besoin de le refaire");
				}
				figures.features.push(union);
				
				caracs["latLng"][id_max] = [annee, annee_fin_union];
				
				// on gère les changements pour poly1 et poly2
				var id_poly1 = poly1.properties.id;
				var id_poly2 = poly2.properties.id;
				
				index = 0;
				while (index < figures.features.length)
				{
					var id_feature = figures.features[index].properties.id;
					
					// on trouve la figure correspondant à poly 1
					if (id_feature == id_poly1 || id_feature == id_poly2)
					{
						// si le poygone existe après fin fusion on le garde après union_année_fin+1
						if (annee_fin_union < figures.features[index].properties.annee_fin)
						{
							var futur_poly = JSON.parse(JSON.stringify(figures.features[index]));
							
							id_max += 1;
							futur_poly.properties.statut = 2;
							futur_poly.properties.id = id_max;
							futur_poly.properties.annee_debut = annee_fin_union+1;
							futur_poly.properties.annee_fin = figures.features[index].properties.annee_fin;
							
							prev.changement.push({id : id_max, prev : ""});
							
							figures.features.push(futur_poly);
							caracs["latLng"][id_max] = [futur_poly.properties.annee_debut, futur_poly.properties.annee_fin];
						}
						
						
						// de même si le polygone existait avant la fusion on le garde jusqu'à année-1
						prev.changement.push({id : id_feature, prev : JSON.stringify(figures.features[index])});
						
						if (figures.features[index].properties.annee_debut < annee)
						{
							if (figures.features[index].properties.statut != 2)
							{
								figures.features[index].properties.statut = 1;
							}
							figures.features[index].properties.annee_fin = annee-1;
							caracs["latLng"][id_feature][1] = annee-1;
						}
						// sinon on le supprime
						else
						{
							if (figures.features[index].properties.statut != 2)
							{
								figures.features[index].properties.statut = 3;
							}
							else
							{
								figures.features.splice(index, 1);
								
								index -= 1;
							}
							delete caracs["latLng"][id_feature];
						}						
						
					}
					
					index += 1;
				}	
				
				figures = JSON.stringify(figures); //IMPORTANT : Sans ça les fonctions leaflet de modification de figure créent de nouvelles formes quand on modifie une forme existante
				if ($("#bouton_latLng").text() == "-")
				{
					affichage_annees("latLng");
				}
				
				prev_actions.push(prev);
				
				post_actions = [];
				
				affichage_figures();
			}
		});

		return container;
	}
});

map.addControl(new autres_boutons());


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
		
		$(sliderContainer).append('<div id="slider_range" style="float:left; width:88%;"></div><div id="slider_date" style="float: right; width: 120px; padding-left:1%;"><input type="number" id="slider_date_annee" min="-3000" max="2022" value="0" style="font-family: Georgia; color: black; font-size:30px; padding-top: 5px; padding-bottom: 7px; padding-left: 11px; padding-right: 7px;"></input></div>');
		
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


// pour les sliders de decalque

$( "#slider_decalque_annee" ).bind('change', function(){
	
	$( "#input_decalque_annee" ).val($( "#slider_decalque_annee" ).val());
	
	autre_geoJSONlayer.clearLayers();
	
	var inter_gL = new L.geoJSON(autres_figures, {
		filter:
			function(feature) {
				return (feature.properties.annee_debut <= $( "#input_decalque_annee" ).val()) && ($( "#input_decalque_annee" ).val() <= feature.properties.annee_fin);
			}
	});
	
	inter_gL = inter_gL.toGeoJSON();

	autre_geoJSONlayer.addData(inter_gL);
	
	affichage_figures();
});

$( "#input_decalque_annee" ).bind('change', function(){
	
	$( "#slider_decalque_annee" ).val($( "#input_decalque_annee" ).val());
	
	autre_geoJSONlayer.clearLayers();
	
	var inter_gL = new L.geoJSON(autres_figures, {
		filter:
			function(feature) {
				return (feature.properties.annee_debut <= $( "#input_decalque_annee" ).val()) && ($( "#input_decalque_annee" ).val() <= feature.properties.annee_fin);
			}
	});
	
	inter_gL = inter_gL.toGeoJSON();

	autre_geoJSONlayer.addData(inter_gL);
	
	affichage_figures();
});

// code magique pour que la largeur de l'autocomplete soit appropriée
jQuery.ui.autocomplete.prototype._resizeMenu = function () {
  var ul = this.menu.element;
  ul.outerWidth(this.element.outerWidth());
}

$.post('../dialogue_BDD_site/recuperation_formes_un_element.php', { id_element:id_element, type_element:type_element }, function(result) {
		result = result.split(";;;");
		
		couleur = result[0];
		$('#html5colorpicker').val(couleur);

		figures = result[1];
		
		caracs = JSON.parse(result[2].replace(/\n/g, "\\n"));
		
		id_max = parseInt(result[3]);
		
		noms_autres_figures = JSON.parse(result[4]);
		
		$('#nom_pays_decalque').autocomplete({
			source : Object.keys(noms_autres_figures),
			minLength:3,
			appendTo: '#conteneur_gauche',
			select: function( event, ui ) {

				$.post('../dialogue_BDD_site/recuperation_formes_un_element.php', { id_element:noms_autres_figures[ui.item.label], type_element:"pays", decalque:true }, function(result_2) {
					
					result_2 = result_2.split(";;;");
					
					autres_figures = JSON.parse(result_2[1]);
					
	
					autre_geoJSONlayer.clearLayers();
					
					var inter_gL = new L.geoJSON(autres_figures, {
						filter:
							function(feature) {
								return (feature.properties.annee_debut <= $( "#input_decalque_annee" ).val()) && ($( "#input_decalque_annee" ).val() <= feature.properties.annee_fin);
							}
					});
					
					inter_gL = inter_gL.toGeoJSON();

					autre_geoJSONlayer.addData(inter_gL);
					
					affichage_figures();
				});
			}
		});
		
		// initialisation des couches geojson		
		geoJSONlayer = new L.geoJSON('', {
			onEachFeature: function(feature, layer) {
				layer.setStyle({color: "#000080"});
				
				layer.addEventListener('click', function(e) {
					
					var bool = false;
					
					for (i in map.pm.Toolbar.buttons)
					{
						var bouton = map.pm.Toolbar.buttons[i];
						
						if (bouton._leaflet_id && bouton._button.toggleStatus)
						{
							bool = true;
						}
					}
					
					// on autorise la sélection d'éléments que si la palette de dessin est désactivée
					if (!bool)
					{
						if (e.originalEvent.shiftKey)
						{
							if (selection.indexOf(e.target) != -1)
							{
								selection.splice( selection.indexOf(e.target), 1);
								e.target.setStyle({color: "#000080"});
							}
							
							else
							{
								selection.push(e.target);
								e.target.setStyle({color: "#FF0080"});
							}
						}
						
						else
						{
							for (i in selection)
							{
								if (selection[i].options.pmIgnore)
								{
									selection[i].setStyle({color: "BEBEBE"});
								}
								else
								{
									selection[i].setStyle({color: "#000080"});
								}
							}
							
							if (selection.indexOf(e.target) == -1)
							{
								selection = [e.target];
								e.target.setStyle({color: "#FF0080"});
							}
							else
							{
								selection = [];
							}
						}
					}
				});
				
				},
			weight: 1,
			fillOpacity: 0.6,
		}).addTo(map);
		
		autre_geoJSONlayer = new L.geoJSON('', {
			onEachFeature: function(feature, layer) {
				layer.setStyle({color: "BEBEBE"});
				
				layer.addEventListener('click', function(e) {

					var bool = false;
					
					for (i in map.pm.Toolbar.buttons)
					{
						var bouton = map.pm.Toolbar.buttons[i];
						
						if (bouton._leaflet_id && bouton._button.toggleStatus)
						{
							bool = true;
						}
					}
					
					// on autorise la sélection d'éléments que si la palette de dessin est désactivée
					if (!bool)
					{
						if (e.originalEvent.shiftKey)
						{
							if (selection.indexOf(e.target) != -1)
							{
								selection.splice( selection.indexOf(e.target), 1);
								e.target.setStyle({color: "BEBEBE"});
							}
							
							else
							{
								selection.push(e.target);
								e.target.setStyle({color: "#FF0080"});
							}
						}
						else
						{
							for (i in selection)
							{
								a = selection[i];
								if (selection[i].options.pmIgnore)
								{
									selection[i].setStyle({color: "BEBEBE"});
								}
								else
								{
									selection[i].setStyle({color: "#000080"});
								}
							}
							
							if (selection.indexOf(e.target) == -1)
							{
								selection = [e.target];
								e.target.setStyle({color: "#FF0080"});
							}
							else
							{
								selection = [];
							}
						}
					}
				});
			},
				
			style: 
				function(feature) {
					return {color: 'BEBEBE'};
				},
			pointToLayer: 
				function (feature, latlng) {
					
				},
			pmIgnore: true,
			weight: 0,
			fillOpacity: 0.2
		}).addTo(map);
		
		
		// interdiction du déplacement de polygones parce qu'à quoi ça pourrait bien servir ?
		geoJSONlayer.pm.enable( {draggable: false} );
		geoJSONlayer.pm.disable()

		geoJSONlayer.on('pm:edit', function(e) {
			var prev = {annee : annee, changement : []};
			
			var modif = e.sourceTarget.toGeoJSON();
			var id_modifie = modif.properties.id;
			
			try {
				figures = JSON.parse(figures);
			}
			catch (error) {
				console.log("Figures était deja parse, pas besoin de le refaire");
			}
			for (index in figures.features)
			{
				var id_feature = figures.features[index].properties.id;
				
				if (id_feature == id_modifie)
				{
					prev.changement.push({id : id_modifie, prev : JSON.stringify(figures.features[index])});
					
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
						delete caracs["latLng"][id_feature]; // on supprime un élément qui n'existe pas tjrs ?
					}
					
					id_max += 1;
					modif.properties.statut = 2;
					modif.properties.id = id_max;
					modif.properties.annee_debut = annee;
					
					prev.changement.push({id : id_max, prev : ""});
					
					figures.features.push(modif);
					caracs["latLng"][id_max] = [annee, modif.properties.annee_fin];
				}
			}
			
			figures = JSON.stringify(figures); //IMPORTANT : Sans ça les fonctions leaflet de modification de figure créent de nouvelles formes quand on modifie une forme existante
			if ($("#bouton_latLng").text() == "-")
			{
				affichage_annees("latLng");
			}
			
			prev_actions.push(prev);// que deviens cette variable ?
			
			post_actions = [];
		});

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
	var nouvelle_forme = e.layer.toGeoJSON();
	
	id_max += 1;
	
	L.extend(nouvelle_forme.properties, {
		statut: 2,
		id_bdd: '',
		id: id_max,
		annee_debut: annee,
		annee_fin: max_annee
	});
	
	prev_actions.push({annee : annee, changement : [{id : id_max, prev : ""}]});
	
	post_actions = [];
	
	try {
		figures = JSON.parse(figures);
	}
	catch (error) {
		console.log("Figures était deja parse, pas besoin de le refaire");
	}
	figures.features.push(nouvelle_forme);

	figures = JSON.stringify(figures); //IMPORTANT : Sans ça les fonctions leaflet de modification de figure créent de nouvelles formes quand on modifie une forme existante
	caracs["latLng"][id_max] = [annee, max_annee];
	
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
		
		try {
			figures = JSON.parse(figures);
		}
		catch (error) {
			console.log("Figures était deja parse, pas besoin de le refaire");
		}
		for (index in figures.features)
		{
			var id_feature = figures.features[index].properties.id;
			
			if (id_feature == id_modifie)
			{
				prev_actions.push({annee : annee, changement : [{id : id_modifie, prev : JSON.stringify(figures.features[index])}]});
				
				post_actions = [];
				
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
		
		figures = JSON.stringify(figures); //IMPORTANT : Sans ça les fonctions leaflet de modification de figure créent de nouvelles formes quand on modifie une forme existante
		if ($("#bouton_latLng").text() == "-")
		{
			affichage_annees("latLng");
		}
		
		affichage_figures();
		
		for (i in map.pm.Toolbar.buttons)
		{
			var bouton = map.pm.Toolbar.buttons[i];

			if (bouton._button.className == " leaflet-pm-icon-delete")
			{
				bouton._button.afterClick();
			}
		}
	}
});

map.on('pm:cut', function(e) {
	ecoute_remove = false;
	map.removeLayer(e.cuttedLayer);
	ecoute_remove = true;
	
	var prev = {annee : annee, changement : []};
	
	var tab = e.resultingLayers;
	
	tab.forEach(function(element) {
		
		var couches = element._layers;
		
		for (i in couches)
		{	
			var nouvelle_forme_coupee = couches[i].toGeoJSON();
			var id_modifie = nouvelle_forme_coupee.properties.id;
			
			try {
				figures = JSON.parse(figures);
			}
			catch (error) {
				console.log("Figures était deja parse, pas besoin de le refaire");
			}
			for (index in figures.features)
			{
				var id_feature = figures.features[index].properties.id;
				
				if (id_feature == id_modifie)
				{
					prev.changement.push({id : id_modifie, prev : JSON.stringify(figures.features[index])});
					
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
					
					if (nouvelle_forme_coupee.geometry.type == "MultiPolygon")
					{
						for (i_f in nouvelle_forme_coupee.geometry.coordinates)
						{
							var nouvelle_forme = JSON.parse(JSON.stringify(nouvelle_forme_coupee));
							nouvelle_forme.geometry.coordinates = [ nouvelle_forme_coupee.geometry.coordinates[i_f] ];
							
							id_max += 1;
							nouvelle_forme.properties.statut = 2;
							nouvelle_forme.properties.id = id_max;
							nouvelle_forme.properties.annee_debut = annee;
							
							prev.changement.push({id : id_max, prev : ""});
							
							figures.features.push(nouvelle_forme);
							caracs["latLng"][id_max] = [annee, nouvelle_forme.properties.annee_fin];
						}
					}
					else
					{
						var nouvelle_forme = JSON.parse(JSON.stringify(nouvelle_forme_coupee));
						
						id_max += 1;
						nouvelle_forme.properties.statut = 2;
						nouvelle_forme.properties.id = id_max;
						nouvelle_forme.properties.annee_debut = annee;
						
						prev.changement.push({id : id_max, prev : ""});
						
						figures.features.push(nouvelle_forme);
						caracs["latLng"][id_max] = [annee, nouvelle_forme.properties.annee_fin];
					}
				}
			}
			
			figures = JSON.stringify(figures); //IMPORTANT : Sans ça les fonctions leaflet de modification de figure créent de nouvelles formes quand on modifie une forme existante
			if ($("#bouton_latLng").text() == "-")
			{
				affichage_annees("latLng");
			}
			
			ecoute_remove = false;
			map.removeLayer(couches[i]);
			ecoute_remove = true;
		}
	});
	
	prev_actions.push(prev);
	
	post_actions = [];
	
	affichage_figures();
});



// fonctions
function validateURL(s) {
   var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
   return regexp.test(s);
}

function securite_ok()
{
	if ($("#conteneur_droite_population_etat").val() != "" && isNaN(parseInt($("#conteneur_droite_population_etat").val())))
	{
		$("#conteneur_droite_population_etat").css("background-color", "#B22222");
		$("#conteneur_droite_population_etat").css("color", "#ffffff");
		
		return false;
	}
	else
	{
		if ($("#conteneur_droite_population_etat").val() != "") { $("#conteneur_droite_population_etat").val(parseInt($("#conteneur_droite_population_etat").val())); }
		
		$("#conteneur_droite_population_etat").css("background-color", "#e8edf0");
		$("#conteneur_droite_population_etat").css("color", "#8a97a0");
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

	for (ix in caracs[key])
	{
		if (caracs[key][ix][3] != 3)
		{
			// si on n'a pas déjà mis ce couple d'années dans le tableau on le rajoute
			if (typeof(tab_annees.find(element => element[0] == caracs[key][ix][0] && element[1] == caracs[key][ix][1])) == "undefined")
			{
				tab_annees.push([caracs[key][ix][0], caracs[key][ix][1]]);
			}
		}
	}
	tab_annees.sort(function(a, b) { return a[0]-b[0] });

	
	chaine = "";
	for (ix = 0; ix < tab_annees.length; ix++)
	{
		chaine += "<p>De <b onclick=\"clic_annee("+tab_annees[ix][0]+", "+tab_annees[ix][1]+", 0);\">" + tab_annees[ix][0] + "</b> à <b onclick=\"clic_annee("+tab_annees[ix][0]+", "+tab_annees[ix][1]+", 1)\">" + tab_annees[ix][1] + "</b></p>";
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

function clic_annee(annee_debut, annee_fin, bool_fin)
{
	if (securite_ok())
	{
		changement_annee = annee_debut;
		if (bool_fin == 1) {
			changement_annee = annee_fin;
		}
		
		$( "#slider_date_annee" ).val(changement_annee);
		$( "#slider_range" ).slider('value', $( "#slider_date_annee" ).val());

		mise_a_jour_carte();
		
		geoJSONlayer.eachLayer(function(layer) {
			if (layer.feature.properties.annee_debut == annee_debut && layer.feature.properties.annee_fin == annee_fin) {
				selection.push(layer);
				layer.setStyle({color: "#FF0080"});
			}
		});
	}
}

function reinitialisation_palette_dessin()
{
	for (ib in map.pm.Toolbar.buttons)
	{
		var bouton = map.pm.Toolbar.buttons[ib];
		
		if (bouton._leaflet_id && bouton._button.toggleStatus)
		{
			bouton._button.afterClick();
		}
	}
}

function population_inc()
{
	if ($("#zero_info_pop").prop('checked'))
	{
		$("#conteneur_droite_population_etat").prop('disabled', true);
		$("#conteneur_droite_population_etat").val(-1);
		
		$("#conteneur_droite_population_etat").css("background-color", "#616466");
		$("#conteneur_droite_population_etat").css("color", "#8a97a0");
	}
	else
	{
		$("#conteneur_droite_population_etat").prop('disabled', false);
		$("#conteneur_droite_population_etat").val("");
		
		$("#conteneur_droite_population_etat").css("background-color", "#e8edf0");
		$("#conteneur_droite_population_etat").css("color", "#8a97a0");
	}
}

function enregistrement_caracteristiques()
{
	Object.keys(caracs).forEach(function (key) {
		
		if (key != "latLng")
		{
			var nouvelle_carac;
			if (key == "nomade")
			{
				nouvelle_carac = $("#conteneur_droite_nomade").prop('checked');
			}
			else
			{
				nouvelle_carac = $("#conteneur_droite_" + key).val();
			}
			
			// si le nom dans la boîte est différent du nom prévu
			if (nouvelle_carac != anciennes_caracs[key])
			{
				var fin_carac = max_annee;
				
				// en modifiant l'ancien élément (en le supprimant si besoin en changeant le statut, en changeant les années de fin/début et le statut sinon)
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
	geoJSONlayer.clearLayers();
	ecoute_remove = true;
	
	affichage_figures_from_scratch();
}

function affichage_figures_from_scratch()
{
	reinitialisation_palette_dessin();
	
	annee = parseInt($( "#slider_date_annee" ).val());
	
	
	// on réinitialise la sélection : obligé comme il y a eu modification de figures
	selection = [];
	
	autre_geoJSONlayer.eachLayer(function (layer) {  
		layer.setStyle({color: "BEBEBE"});
	});
	try {
		figures = JSON.parse(figures);
	}
	catch (error) {
		console.log("Figures était deja parse, pas besoin de le refaire");
	}
	var inter_gL = new L.geoJSON(figures, {
		filter:
			function(feature) {
				return (feature.properties.statut <= 2) && (feature.properties.annee_debut <= annee) && (annee <= feature.properties.annee_fin);
			}
	});
	figures = JSON.stringify(figures); //IMPORTANT : Sans ça les fonctions leaflet de modification de figure créent de nouvelles formes quand on modifie une forme existante
	inter_gL = inter_gL.toGeoJSON();
	geoJSONlayer.addData(inter_gL);
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
		
		if (key == "nomade")
		{
			$("#conteneur_droite_nomade").prop('checked', anciennes_caracs[key]);
		}
		else
		{
			$("#conteneur_droite_" + key).val(anciennes_caracs[key]);
		}
		
		
		if ($("#conteneur_droite_population_etat").val() == -1)
		{
			$("#zero_info_pop").prop('checked', true);
			$("#conteneur_droite_population_etat").prop('disabled', true);
			
			$("#conteneur_droite_population_etat").css("background-color", "#616466");
			$("#conteneur_droite_population_etat").css("color", "#8a97a0");
		}
		else
		{
			$("#zero_info_pop").prop('checked', false);
			$("#conteneur_droite_population_etat").prop('disabled', false);
		}
		
	});
}


function envoi_forme()
{
	if (securite_ok())
	{
		enregistrement_caracteristiques();
		delete caracs["latLng"];
		try {
			figures = JSON.parse(figures);
		}
		catch (error) {
			console.log("Figures était deja parse, pas besoin de le refaire");
		}

		if ( $("#html5colorpicker").length ) {
			couleur = $("#html5colorpicker").val();
		}
		
		var bool = false;
		var lignes = [];
		for (i in figures.features)
		{
			if (figures.features[i].properties.statut != 0)
			{
				var geometry = figures.features[i].geometry;
				// je récupère les coordonnées de la figure
				var shape = geometry.coordinates;
				// si on n'a pas affaire à un multipolygon mais à un simple polygone on le met en mode multipolygone en rajoutant des crochets
				if (geometry.type != "MultiPolygon")
				{
					tab = []
					tab.push(shape);
					shape = tab;
				}
				shape = JSON.stringify(shape);
				
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
		
		// Convert data to JSON string
		var jsonData = JSON.stringify({
			id_element: id_element,
			couleur_element: couleur,
			type_element: type_element,
			lignes: lignes,
			caracs: caracs,
			bool: bool
		});

		try{
			// Use $.ajax to send JSON data
			$.ajax({
				url: "../dialogue_BDD_site/traitement_formes_un_element.php",
				type: "POST",
				contentType: "application/json",
				data: jsonData,
				success: function(result) {
					window.location.href = "../index.php";
				}
			});
		}
		catch (error)
		{
			console.log("Post method failed. Error : ");
			console.log(error);
			figures = JSON.stringify(figures); //IMPORTANT : Sans ça les fonctions leaflet de modification de figure créent de nouvelles formes quand on modifie une forme existante
		}
	}
}

</script>

</body>

</html>