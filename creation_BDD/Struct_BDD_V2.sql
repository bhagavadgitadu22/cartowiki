CREATE EXTENSION postgis;

CREATE SEQUENCE public.periodes_id_periode_seq;

CREATE TABLE public.Periodes (
                id_periode INTEGER NOT NULL DEFAULT nextval('public.periodes_id_periode_seq'),
                annee_debut SMALLINT,
                annee_fin SMALLINT,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT periodes_pk PRIMARY KEY (id_periode)
);
COMMENT ON TABLE public.Periodes IS 'Périodes de validité des informations reliées';
COMMENT ON COLUMN public.Periodes.annee_debut IS 'Année de début de la période
-3000 = première date de la simulation a priori';
COMMENT ON COLUMN public.Periodes.annee_fin IS 'Année de fin de la période';
COMMENT ON COLUMN public.Periodes.hash_column IS 'SHA256';


ALTER SEQUENCE public.periodes_id_periode_seq OWNED BY public.Periodes.id_periode;

CREATE SEQUENCE public.modifications_id_modification_seq;

CREATE TABLE public.Modifications (
                id_modification INTEGER NOT NULL DEFAULT nextval('public.modifications_id_modification_seq'),
                nouvelle_proposition BOOLEAN DEFAULT TRUE,
                id_precedent INTEGER,
                commentaire VARCHAR,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT modifications_pk PRIMARY KEY (id_modification)
);
COMMENT ON TABLE public.Modifications IS 'Statut d''un élément (version actuelle, nouvelle proposition
TODO : Relier les lignes des différentes tables qui correspondent à une seule et même proposition
TODO : Tableau .md décrivant les différents cas de figure';
COMMENT ON COLUMN public.Modifications.nouvelle_proposition IS 'NULL : Version acceptée ; 
TRUE : Nouvelle proposition, encore jamais vérifiée ; 
FALSE : Proposition déjà vérfifiée mais à revoir.';
COMMENT ON COLUMN public.Modifications.id_precedent IS 'Identifiant de la version précédente de l''élément, si elle existe';
COMMENT ON COLUMN public.Modifications.commentaire IS 'Commentaire de l''admin en cas de refus de la modification proposée telle quelle';
COMMENT ON COLUMN public.Modifications.hash_column IS 'SHA256';


ALTER SEQUENCE public.modifications_id_modification_seq OWNED BY public.Modifications.id_modification;

CREATE SEQUENCE public.metadonnees_id_meta_seq;

CREATE TABLE public.Metadonnees (
                id_meta INTEGER NOT NULL DEFAULT nextval('public.metadonnees_id_meta_seq'),
                wikipedia VARCHAR,
                description VARCHAR,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT metadonnees_pk PRIMARY KEY (id_meta)
);
COMMENT ON TABLE public.Metadonnees IS 'Métadonnées nécessaires de chaque élément de la DB';
COMMENT ON COLUMN public.Metadonnees.wikipedia IS 'Lien wikipédia vers la page de renseignement correspondant à l''élément';
COMMENT ON COLUMN public.Metadonnees.description IS 'Description rapide de l''élément';
COMMENT ON COLUMN public.Metadonnees.hash_column IS 'SHA256';


ALTER SEQUENCE public.metadonnees_id_meta_seq OWNED BY public.Metadonnees.id_meta;

CREATE SEQUENCE public.utilisateurs_id_utilisateur_seq;

CREATE TABLE public.utilisateurs (
                id_utilisateur INTEGER NOT NULL DEFAULT nextval('public.utilisateurs_id_utilisateur_seq'),
                pseudo VARCHAR(32) NOT NULL,
                mail VARCHAR(128) NOT NULL,
                mdp_hash BYTEA NOT NULL, -- MODIF : VARCHAR(128) -> VARCHAR(60)
                niveau_admin INTEGER DEFAULT 0, -- MODIF : INTEGER DEFAULT 1 (voir COMMENT) -> BYTEA NOT NULL (erreur)
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT utilisateurs_pk PRIMARY KEY (id_utilisateur)
);
COMMENT ON TABLE public.utilisateurs IS 'Utilisateurs de la DB';
COMMENT ON COLUMN public.utilisateurs.pseudo IS 'Nom publique de l''utilisateur au sein de Cartowiki';
COMMENT ON COLUMN public.utilisateurs.mail IS 'Email de l''utilisateur, servant à le recontacter
TODO : Vérifier que c''est utile à terme';
COMMENT ON COLUMN public.utilisateurs.mdp_hash IS 'On utilisera bcrypt avec les fonctions crypt() et gen_salt(''bf'', 12) de l''extension pgcrypto';
COMMENT ON COLUMN public.utilisateurs.niveau_admin IS '0 : Contributeur ; 
1 : Admin ;
2 : Super Admin.';
COMMENT ON COLUMN public.utilisateurs.hash_column IS 'SHA256';


