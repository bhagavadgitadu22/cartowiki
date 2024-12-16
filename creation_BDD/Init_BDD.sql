CREATE EXTENSION postgis;

CREATE SEQUENCE public.utilisateurs_id_utilisateur_seq;

CREATE TABLE public.utilisateurs (
                id_utilisateur INTEGER NOT NULL DEFAULT nextval('public.utilisateurs_id_utilisateur_seq'),
                pseudo VARCHAR(32) NOT NULL,
                mail VARCHAR(128) NOT NULL,
                mdp_hash VARCHAR(128) NOT NULL,
                niveau_admin INTEGER DEFAULT 1 NOT NULL,
                CONSTRAINT utilisateurs_pk PRIMARY KEY (id_utilisateur)
);


ALTER SEQUENCE public.utilisateurs_id_utilisateur_seq OWNED BY public.utilisateurs.id_utilisateur;

CREATE SEQUENCE public.entites_pays_id_entite_pays_seq;

CREATE TABLE public.entites_pays (
                id_entite_pays INTEGER NOT NULL DEFAULT nextval('public.entites_pays_id_entite_pays_seq'),
                couleur VARCHAR(8) NOT NULL,
                CONSTRAINT entites_pays_pk PRIMARY KEY (id_entite_pays)
);


ALTER SEQUENCE public.entites_pays_id_entite_pays_seq OWNED BY public.entites_pays.id_entite_pays;

CREATE SEQUENCE public.populations_pays_id_pop_pays_seq;

CREATE TABLE public.Populations_pays (
                id_pop_pays INTEGER NOT NULL DEFAULT nextval('public.populations_pays_id_pop_pays_seq'),
                id_entite_pays INTEGER NOT NULL,
                population INTEGER NOT NULL,
                annee SMALLINT DEFAULT -30000 NOT NULL,
                CONSTRAINT populations_pays_pk PRIMARY KEY (id_pop_pays)
);


ALTER SEQUENCE public.populations_pays_id_pop_pays_seq OWNED BY public.Populations_pays.id_pop_pays;

CREATE SEQUENCE public.geometrie_pays_id_geometrie_pays_seq;

CREATE TABLE public.geometrie_pays (
                id_geometrie_pays INTEGER NOT NULL DEFAULT nextval('public.geometrie_pays_id_geometrie_pays_seq'),
                id_entite_pays INTEGER NOT NULL,
                annee_debut SMALLINT DEFAULT -30000 NOT NULL,
                annee_fin SMALLINT DEFAULT -30000 NOT NULL,
                geometry GEOMETRY NOT NULL,
                id_modif INTEGER,
                CONSTRAINT geometrie_pays_pk PRIMARY KEY (id_geometrie_pays)
);
COMMENT ON COLUMN public.geometrie_pays.annee_debut IS '-30000 = première annee de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.geometrie_pays.annee_fin IS '-30000 = dernière annee de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.geometrie_pays_id_geometrie_pays_seq OWNED BY public.geometrie_pays.id_geometrie_pays;

CREATE SEQUENCE public.entites_villes_id_entite_ville_seq;

CREATE TABLE public.entites_villes (
                id_entite_ville INTEGER NOT NULL DEFAULT nextval('public.entites_villes_id_entite_ville_seq'),
                position_ville GEOMETRY NOT NULL,
                CONSTRAINT entites_villes_pk PRIMARY KEY (id_entite_ville)
);


ALTER SEQUENCE public.entites_villes_id_entite_ville_seq OWNED BY public.entites_villes.id_entite_ville;

CREATE SEQUENCE public.populations_villes_id_pop_ville_seq;

CREATE TABLE public.Populations_villes (
                id_pop_ville INTEGER NOT NULL DEFAULT nextval('public.populations_villes_id_pop_ville_seq'),
                id_entite_ville INTEGER NOT NULL,
                population INTEGER NOT NULL,
                annee SMALLINT DEFAULT -30000 NOT NULL,
                CONSTRAINT populations_villes_pk PRIMARY KEY (id_pop_ville)
);


ALTER SEQUENCE public.populations_villes_id_pop_ville_seq OWNED BY public.Populations_villes.id_pop_ville;

CREATE SEQUENCE public.pays_ville_id_pays_ville_seq;

CREATE SEQUENCE public.pays_ville_id_entite_pays_seq;

