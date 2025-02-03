-- Récupérer géométrie des pays pour un pays donné
SELECT id_entite_pays, annee_debut, annee_fin, geometry, couleur
FROM public.geometrie_pays JOIN public.entites_pays 
ON geometrie_pays.id_entite_pays = entites_pays.id_entite_pays
WHERE id_entite_pays = %id_entite_pays% 

-- Récupérer populations pays pour un pays donné
SELECT id_entite_pays, annee, population
FROM public.population_pays
WHERE id_entite_pays = %id_entite_pays%

-- Récupérer nom pays pour un pays donné
SELECT id_entite_pays, nom_pays, annee_debut, annee_fin, proto_etat
FROM public.nom_pays JOIN public.pays
ON nom_pays.id_nom_pays = pays.id_nom_pays
WHERE id_entite_pays = %id_entite_pays%

-- Récupérer tout ce qui est relatififié à un pays pour un pays donné
SELECT id_entite_pays, geometrie_pays.annee_debut AS annee_debut_geometrie, geometrie_pays.annee_fin AS annee_fin_geometrie, geometry, couleur, population_pays.annee AS annee_population, population, nom_pays, pays.annee_debut AS annee_debut_nom, pays.annee_fin AS annee_fin_nom, proto_etat
FROM public.geometrie_pays JOIN public.entites_pays 
ON geometrie_pays.id_entite_pays = entites_pays.id_entite_pays
JOIN public.population_pays
ON entites_pays.id_entite_pays = population_pays.id_entite_pays
JOIN public.pays
ON entites_pays.id_entite_pays = pays.id_entite_pays
JOIN public.nom_pays 
ON nom_pays.id_nom_pays = pays.id_nom_pays
WHERE annee_debut_nom <= %annee% AND annee_fin_nom >= %annee% AND annee_debut_geometrie <= %annee% AND annee_fin_geometrie >= %annee% 
AND annee_population = (
        SELECT annee 
        FROM public.population_pays 
        WHERE id_entite_pays = entites_pays.id_entite_pays 
        AND annee >= %annee% 
        ORDER BY ABS(annee - %annee%) 
        LIMIT 1
    );
-- Il faut vérifier que l'annee_population est bien supérieure ou égale à %annee% et l'annee la moins éloignée de %annee% pour les autres valeurs de population du pays. C'est à ça que sert la sous-requête.