ALTER SEQUENCE public.utilisateurs_id_utilisateur_seq OWNED BY public.utilisateurs.id_utilisateur;

CREATE SEQUENCE public.contributions_id_contribution_seq;

CREATE TABLE public.Contributions (
                id_contribution INTEGER NOT NULL DEFAULT nextval('public.contributions_id_contribution_seq'),
                id_meta INTEGER NOT NULL,
                id_utilisateur INTEGER NOT NULL,
                date DATE DEFAULT CURRENT_DATE NOT NULL,
                sources VARCHAR,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT contributions_pk PRIMARY KEY (id_contribution)
);
COMMENT ON TABLE public.Contributions IS 'Contributions des utilisateurs aux éléments de la DB';
COMMENT ON COLUMN public.Contributions.id_meta IS 'Lien vers les métadonnées et l''élément auquel correspond la contribution';
COMMENT ON COLUMN public.Contributions.id_utilisateur IS 'Utilisateur ayant proposé la contribution';
COMMENT ON COLUMN public.Contributions.date IS 'Date de contibution';
COMMENT ON COLUMN public.Contributions.sources IS 'Sources ayant servi à une contribution';
COMMENT ON COLUMN public.Contributions.hash_column IS 'SHA256';


ALTER SEQUENCE public.contributions_id_contribution_seq OWNED BY public.Contributions.id_contribution;

CREATE SEQUENCE public.entites_pays_id_entite_pays_seq;

CREATE TABLE public.entites_pays (
                id_entite_pays INTEGER NOT NULL DEFAULT nextval('public.entites_pays_id_entite_pays_seq'),
                couleur VARCHAR(8) NOT NULL, -- MODIF : VARCHAR(8)
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT entites_pays_pk PRIMARY KEY (id_entite_pays)
);
COMMENT ON TABLE public.entites_pays IS 'Coeur des caractéristiques d''un pays dans Cartowiki, ne dépendant ni du temps ni de l''espace';
COMMENT ON COLUMN public.entites_pays.couleur IS 'Couleur d''affichage du pays sur la carte
Conversion depuis Hexa : INSERT INTO entites_pays (couleur) VALUES (0xRRGGBB)
Conversion en Hexa : SELECT couleur, lpad(to_hex(couleur), 6, ''0'') AS hex FROM entites_pays';
COMMENT ON COLUMN public.entites_pays.hash_column IS 'SHA256';


ALTER SEQUENCE public.entites_pays_id_entite_pays_seq OWNED BY public.entites_pays.id_entite_pays;

CREATE SEQUENCE public.populations_pays_id_pop_pays_seq;

CREATE TABLE public.Populations_pays (
                id_pop_pays INTEGER NOT NULL DEFAULT nextval('public.populations_pays_id_pop_pays_seq'),
                id_entite_pays INTEGER NOT NULL,
                population INTEGER NOT NULL,
                annee SMALLINT NOT NULL, -- MODIF : DEFAULT -30000
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT populations_pays_pk PRIMARY KEY (id_pop_pays)
);
COMMENT ON TABLE public.Populations_pays IS 'Population du pays en une année donnée';
COMMENT ON COLUMN public.Populations_pays.id_entite_pays IS 'Pays concerné';
COMMENT ON COLUMN public.Populations_pays.population IS 'Population relevée';
COMMENT ON COLUMN public.Populations_pays.annee IS 'Année de l''estimation de la population';
COMMENT ON COLUMN public.Populations_pays.hash_column IS 'SHA256';


ALTER SEQUENCE public.populations_pays_id_pop_pays_seq OWNED BY public.Populations_pays.id_pop_pays;

CREATE SEQUENCE public.geometrie_pays_id_geometrie_pays_seq;