CREATE TABLE public.pays_ville (
                id_pays_ville INTEGER NOT NULL DEFAULT nextval('public.pays_ville_id_pays_ville_seq'),
                id_entite_pays INTEGER NOT NULL DEFAULT nextval('public.pays_ville_id_entite_pays_seq'),
                id_entite_ville INTEGER NOT NULL,
                annee_debut SMALLINT DEFAULT -30000 NOT NULL,
                annee_fin SMALLINT DEFAULT -30000 NOT NULL,
                CONSTRAINT pays_ville_pk PRIMARY KEY (id_pays_ville)
);
COMMENT ON COLUMN public.pays_ville.id_pays_ville IS 'Pays dans lequel est situé une ville';
COMMENT ON COLUMN public.pays_ville.annee_debut IS '-30000 = première annee de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.pays_ville.annee_fin IS '-30000 = dernière annee de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.pays_ville_id_pays_ville_seq OWNED BY public.pays_ville.id_pays_ville;

ALTER SEQUENCE public.pays_ville_id_entite_pays_seq OWNED BY public.pays_ville.id_entite_pays;

CREATE SEQUENCE public.capitales_id_capitale_seq;

CREATE TABLE public.capitales (
                id_capitale INTEGER NOT NULL DEFAULT nextval('public.capitales_id_capitale_seq'),
                id_pays_ville INTEGER NOT NULL,
                annee_debut SMALLINT DEFAULT -30000 NOT NULL,
                annee_fin SMALLINT DEFAULT -30000 NOT NULL,
                CONSTRAINT capitales_pk PRIMARY KEY (id_capitale)
);
COMMENT ON COLUMN public.capitales.id_pays_ville IS 'Pays dans lequel est situé une ville';
COMMENT ON COLUMN public.capitales.annee_debut IS '-30000 = première annee de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.capitales.annee_fin IS '-30000 = dernière annee de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.capitales_id_capitale_seq OWNED BY public.capitales.id_capitale;

CREATE SEQUENCE public.existence_ville_id_existence_ville_seq;

CREATE TABLE public.existence_ville (
                id_existence_ville INTEGER NOT NULL DEFAULT nextval('public.existence_ville_id_existence_ville_seq'),
                id_entite_ville INTEGER NOT NULL,
                annee_debut SMALLINT DEFAULT -30000 NOT NULL,
                annee_fin SMALLINT DEFAULT -30000 NOT NULL,
                CONSTRAINT existence_ville_pk PRIMARY KEY (id_existence_ville)
);
COMMENT ON TABLE public.existence_ville IS 'Intervalles d''existence de l''entité "ville" en tant que position géographique';
COMMENT ON COLUMN public.existence_ville.annee_debut IS '-30000 = première annee de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.existence_ville.annee_fin IS '-30000 = dernière annee de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.existence_ville_id_existence_ville_seq OWNED BY public.existence_ville.id_existence_ville;

CREATE SEQUENCE public.noms_villes_id_nom_ville_seq;

CREATE TABLE public.noms_villes (
                id_nom_ville INTEGER NOT NULL DEFAULT nextval('public.noms_villes_id_nom_ville_seq'),
                nom_ville VARCHAR(128) NOT NULL,
                CONSTRAINT noms_villes_pk PRIMARY KEY (id_nom_ville)
);
COMMENT ON TABLE public.noms_villes IS 'Nomenclature des noms de ville';
COMMENT ON COLUMN public.noms_villes.nom_ville IS 'Nom de ville le plus long : 105 lettres';


ALTER SEQUENCE public.noms_villes_id_nom_ville_seq OWNED BY public.noms_villes.id_nom_ville;

CREATE SEQUENCE public.ville_id_ville_seq;

CREATE TABLE public.ville (
                id_ville INTEGER NOT NULL DEFAULT nextval('public.ville_id_ville_seq'),
                id_nom_ville INTEGER NOT NULL,
                id_entite_ville INTEGER NOT NULL,
                annee_debut SMALLINT DEFAULT -30000 NOT NULL,
                annee_fin SMALLINT DEFAULT -30000 NOT NULL,
                wikipedia VARCHAR(256),
                sources VARCHAR(2048),
                CONSTRAINT ville_pk PRIMARY KEY (id_ville)
);
COMMENT ON COLUMN public.ville.annee_debut IS '-30000 = première annee de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.ville.annee_fin IS '-30000 = dernière annee de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.ville_id_ville_seq OWNED BY public.ville.id_ville;

CREATE SEQUENCE public.noms_pays_id_nom_pays_seq;

CREATE TABLE public.noms_pays (
                id_nom_pays INTEGER NOT NULL DEFAULT nextval('public.noms_pays_id_nom_pays_seq'),
                nom_pays VARCHAR(256) NOT NULL,
                CONSTRAINT noms_pays_pk PRIMARY KEY (id_nom_pays)
);
COMMENT ON TABLE public.noms_pays IS 'Nomenclature des noms de pays';
COMMENT ON COLUMN public.noms_pays.nom_pays IS 'Nom de pays le plus long : 168 lettres';


