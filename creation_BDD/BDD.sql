CREATE EXTENSION postgis;

CREATE SEQUENCE public.utilisateurs_id_utilisateur_seq;

CREATE TABLE public.utilisateurs (
                id_utilisateur INTEGER NOT NULL DEFAULT nextval('public.utilisateurs_id_utilisateur_seq'),
                pseudo VARCHAR(32) NOT NULL,
                mail VARCHAR(128) NOT NULL,
                mdp_hash VARCHAR(128) NOT NULL,
                niveau_admin INTEGER DEFAULT 1 NOT NULL,
                crc_utilisateurs INTEGER DEFAULT 0 NOT NULL,
                CONSTRAINT utilisateurs_pk PRIMARY KEY (id_utilisateur)
);


ALTER SEQUENCE public.utilisateurs_id_utilisateur_seq OWNED BY public.utilisateurs.id_utilisateur;

CREATE SEQUENCE public.entites_pays_id_entite_pays_seq;

CREATE TABLE public.entites_pays (
                id_entite_pays INTEGER NOT NULL DEFAULT nextval('public.entites_pays_id_entite_pays_seq'),
                couleur VARCHAR(8) NOT NULL,
                crc_entites_pays INTEGER DEFAULT 0 NOT NULL,
                CONSTRAINT entites_pays_pk PRIMARY KEY (id_entite_pays)
);


ALTER SEQUENCE public.entites_pays_id_entite_pays_seq OWNED BY public.entites_pays.id_entite_pays;

CREATE SEQUENCE public.populations_pays_id_pop_pays_seq;

CREATE TABLE public.Populations_pays (
                id_pop_pays INTEGER NOT NULL DEFAULT nextval('public.populations_pays_id_pop_pays_seq'),
                id_entite_pays INTEGER NOT NULL,
                population INTEGER NOT NULL,
                date SMALLINT DEFAULT -30000 NOT NULL,
                crc_pop_pays INTEGER DEFAULT 0 NOT NULL,
                CONSTRAINT populations_pays_pk PRIMARY KEY (id_pop_pays)
);


ALTER SEQUENCE public.populations_pays_id_pop_pays_seq OWNED BY public.Populations_pays.id_pop_pays;

CREATE SEQUENCE public.geometrie_pays_id_geometrie_pays_seq;

CREATE TABLE public.geometrie_pays (
                id_geometrie_pays INTEGER NOT NULL DEFAULT nextval('public.geometrie_pays_id_geometrie_pays_seq'),
                id_entite_pays INTEGER NOT NULL,
                date_debut SMALLINT DEFAULT -30000 NOT NULL,
                date_fin SMALLINT DEFAULT -30000 NOT NULL,
                geometry GEOMETRY NOT NULL,
                id_modif INTEGER,
                crc_geometrie_pays INTEGER DEFAULT 0 NOT NULL,
                CONSTRAINT geometrie_pays_pk PRIMARY KEY (id_geometrie_pays)
);
COMMENT ON COLUMN public.geometrie_pays.date_debut IS '-30000 = première date de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.geometrie_pays.date_fin IS '-30000 = dernière date de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.geometrie_pays_id_geometrie_pays_seq OWNED BY public.geometrie_pays.id_geometrie_pays;

CREATE SEQUENCE public.entites_villes_id_entite_ville_seq;

CREATE TABLE public.entites_villes (
                id_entite_ville INTEGER NOT NULL DEFAULT nextval('public.entites_villes_id_entite_ville_seq'),
                position_ville GEOMETRY NOT NULL,
                crc_entites_villes INTEGER NOT NULL,
                CONSTRAINT entites_villes_pk PRIMARY KEY (id_entite_ville)
);


ALTER SEQUENCE public.entites_villes_id_entite_ville_seq OWNED BY public.entites_villes.id_entite_ville;

CREATE SEQUENCE public.populations_villes_id_pop_ville_seq;

CREATE TABLE public.Populations_villes (
                id_pop_ville INTEGER NOT NULL DEFAULT nextval('public.populations_villes_id_pop_ville_seq'),
                id_entite_ville INTEGER NOT NULL,
                population INTEGER NOT NULL,
                date SMALLINT DEFAULT -30000 NOT NULL,
                crc_pop_ville INTEGER DEFAULT 0 NOT NULL,
                CONSTRAINT populations_villes_pk PRIMARY KEY (id_pop_ville)
);


ALTER SEQUENCE public.populations_villes_id_pop_ville_seq OWNED BY public.Populations_villes.id_pop_ville;

CREATE SEQUENCE public.pays_ville_id_pays_ville_seq;

CREATE SEQUENCE public.pays_ville_id_entite_pays_seq;