CREATE TABLE public.geometrie_pays (
                id_geometrie_pays INTEGER NOT NULL DEFAULT nextval('public.geometrie_pays_id_geometrie_pays_seq'),
                id_entite_pays INTEGER NOT NULL,
                geometrie geometry(Geometry, 3857) NOT NULL,-- SRID 3857
                id_periode INTEGER NOT NULL, -- MODIF :)
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT geometrie_pays_pk PRIMARY KEY (id_geometrie_pays)
);
COMMENT ON TABLE public.geometrie_pays IS 'Frontières d''un pays sur une période donnée';
COMMENT ON COLUMN public.geometrie_pays.id_entite_pays IS 'Pays concerné';
COMMENT ON COLUMN public.geometrie_pays.geometrie IS 'Géométrie des frontières du pays (type : GEOMETRY)';
COMMENT ON COLUMN public.geometrie_pays.hash_column IS 'SHA256';


ALTER SEQUENCE public.geometrie_pays_id_geometrie_pays_seq OWNED BY public.geometrie_pays.id_geometrie_pays;

CREATE SEQUENCE public.entites_villes_id_entite_ville_seq;

CREATE TABLE public.entites_villes (
                id_entite_ville INTEGER NOT NULL DEFAULT nextval('public.entites_villes_id_entite_ville_seq'),
                position_ville geometry(Geometry, 3857) NOT NULL,  -- SRID 3857
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT entites_villes_pk PRIMARY KEY (id_entite_ville)
);
COMMENT ON TABLE public.entites_villes IS 'Coeur des caractéristiques d''une ville dans Cartowiki, ne dépendant ni du temps ni de l''espace';
COMMENT ON COLUMN public.entites_villes.position_ville IS 'Position de la ville
On suppose qu''une ville est totalement fixe au cours du temps, se qui la définit intrinsèquement';
COMMENT ON COLUMN public.entites_villes.hash_column IS 'SHA256';


ALTER SEQUENCE public.entites_villes_id_entite_ville_seq OWNED BY public.entites_villes.id_entite_ville;

CREATE SEQUENCE public.populations_villes_id_pop_ville_seq;

CREATE TABLE public.Populations_villes (
                id_pop_ville INTEGER NOT NULL DEFAULT nextval('public.populations_villes_id_pop_ville_seq'),
                id_entite_ville INTEGER NOT NULL,
                population INTEGER NOT NULL,
                annee SMALLINT NOT NULL, -- MODIF : DEFAULT -30000
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT populations_villes_pk PRIMARY KEY (id_pop_ville)
);
COMMENT ON TABLE public.Populations_villes IS 'Population d''une ville en une année donnée';
COMMENT ON COLUMN public.Populations_villes.id_entite_ville IS 'Ville concernée';
COMMENT ON COLUMN public.Populations_villes.population IS 'Population relevée';
COMMENT ON COLUMN public.Populations_villes.annee IS 'Année de l''estimation de la population';
COMMENT ON COLUMN public.Populations_villes.hash_column IS 'SHA256';


ALTER SEQUENCE public.populations_villes_id_pop_ville_seq OWNED BY public.Populations_villes.id_pop_ville;

CREATE SEQUENCE public.pays_ville_id_pays_ville_seq;

CREATE TABLE public.pays_ville (
                id_pays_ville INTEGER NOT NULL DEFAULT nextval('public.pays_ville_id_pays_ville_seq'),
                id_entite_pays INTEGER NOT NULL,
                id_entite_ville INTEGER NOT NULL,
                id_periode INTEGER NOT NULL, -- MODIF :)
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT pays_ville_pk PRIMARY KEY (id_pays_ville)
);
COMMENT ON TABLE public.pays_ville IS 'Lien définissant l''appartenance d''une ville à un pays sur une période donnée';
COMMENT ON COLUMN public.pays_ville.id_pays_ville IS 'Pays dans lequel est situé une ville';
COMMENT ON COLUMN public.pays_ville.id_entite_pays IS 'Pays lié';
COMMENT ON COLUMN public.pays_ville.id_entite_ville IS 'Ville liée';
COMMENT ON COLUMN public.pays_ville.hash_column IS 'SHA256';


ALTER SEQUENCE public.pays_ville_id_pays_ville_seq OWNED BY public.pays_ville.id_pays_ville;

