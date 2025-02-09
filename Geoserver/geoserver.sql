n-- Récupérer géométrie à toutes les dates pour une entité pays donnée
-- one_country_all_time_geometry
SELECT id_entite_pays, annee_debut, annee_fin, geometrie, couleur
FROM public.geometrie_pays JOIN public.entites_pays 
ON geometrie_pays.id_entite_pays = entites_pays.id_entite_pays
JOIN public.periodes ON entites_pays.id_periode = periode.id_periode
WHERE id_entite_pays = %id_entite_pays% 

-- Récupérer populations à toutes les dates pour une entité pays donnée
-- one_country_all_time_population
SELECT id_entite_pays, annee, population
FROM public.populations_pays
WHERE id_entite_pays = %id_entite_pays%

-- Récupérer nom à toutes les dates pour une entité pays donnée
-- one_country_all_time_name
SELECT id_entite_pays, nom_pays, annee_debut, annee_fin, proto_etat
FROM public.noms_pays JOIN public.pays
ON noms_pays.id_nom_pays = pays.id_nom_pays
JOIN public.periodes ON pays.id_periode = periode.id_periode
WHERE id_entite_pays = %id_entite_pays%

-- Récupérer tout ce qui est relatif à tout les pays pour une année donnée
-- all_countries_one_time
SELECT id_entite_pays, geometrie_pays_periodes.annee_debut AS annee_debut_geometrie, geometrie_pays_periodes.annee_fin AS annee_fin_geometrie, geometrie, couleur, populations_pays.annee AS annee_population, population, nom_pays, pays_periodes.annee_debut AS annee_debut_nom, pays_periodes.annee_fin AS annee_fin_nom, proto_etat
FROM public.geometrie_pays JOIN public.entites_pays 
ON geometrie_pays.id_entite_pays = entites_pays.id_entite_pays
JOIN public.periodes AS geometrie_pays_periodes ON geometrie_pays.id_periode = geometrie_pays_periodes.id_periode
JOIN public.populations_pays
ON entites_pays.id_entite_pays = populations_pays.id_entite_payse
JOIN public.pays
ON entites_pays.id_entite_pays = pays.id_entite_pays
JOIN public.periodes AS pays_periodes ON pays.id_periode = pays_periodes.id_periode
JOIN public.noms_pays 
ON noms_pays.id_nom_pays = pays.id_nom_pays
WHERE annee_debut_nom <= %annee% AND annee_fin_nom >= %annee% AND annee_debut_geometrie <= %annee% AND annee_fin_geometrie >= %annee% 
AND annee_population = (
        SELECT annee 
        FROM public.populations_pays 
        WHERE id_entite_pays = entites_pays.id_entite_pays 
        AND annee >= %annee% 
        ORDER BY ABS(annee - %annee%) 
        LIMIT 1
    )
-- Il faut vérifier que l'annee_population est bien supérieure ou égale à %annee% et l'annee la moins éloignée de %annee% pour les autres valeurs de population du pays. C'est à ça que sert la sous-requête.


---------- Maintenant, même chose mais pour les villes ----------
-- Récupérer géométrie pour une entité ville donnée
-- one_city_all_time_geometry
SELECT id_entite_ville, position_ville
FROM public.entites_villes
WHERE id_entite_ville = %id_entite_ville%

-- Récupérer populations à toutes les dates pour une entité ville donnée
-- one_city_all_time_population
SELECT id_entite_ville, annee, population
FROM public.populations_villes
WHERE id_entite_ville = %id_entite_ville%

-- Récupérer nom à toutes les dates pour une entité ville donnée
-- one_city_all_time_name
SELECT id_entite_ville, nom_ville, annee_debut, annee_fin
FROM public.noms_villes JOIN public.ville
ON noms_villes.id_nom_ville = ville.id_nom_ville
JOIN public.periodes ON ville.id_periode = periodes .id_periode
WHERE id_entite_ville = %id_entite_ville%

-- Récupérer tout ce qui est relatif aux villes pour une annee donnée
-- all_cities_one_time
SELECT entites_villes.id_entite_ville, entites_villes.position_ville, populations_villes.annee AS annee_population, populations_villes.population, noms_villes.nom_ville, ville_periodes.annee_debut AS annee_debut_nom, ville_periodes.annee_fin AS annee_fin_nom
FROM public.entites_villes
JOIN public.populations_villes
ON entites_villes.id_entite_ville = populations_villes.id_entite_ville
JOIN public.ville
ON entites_villes.id_entite_ville = ville.id_entite_ville
JOIN public.periodes AS ville_periodes ON ville.id_periode = ville_periodes.id_periode
JOIN public.noms_villes
ON noms_villes.id_nom_ville = ville.id_nom_ville
WHERE ville_periodes.annee_debut <= %annee% AND ville_periodes.annee_fin >= %annee% AND populations_villes.annee = (
        SELECT annee 
        FROM public.populations_villes 
        WHERE id_entite_ville = entites_villes.id_entite_ville 
        AND annee >= %annee% 
        ORDER BY ABS(CAST(annee AS int) - %annee%) 
        LIMIT 1
    )
-- You can't use alias AS in WHERE statements