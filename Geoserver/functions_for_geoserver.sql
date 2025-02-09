-- Récupérer géométrie à toutes les dates pour une entité pays donnée
-- one_country_all_time_geometry
CREATE OR REPLACE FUNCTION get_one_country_geometries(entite_pays_id INT)
RETURNS TABLE (
    id_entite_pays INT,
    annee_debut INT,
    annee_fin INT,
    geometrie GEOMETRY,
    couleur VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT entites_pays.id_entite_pays, CAST(periodes.annee_debut AS int) as annee_debut_geometrie, CAST(periodes.annee_fin AS int) as annee_fin_geometrie, geometrie_pays.geometrie, entites_pays.couleur
    FROM public.geometrie_pays JOIN public.entites_pays
    ON geometrie_pays.id_entite_pays = entites_pays.id_entite_pays
    JOIN public.periodes ON geometrie_pays.id_periode = periodes.id_periode
    WHERE entites_pays.id_entite_pays = entite_pays_id;
END;
$$ LANGUAGE plpgsql;

-- Récupérer populations à toutes les dates pour une entité pays donnée
-- one_country_all_time_population
CREATE OR REPLACE FUNCTION get_one_country_populations(entite_pays_id INT)
RETURNS TABLE (
    id_entite_pays INT,
    annee INT,
    population INT
) AS $$
BEGIN
    RETURN QUERY
    SELECT p.id_entite_pays, CAST(p.annee AS int), p.population
    FROM public.populations_pays AS p
    WHERE p.id_entite_pays = entite_pays_id;
END;
$$ LANGUAGE plpgsql;

-- Récupérer nom à toutes les dates pour une entité pays donnée
-- one_country_all_time_name
CREATE OR REPLACE FUNCTION get_one_country_names(entite_pays_id INT)
RETURNS TABLE (
    id_entite_pays INT,
    nom_pays VARCHAR,
    annee_debut INT,
    annee_fin INT,
    proto_etat BOOLEAN
) AS $$
BEGIN
    RETURN QUERY
    SELECT pays.id_entite_pays, noms_pays.nom_pays, CAST(periodes.annee_debut AS int), CAST(periodes.annee_fin as int), pays.proto_etat
    FROM public.noms_pays JOIN public.pays
    ON noms_pays.id_nom_pays = pays.id_nom_pays
    JOIN public.periodes ON pays.id_periode = periodes.id_periode
    WHERE pays.id_entite_pays = entite_pays_id;
END;
$$ LANGUAGE plpgsql;

-- Récupérer tout ce qui est relatif à tout les pays pour une année donnée
-- all_countries_one_time
-- La population n'étant pas obligatoire on utilise un LEFT JOIN
CREATE OR REPLACE FUNCTION get_all_countries_at_date(year INT)
RETURNS TABLE (
    id_entite_pays INT,
    annee_debut_geometrie INT,
    annee_fin_geometrie INT,
    couleur VARCHAR,
    annee_population INT,
    population INT,
    nom_pays VARCHAR,
    annee_debut_nom INT,
    annee_fin_nom INT,
    proto_etat BOOLEAN,
    geometrie GEOMETRY
) AS $$
BEGIN
    RETURN QUERY
    SELECT entites_pays.id_entite_pays, CAST(geometrie_pays_periodes.annee_debut AS int) AS annee_debut_geometrie, CAST(geometrie_pays_periodes.annee_fin AS int) AS annee_fin_geometrie, entites_pays.couleur, CAST(populations_pays.annee AS int) AS annee_population, populations_pays.population, noms_pays.nom_pays, CAST(pays_periodes.annee_debut AS int) AS annee_debut_nom, CAST(pays_periodes.annee_fin AS int) AS annee_fin_nom, pays.proto_etat, geometrie_pays.geometrie
    FROM public.geometrie_pays 
    JOIN public.entites_pays ON geometrie_pays.id_entite_pays = entites_pays.id_entite_pays
    JOIN public.periodes AS geometrie_pays_periodes ON geometrie_pays.id_periode = geometrie_pays_periodes.id_periode
    LEFT JOIN public.populations_pays ON entites_pays.id_entite_pays = populations_pays.id_entite_pays
    JOIN public.pays ON entites_pays.id_entite_pays = pays.id_entite_pays
    JOIN public.periodes AS pays_periodes ON pays.id_periode = pays_periodes.id_periode
    JOIN public.noms_pays ON noms_pays.id_nom_pays = pays.id_nom_pays
    WHERE pays_periodes.annee_debut <= year AND pays_periodes.annee_fin >= year AND geometrie_pays_periodes.annee_debut <= year AND geometrie_pays_periodes.annee_fin >= year 
    AND (populations_pays.annee = (
            SELECT annee 
            FROM public.populations_pays 
            WHERE populations_pays.id_entite_pays = entites_pays.id_entite_pays 
            AND populations_pays.annee >= year 
            ORDER BY ABS(CAST(annee AS int) - year) 
            LIMIT 1
        ) OR populations_pays.annee IS NULL);
END;
$$ LANGUAGE plpgsql;
-- Il faut vérifier que l'annee_population est bien supérieure ou égale à year et l'annee la moins éloignée de year pour les autres valeurs de population du pays. C'est à ça que sert la sous-requête.


---------- Maintenant, même chose mais pour les villes ----------
-- Récupérer géométrie pour une entité ville donnée
-- one_city_all_time_geometry
CREATE OR REPLACE FUNCTION get_one_city_geometries(entite_ville_id INT)
RETURNS TABLE (
    id_entite_ville INT,
    position_ville GEOMETRY
) AS $$
BEGIN
    RETURN QUERY
    SELECT entites_villes.id_entite_ville, entites_villes.position_ville
    FROM public.entites_villes
    WHERE entites_villes.id_entite_ville = entite_ville_id;
END;
$$ LANGUAGE plpgsql;

-- Récupérer populations à toutes les dates pour une entité ville donnée
-- one_city_all_time_population
CREATE OR REPLACE FUNCTION get_one_city_populations(entite_ville_id INT)
RETURNS TABLE (
    id_entite_ville INT,
    annee INT,
    population INT
) AS $$
BEGIN
    RETURN QUERY
    SELECT populations_villes.id_entite_ville, CAST(populations_villes.annee AS int), populations_villes.population
    FROM public.populations_villes
    WHERE populations_villes.id_entite_ville = entite_ville_id;
END;
$$ LANGUAGE plpgsql;

-- Récupérer nom à toutes les dates pour une entité ville donnée
-- one_city_all_time_name
CREATE OR REPLACE FUNCTION get_one_city_names(entite_ville_id INT)
RETURNS TABLE (
    id_entite_ville INT,
    nom_ville VARCHAR,
    annee_debut INT,
    annee_fin INT
) AS $$
BEGIN
    RETURN QUERY
    SELECT ville.id_entite_ville, noms_villes.nom_ville, CAST(periodes.annee_debut AS int), CAST(periodes.annee_fin AS int)
    FROM public.noms_villes JOIN public.ville
    ON noms_villes.id_nom_ville = ville.id_nom_ville
    JOIN public.periodes ON ville.id_periode = periodes.id_periode
    WHERE ville.id_entite_ville = entite_ville_id;
END;
$$ LANGUAGE plpgsql;

-- Récupérer tout ce qui est relatif aux villes pour une annee donnée
-- all_cities_one_time
-- La population n'étant pas obligatoire on utilise un LEFT JOIN
CREATE OR REPLACE FUNCTION get_all_cities_at_date(year INT)
RETURNS TABLE (
    id_entite_ville INT,
    annee_population INT,
    population INT,
    nom_ville VARCHAR,
    annee_debut_nom INT,
    annee_fin_nom INT,
    position_ville GEOMETRY
) AS $$
BEGIN
    RETURN QUERY
    SELECT entites_villes.id_entite_ville, CAST(populations_villes.annee AS int) AS annee_population, populations_villes.population, noms_villes.nom_ville, CAST(ville_periodes.annee_debut AS int) AS annee_debut_nom, CAST(ville_periodes.annee_fin AS int) AS annee_fin_nom, entites_villes.position_ville
    FROM public.entites_villes
    LEFT JOIN public.populations_villes ON entites_villes.id_entite_ville = populations_villes.id_entite_ville
    JOIN public.ville ON entites_villes.id_entite_ville = ville.id_entite_ville
    JOIN public.periodes AS ville_periodes ON ville.id_periode = ville_periodes.id_periode
    JOIN public.noms_villes ON noms_villes.id_nom_ville = ville.id_nom_ville
    WHERE ville_periodes.annee_debut <= year AND ville_periodes.annee_fin >= year
    AND (populations_villes.annee = (
            SELECT populations_villes.annee
            FROM public.populations_villes
            WHERE populations_villes.id_entite_ville = entites_villes.id_entite_ville
            AND populations_villes.annee >= year
            ORDER BY ABS(CAST(populations_villes.annee AS int) - year)
            LIMIT 1
        ) OR populations_villes.annee IS NULL);
END;
$$ LANGUAGE plpgsql;
-- You can't use alias AS in WHERE statements