CREATE SEQUENCE public.capitales_id_capitale_seq;

CREATE TABLE public.capitales (
                id_capitale INTEGER NOT NULL DEFAULT nextval('public.capitales_id_capitale_seq'),
                id_pays_ville INTEGER NOT NULL,
                id_periode INTEGER NOT NULL, -- MODIF :)
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT capitales_pk PRIMARY KEY (id_capitale)
);
COMMENT ON TABLE public.capitales IS 'Spécifie si une ville est la capitale de son pays sur une période donnée';
COMMENT ON COLUMN public.capitales.id_pays_ville IS 'Lien entre la ville et le pays dans lequel elle est capitale';
COMMENT ON COLUMN public.capitales.hash_column IS 'SHA256';


ALTER SEQUENCE public.capitales_id_capitale_seq OWNED BY public.capitales.id_capitale;

CREATE SEQUENCE public.existence_ville_id_existence_ville_seq;

CREATE TABLE public.existence_ville (
                id_existence_ville INTEGER NOT NULL DEFAULT nextval('public.existence_ville_id_existence_ville_seq'),
                id_entite_ville INTEGER NOT NULL,
                id_periode INTEGER NOT NULL, -- MODIF :)
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT existence_ville_pk PRIMARY KEY (id_existence_ville)
);
COMMENT ON TABLE public.existence_ville IS 'Intervalles d''existence de l''entité "ville" en tant que position géographique';
COMMENT ON COLUMN public.existence_ville.id_entite_ville IS 'Ville concernée';
COMMENT ON COLUMN public.existence_ville.hash_column IS 'SHA256';


ALTER SEQUENCE public.existence_ville_id_existence_ville_seq OWNED BY public.existence_ville.id_existence_ville;

CREATE SEQUENCE public.noms_villes_id_nom_ville_seq;

CREATE TABLE public.noms_villes (
                id_nom_ville INTEGER NOT NULL DEFAULT nextval('public.noms_villes_id_nom_ville_seq'),
                nom_ville VARCHAR(128) NOT NULL,
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT noms_villes_pk PRIMARY KEY (id_nom_ville)
);
COMMENT ON TABLE public.noms_villes IS 'Nomenclature des noms de ville';
COMMENT ON COLUMN public.noms_villes.nom_ville IS 'Nom de ville le plus long : 105 lettres';
COMMENT ON COLUMN public.noms_villes.hash_column IS 'SHA256';


ALTER SEQUENCE public.noms_villes_id_nom_ville_seq OWNED BY public.noms_villes.id_nom_ville;

CREATE SEQUENCE public.ville_id_ville_seq;

CREATE TABLE public.ville (
                id_ville INTEGER NOT NULL DEFAULT nextval('public.ville_id_ville_seq'),
                id_nom_ville INTEGER NOT NULL,
                id_entite_ville INTEGER NOT NULL,
                id_periode INTEGER NOT NULL, -- MODIF :)
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT ville_pk PRIMARY KEY (id_ville)
);
COMMENT ON TABLE public.ville IS 'Nom d''une ville sur une période donnée';
COMMENT ON COLUMN public.ville.id_nom_ville IS 'Nom de la ville';
COMMENT ON COLUMN public.ville.id_entite_ville IS 'Ville concernée';
COMMENT ON COLUMN public.ville.hash_column IS 'SHA256';


ALTER SEQUENCE public.ville_id_ville_seq OWNED BY public.ville.id_ville;

CREATE SEQUENCE public.noms_pays_id_nom_pays_seq;

CREATE TABLE public.noms_pays (
                id_nom_pays INTEGER NOT NULL DEFAULT nextval('public.noms_pays_id_nom_pays_seq'),
                nom_pays VARCHAR(256) NOT NULL,
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT noms_pays_pk PRIMARY KEY (id_nom_pays)
);
COMMENT ON TABLE public.noms_pays IS 'Nomenclature des noms de pays';
COMMENT ON COLUMN public.noms_pays.nom_pays IS 'Nom de pays le plus long : 168 lettres';
COMMENT ON COLUMN public.noms_pays.hash_column IS 'SHA256';


ALTER SEQUENCE public.noms_pays_id_nom_pays_seq OWNED BY public.noms_pays.id_nom_pays;

CREATE SEQUENCE public.pays_id_pays_seq;