ALTER SEQUENCE public.noms_pays_id_nom_pays_seq OWNED BY public.noms_pays.id_nom_pays;

CREATE SEQUENCE public.pays_id_pays_seq;

CREATE TABLE public.pays (
                id_pays INTEGER NOT NULL DEFAULT nextval('public.pays_id_pays_seq'),
                id_nom_pays INTEGER NOT NULL,
                id_entite_pays INTEGER NOT NULL,
                annee_debut SMALLINT DEFAULT -30000 NOT NULL,
                annee_fin SMALLINT DEFAULT -30000 NOT NULL,
                proto_etat BOOLEAN DEFAULT false NOT NULL,
                wikipedia VARCHAR(256),
                sources VARCHAR(2048),
                CONSTRAINT pays_pk PRIMARY KEY (id_pays)
);
COMMENT ON COLUMN public.pays.annee_debut IS '-30000 = première annee de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.pays.annee_fin IS '-30000 = dernière annee de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.pays_id_pays_seq OWNED BY public.pays.id_pays;

ALTER TABLE public.pays_ville ADD CONSTRAINT entite_pays_pays_ville_fk
FOREIGN KEY (id_entite_pays)
REFERENCES public.entites_pays (id_entite_pays)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.pays ADD CONSTRAINT entite_pays_pays_fk
FOREIGN KEY (id_entite_pays)
REFERENCES public.entites_pays (id_entite_pays)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.geometrie_pays ADD CONSTRAINT entite_pays_existence_pays_fk
FOREIGN KEY (id_entite_pays)
REFERENCES public.entites_pays (id_entite_pays)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.Populations_pays ADD CONSTRAINT entites_pays_populations_pays_fk
FOREIGN KEY (id_entite_pays)
REFERENCES public.entites_pays (id_entite_pays)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.ville ADD CONSTRAINT ville_physique_ville_fk
FOREIGN KEY (id_entite_ville)
REFERENCES public.entites_villes (id_entite_ville)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.existence_ville ADD CONSTRAINT ville_virtuelle_existence_ville_fk
FOREIGN KEY (id_entite_ville)
REFERENCES public.entites_villes (id_entite_ville)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.pays_ville ADD CONSTRAINT entites_villes_pays_ville_fk
FOREIGN KEY (id_entite_ville)
REFERENCES public.entites_villes (id_entite_ville)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.Populations_villes ADD CONSTRAINT entites_villes_populations_villes_fk
FOREIGN KEY (id_entite_ville)
REFERENCES public.entites_villes (id_entite_ville)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.capitales ADD CONSTRAINT pays_ville_capitales_fk
FOREIGN KEY (id_pays_ville)
REFERENCES public.pays_ville (id_pays_ville)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.ville ADD CONSTRAINT noms_villes_ville_fk
FOREIGN KEY (id_nom_ville)
REFERENCES public.noms_villes (id_nom_ville)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.pays ADD CONSTRAINT noms_pays_pays_fk
FOREIGN KEY (id_nom_pays)
REFERENCES public.noms_pays (id_nom_pays)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

DO $$
DECLARE
    nom_table TEXT;
BEGIN
    FOR nom_table IN
        SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = 'public' AND table_type = 'BASE TABLE'
    LOOP
        EXECUTE format('ALTER TABLE %I ADD hash_column VARCHAR DEFAULT 0 NOT NULL', nom_table);
    END LOOP;
END;
$$;

-- Création de la fonction helper
CREATE OR REPLACE FUNCTION generate_md5_hash()
RETURNS TRIGGER AS $$
DECLARE
    concatenated_values TEXT;
    nom_col TEXT;
    column_value TEXT;
BEGIN
    concatenated_values := '';

    -- Boucle sur les colonnes de la ligne NEW
    FOR nom_col IN SELECT column_name FROM information_schema.columns WHERE table_name = TG_TABLE_NAME LOOP
        -- Récupération de la valeur de la colonne (null-safe)
        IF NOT nom_col = 'hash_column' THEN
          EXECUTE format('SELECT ($1).%I::TEXT', nom_col) INTO column_value USING NEW;
          concatenated_values := concatenated_values || COALESCE(column_value, '');
        END IF;
    END LOOP;

    -- Calcul du hash MD5
    NEW.hash_column := MD5(concatenated_values);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Creation des triggers
DO $$
DECLARE
    nom_table TEXT;
BEGIN
    FOR nom_table IN
        SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = 'public' AND table_type = 'BASE TABLE'
    LOOP
		EXECUTE format('
			CREATE OR REPLACE TRIGGER trigger_md5_%I
			BEFORE INSERT ON %I
			FOR EACH ROW
			EXECUTE FUNCTION generate_md5_hash();',
			nom_table, nom_table);
    END LOOP;
END;
$$;