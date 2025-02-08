-- Récupérer géométrie des pays pour un pays donné
SELECT id_entite_pays, annee_debut, annee_fin, geometrie, couleur
FROM public.geometrie_pays JOIN public.entites_pays 
ON geometrie_pays.id_entite_pays = entites_pays.id_entite_pays
JOIN public.periodes ON entites_pays.id_periode = periode.id_periode
WHERE id_entite_pays = %id_entite_pays% 

-- Récupérer populations pays pour un pays donné
SELECT id_entite_pays, annee, population
FROM public.population_pays
WHERE id_entite_pays = %id_entite_pays%

-- Récupérer nom pays pour un pays donné
SELECT id_entite_pays, nom_pays, annee_debut, annee_fin, proto_etat
FROM public.nom_pays JOIN public.pays
ON nom_pays.id_nom_pays = pays.id_nom_pays
JOIN public.periodes ON pays.id_periode = periode.id_periode
WHERE id_entite_pays = %id_entite_pays%

-- Récupérer tout ce qui est relatififié à un pays pour un pays donné
SELECT id_entite_pays, geometrie_pays_periodes.annee_debut AS annee_debut_geometrie, geometrie_pays_periodes.annee_fin AS annee_fin_geometrie, geometrie, couleur, population_pays.annee AS annee_population, population, nom_pays, pays_periodes.annee_debut AS annee_debut_nom, pays_periodes.annee_fin AS annee_fin_nom, proto_etat
FROM public.geometrie_pays JOIN public.entites_pays 
ON geometrie_pays.id_entite_pays = entites_pays.id_entite_pays
JOIN public.periodes AS geometrie_pays_periodes ON geometrie_pays.id_periode = geometrie_pays_periodes.id_periode
JOIN public.population_pays
ON entites_pays.id_entite_pays = population_pays.id_entite_payse
JOIN public.pays
ON entites_pays.id_entite_pays = pays.id_entite_pays
JOIN public.periodes AS pays_periodes ON pays.id_periode = pays_periodes.id_periode
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


---------- Maintenant, même chose mais pour les villes ----------
-- Récupérer géométrie des villes pour une ville donnée
SELECT id_entite_ville, position_ville
FROM public.entites_ville
WHERE id_entite_ville = %id_entite_ville%

-- Récupérer populations villes pour une ville donnée
SELECT id_entite_ville, annee, population
FROM public.population_ville
WHERE id_entite_ville = %id_entite_ville%

-- Récupérer nom ville pour une ville donnée
SELECT id_entite_ville, nom_ville, annee_debut, annee_fin
FROM public.nom_ville JOIN public.ville
ON nom_ville.id_nom_ville = ville.id_nom_ville
JOIN public.periodes ON ville.id_periode = periode.id_periode
WHERE id_entite_ville = %id_entite_ville%

-- Récupérer tout ce qui est relatififié à une ville pour une annee donnée
SELECT id_entite_ville, entites_ville.position_ville, population_ville.annee AS annee_population, population, nom_ville, ville_periodes.annee_debut AS annee_debut_nom, ville_periodes.annee_fin AS annee_fin_nom
FROM public.entites_ville
JOIN public.population_ville
ON entites_ville.id_entite_ville = population_ville.id_entite_ville
JOIN public.ville
ON entites_ville.id_entite_ville = ville.id_entite_ville
JOIN public.periodes AS ville_periodes ON ville.id_periode = ville_periodes.id_periode
JOIN public.nom_ville
ON nom_ville.id_nom_ville = ville.id_nom_ville
WHERE annee_debut_nom <= %annee% AND annee_fin_nom >= %annee% AND annee_population = (
        SELECT annee 
        FROM public.population_ville 
        WHERE id_entite_ville = entites_ville.id_entite_ville 
        AND annee >= %annee% 
        ORDER BY ABS(annee - %annee%) 
        LIMIT 1
    );
