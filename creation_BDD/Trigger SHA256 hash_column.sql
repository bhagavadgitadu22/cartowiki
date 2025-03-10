CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Calcul et affecte la checksum de la ligne créée
CREATE OR REPLACE FUNCTION generate_sha256_hash_column()
RETURNS TRIGGER AS $$
DECLARE
    concatenated_values TEXT;
    sha_val BYTEA;
BEGIN
    -- Concaténer les valeurs de NEW en ignorant par défaut la colonne "hash_column"
    concatenated_values := concatenate_record_columns(NEW, ARRAY['hash_column']);
    -- RAISE NOTICE 'Valeur concaténée : %', concatenated_values; -- DEBUG
    
    -- Calcul du hash SHA256 et assignation à hash_column
    sha_val := sha256(concatenated_values);
    NEW.hash_column := sha_val;
    -- RAISE NOTICE 'Hash calculé : %', encode(sha_val, 'hex'); -- DEBUG
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Création générique de tous les triggers
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
			CREATE OR REPLACE TRIGGER trigger_sha256_checksum_%I
			BEFORE INSERT ON %I
			FOR EACH ROW
			EXECUTE FUNCTION generate_sha256_hash_column();',
			nom_table, nom_table);
    END LOOP;
END;
$$;

-- Fonction de comparaison des checksums
CREATE OR REPLACE FUNCTION check_hash_column(nom_table TEXT, row_id INTEGER)
RETURNS BOOLEAN AS $$
DECLARE
    expected_hash BYTEA;
    stored_hash BYTEA;
BEGIN
    -- Calcul du hash attendu en fonction des valeurs actuelles de la ligne
    expected_hash := sha256(concatenate_column_values(nom_table, row_id));

    -- Récupération du hash stocké dans la table
    EXECUTE format('SELECT hash_column FROM %I WHERE %I = $1', nom_table, get_primary_keys_names(nom_table))
    INTO stored_hash
    USING row_id;

    -- Comparaison des deux valeurs
    RETURN stored_hash IS NOT DISTINCT FROM expected_hash;
END;
$$ LANGUAGE plpgsql;
