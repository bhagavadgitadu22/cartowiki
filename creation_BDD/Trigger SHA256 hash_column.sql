CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Création de la fonction de calcul de SHA256
CREATE OR REPLACE FUNCTION sha256(TEXT)
RETURNS BYTEA AS $$
SELECT digest($1, 'sha256')
$$ LANGUAGE SQL STRICT IMMUTABLE;


-- Création de la fonction helper
CREATE OR REPLACE FUNCTION generate_sha256_hash_column()
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
        IF nom_col <> 'hash_column' THEN
          EXECUTE format('SELECT ($1).%I::TEXT', nom_col) INTO column_value USING NEW;
          concatenated_values := concatenated_values || COALESCE(column_value, '');
        END IF;
    END LOOP;

    -- Calcul du hash SHA256
    NEW.hash_column := sha256(concatenated_values);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


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