CREATE TABLE public.pays (
                id_pays INTEGER NOT NULL DEFAULT nextval('public.pays_id_pays_seq'),
                id_nom_pays INTEGER NOT NULL,
                id_entite_pays INTEGER NOT NULL,
                proto_etat BOOLEAN DEFAULT false NOT NULL,
                id_periode INTEGER NOT NULL, -- MODIF :)
                id_meta INTEGER NOT NULL,
                id_modification INTEGER NOT NULL,
                hash_column BYTEA, -- MODIF : VARCHAR(32) DEFAULT 0 NOT NULL
                CONSTRAINT pays_pk PRIMARY KEY (id_pays)
);
COMMENT ON TABLE public.pays IS 'Nom et type d''un pays (proto-état ou non) sur une période donnée';
COMMENT ON COLUMN public.pays.id_nom_pays IS 'Nom du pays dans la nomenclature des noms';
COMMENT ON COLUMN public.pays.id_entite_pays IS 'Pays concerné';
COMMENT ON COLUMN public.pays.proto_etat IS 'Spécifie si un pays est un proto-état';
COMMENT ON COLUMN public.pays.hash_column IS 'SHA256';


ALTER SEQUENCE public.pays_id_pays_seq OWNED BY public.pays.id_pays;

ALTER TABLE public.pays ADD CONSTRAINT periodes_pays_fk
FOREIGN KEY (id_periode)
REFERENCES public.Periodes (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.geometrie_pays ADD CONSTRAINT periodes_geometrie_pays_fk
FOREIGN KEY (id_periode)
REFERENCES public.Periodes (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.existence_ville ADD CONSTRAINT periodes_existence_ville_fk
FOREIGN KEY (id_periode)
REFERENCES public.Periodes (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.ville ADD CONSTRAINT periodes_ville_fk
FOREIGN KEY (id_periode)
REFERENCES public.Periodes (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.capitales ADD CONSTRAINT periodes_capitales_fk
FOREIGN KEY (id_periode)
REFERENCES public.Periodes (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.pays_ville ADD CONSTRAINT periodes_pays_ville_fk
FOREIGN KEY (id_periode)
REFERENCES public.Periodes (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.pays ADD CONSTRAINT modifications_pays_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.noms_pays ADD CONSTRAINT modifications_noms_pays_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.entites_pays ADD CONSTRAINT modifications_entites_pays_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.geometrie_pays ADD CONSTRAINT modifications_geometrie_pays_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.capitales ADD CONSTRAINT modifications_capitales_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.pays_ville ADD CONSTRAINT modifications_pays_ville_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.Populations_pays ADD CONSTRAINT modifications_populations_pays_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.Populations_villes ADD CONSTRAINT modifications_populations_villes_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.noms_villes ADD CONSTRAINT modifications_noms_villes_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.ville ADD CONSTRAINT modifications_ville_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.entites_villes ADD CONSTRAINT modifications_entites_villes_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.existence_ville ADD CONSTRAINT modifications_existence_ville_fk
FOREIGN KEY (id_modification)
REFERENCES public.Modifications (id_modification)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.noms_pays ADD CONSTRAINT metadonnees_noms_pays_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.pays ADD CONSTRAINT metadonnees_pays_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.entites_pays ADD CONSTRAINT metadonnees_entites_pays_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.Contributions ADD CONSTRAINT metadonnees_contributions_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.capitales ADD CONSTRAINT metadonnees_capitales_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.geometrie_pays ADD CONSTRAINT metadonnees_geometrie_pays_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.Populations_pays ADD CONSTRAINT metadonnees_populations_pays_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.pays_ville ADD CONSTRAINT metadonnees_pays_ville_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.Populations_villes ADD CONSTRAINT metadonnees_populations_villes_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.existence_ville ADD CONSTRAINT metadonnees_existence_ville_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.entites_villes ADD CONSTRAINT metadonnees_entites_villes_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.ville ADD CONSTRAINT metadonnees_ville_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.noms_villes ADD CONSTRAINT metadonnees_noms_villes_fk
FOREIGN KEY (id_meta)
REFERENCES public.Metadonnees (id_meta)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.Contributions ADD CONSTRAINT utilisateurs_contributions_fk
FOREIGN KEY (id_utilisateur)
REFERENCES public.utilisateurs (id_utilisateur)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

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
