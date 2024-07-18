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


/// Fonction pour calculer l'aire visible d'un polygone sur la carte, resultat en poucentage de l'aire totale visible à l'ecran
function calcul_aire_visible_polygone(polygon, map_bounds, map_aire){
	var area = 0;
	var j = polygon.length - 1;
	for (var i = 0; i < polygon.length; i++){
		if (L.latLngBounds(map_bounds).contains([polygon[i][1], polygon[i][0]])){
			area += (polygon[j][0] + polygon[i][0]) * (polygon[j][1] - polygon[i][1]);
		}
		j = i;
	}
	return Math.abs(100 * area / (2 * map_aire));
}

/// Fonction pour calculer l'aire d'un polygone
function calcul_aire_polygone(polygon){
	var area = 0;
	var j = polygon.length - 1;
	for (var i = 0; i < polygon.length; i++){
		area += (polygon[j][0] + polygon[i][0]) * (polygon[j][1] - polygon[i][1]);
		j = i;
	}
	return Math.abs(area / 2);
}