CREATE TABLE public.pays_ville (
                id_pays_ville INTEGER NOT NULL DEFAULT nextval('public.pays_ville_id_pays_ville_seq'),
                id_entite_pays INTEGER NOT NULL DEFAULT nextval('public.pays_ville_id_entite_pays_seq'),
                id_entite_ville INTEGER NOT NULL,
                est_capitale BOOLEAN DEFAULT false NOT NULL,
                date_debut SMALLINT DEFAULT -30000 NOT NULL,
                date_fin SMALLINT DEFAULT -30000 NOT NULL,
                crc_pays_ville INTEGER DEFAULT 0 NOT NULL,
                CONSTRAINT pays_ville_pk PRIMARY KEY (id_pays_ville)
);
COMMENT ON COLUMN public.pays_ville.id_pays_ville IS 'Pays dans lequel est situé une ville';
COMMENT ON COLUMN public.pays_ville.date_debut IS '-30000 = première date de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.pays_ville.date_fin IS '-30000 = dernière date de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.pays_ville_id_pays_ville_seq OWNED BY public.pays_ville.id_pays_ville;

ALTER SEQUENCE public.pays_ville_id_entite_pays_seq OWNED BY public.pays_ville.id_entite_pays;

CREATE SEQUENCE public.capitales_id_capitale_seq;

CREATE TABLE public.capitales (
                id_capitale INTEGER NOT NULL DEFAULT nextval('public.capitales_id_capitale_seq'),
                id_pays_ville INTEGER NOT NULL,
                date_debut SMALLINT DEFAULT -30000 NOT NULL,
                date_fin SMALLINT DEFAULT -30000 NOT NULL,
                crc_capitales INTEGER DEFAULT 0 NOT NULL,
                CONSTRAINT capitales_pk PRIMARY KEY (id_capitale)
);
COMMENT ON COLUMN public.capitales.id_pays_ville IS 'Pays dans lequel est situé une ville';
COMMENT ON COLUMN public.capitales.date_debut IS '-30000 = première date de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.capitales.date_fin IS '-30000 = dernière date de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.capitales_id_capitale_seq OWNED BY public.capitales.id_capitale;

CREATE SEQUENCE public.existence_ville_id_existence_ville_seq;

CREATE TABLE public.existence_ville (
                id_existence_ville INTEGER NOT NULL DEFAULT nextval('public.existence_ville_id_existence_ville_seq'),
                id_entite_ville INTEGER NOT NULL,
                date_debut SMALLINT DEFAULT -30000 NOT NULL,
                date_fin SMALLINT DEFAULT -30000 NOT NULL,
                crc_existence_ville INTEGER DEFAULT 0 NOT NULL,
                CONSTRAINT existence_ville_pk PRIMARY KEY (id_existence_ville)
);
COMMENT ON TABLE public.existence_ville IS 'Intervalles d''existence de l''entité "ville" en tant que position géographique';
COMMENT ON COLUMN public.existence_ville.date_debut IS '-30000 = première date de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.existence_ville.date_fin IS '-30000 = dernière date de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.existence_ville_id_existence_ville_seq OWNED BY public.existence_ville.id_existence_ville;

CREATE SEQUENCE public.noms_villes_id_nom_ville_seq;

CREATE TABLE public.noms_villes (
                id_nom_ville INTEGER NOT NULL DEFAULT nextval('public.noms_villes_id_nom_ville_seq'),
                nom_ville VARCHAR(128) NOT NULL,
                crc_noms_villes INTEGER DEFAULT 0 NOT NULL,
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
                date_debut SMALLINT DEFAULT -30000 NOT NULL,
                date_fin SMALLINT DEFAULT -30000 NOT NULL,
                wikipedia VARCHAR(256),
                sources VARCHAR(2048),
                crc_villes INTEGER DEFAULT 0 NOT NULL,
                CONSTRAINT ville_pk PRIMARY KEY (id_ville)
);
COMMENT ON COLUMN public.ville.date_debut IS '-30000 = première date de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.ville.date_fin IS '-30000 = dernière date de la simulation (+inf)
ex : 2022';


ALTER SEQUENCE public.ville_id_ville_seq OWNED BY public.ville.id_ville;

CREATE SEQUENCE public.noms_pays_id_nom_pays_seq;

CREATE TABLE public.noms_pays (
                id_nom_pays INTEGER NOT NULL DEFAULT nextval('public.noms_pays_id_nom_pays_seq'),
                nom_pays VARCHAR(256) NOT NULL,
                crc_noms_pays INTEGER DEFAULT 0 NOT NULL,
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
                date_debut SMALLINT DEFAULT -30000 NOT NULL,
                date_fin SMALLINT DEFAULT -30000 NOT NULL,
                wikipedia VARCHAR(256),
                sources VARCHAR(2048),
                crc_pays INTEGER DEFAULT 0 NOT NULL,
                CONSTRAINT pays_pk PRIMARY KEY (id_pays)
);
COMMENT ON COLUMN public.pays.date_debut IS '-30000 = première date de la simulation (-inf)
ex : -3000';
COMMENT ON COLUMN public.pays.date_fin IS '-30000 = dernière date de la simulation (+inf)
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
