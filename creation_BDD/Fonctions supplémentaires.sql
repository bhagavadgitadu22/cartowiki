CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Création de la fonction de calcul de SHA256
CREATE OR REPLACE FUNCTION sha256(TEXT)
RETURNS BYTEA AS $$
SELECT digest($1, 'sha256')
$$ LANGUAGE SQL STRICT IMMUTABLE;


-- Récupère les noms des clés primaires de la table donnée
CREATE OR REPLACE FUNCTION get_primary_keys_names(
    nom_table TEXT
)
RETURNS TEXT AS $$
SELECT c.column_name
FROM information_schema.table_constraints tc 
JOIN information_schema.constraint_column_usage AS ccu USING (constraint_schema, constraint_name) 
JOIN information_schema.columns AS c ON c.table_schema = tc.constraint_schema
  AND tc.table_name = c.table_name AND ccu.column_name = c.column_name
WHERE constraint_type = 'PRIMARY KEY' and tc.table_name = nom_table;
$$ LANGUAGE SQL STRICT IMMUTABLE;

-- Concatène les valeurs des colonnes d'un RECORD, en excluant les colonnes nommées dans ignore_cols
CREATE OR REPLACE FUNCTION concatenate_record_columns(
    rec RECORD,
    ignore_cols TEXT[] DEFAULT null
)
RETURNS TEXT AS $$
DECLARE
    nom_col TEXT;
    val_col TEXT;
    result TEXT := '';
BEGIN
    -- Convertit le record en JSON et itère sur chaque paire clé/valeur
    FOR nom_col, val_col IN 
        SELECT key, value FROM json_each_text(to_json(rec))
    LOOP
        -- Si la colonne n'est pas dans la liste des colonnes à ignorer, on concatène sa valeur
        IF NOT (nom_col = ANY(ignore_cols)) THEN
            result := result || COALESCE(val_col, '');
        END IF;
    END LOOP;
    RETURN result;
END;
$$ LANGUAGE plpgsql;
