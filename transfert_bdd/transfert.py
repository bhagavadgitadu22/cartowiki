# Description: This script is used to transfer data from the old database to the new database

import mysql.connector
from mysql.connector import Error
import psycopg2
import json
import datetime

#Variables globales
# connection_old_database = None
# connection_new_database = None
noms_pays = []
noms_villes = []
entites_pays = []
entites_villes = []
geometrie_pays = []
population_pays = []
existence_villes = []
population_villes = []
sources_pays = []
sources_villes = []
wikipedia_pays = []
wikipedia_villes = []
est_capitale = []

def connect_to_my_sql_database():
    """Connect to the MySQL database and return the connection object

    Returns:
        connection: can be used to interact with the database
    """
    try:
        my_sql_connection = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='base_cartowiki'
        )
        return my_sql_connection
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None

def fetch_data_from_old_database(connection_old_database):
    """Gets all the data from the old database

    Args:
        connection_old_database (connection): can be used to interact with the database

    Returns:
        Rows of a table: All the data from the old database
    """

    global noms_pays, noms_villes, geometrie_pays, population_pays, existence_villes, population_villes, sources_pays, sources_villes, wikipedia_pays, wikipedia_villes, est_capitale, entites_pays, entites_villes

    cursor = connection_old_database.cursor(dictionary=True)
    #Fetch nom pays
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "nom" AND elements.type = "pays"')
    noms_pays = cursor.fetchall()

    #Fetch nom ville
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "nom" AND elements.type = "ville"')
    noms_villes = cursor.fetchall()

    #Fetch entite pays
    cursor.execute('SELECT id,couleur FROM elements WHERE type = "pays"')
    entites_pays = cursor.fetchall()

    #Fetch entite ville
    cursor.execute('SELECT id_element ,valeur, annee_debut FROM elements JOIN formes ON formes.id_element = elements.id WHERE champ = "geometry" AND type = "ville"')
    entites_villes = cursor.fetchall()

    #Fetch geometrie pays
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "geometry" AND elements.type = "pays"')
    geometrie_pays = cursor.fetchall()

    #Fetch population pays
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "population_etat" AND elements.type = "pays"')
    population_pays = cursor.fetchall()

    #Fetch existence ville
    cursor.execute('SELECT id_element, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "geometry" AND elements.type = "ville"')
    existence_villes = cursor.fetchall()
    #On a besoin que des annees des geometries pour savoir à quelles annees les villes existent. Leur géométrie est déjà dans la table entites_villes

    #Fetch population ville
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "population" AND elements.type = "ville"')
    population_villes = cursor.fetchall()

    #Fetch sources pays
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "source" AND elements.type = "pays"')
    sources_pays = cursor.fetchall()

    #Fetch sources ville
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "source" AND elements.type = "ville"')
    sources_villes = cursor.fetchall()

    #Fetch wikipedia pays
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "wikipedia" AND elements.type = "pays"')
    wikipedia_pays = cursor.fetchall()

    #Fetch wikipedia ville
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "wikipedia" AND elements.type = "ville"')
    wikipedia_villes = cursor.fetchall()

    #Fetch est_capitale
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "capitale" AND elements.type = "ville"')
    est_capitale = cursor.fetchall()

    #Fetch all data
    cursor.execute('SELECT * FROM formes JOIN elements ON formes.id_element = elements.id')
    return cursor.fetchall()

def generate_geojson_and_caracs(data):
    """Generates the geojson and caracs from the data

    Args:
        data (Rows of a table): Data from the old database

    Returns:
        (geosson,caracs): (List of geometries, List of characteristics(population, population_etat, nom, wikipedia, capitale, nomade, source, latLng))
    """
    geojson = {
        "type": "FeatureCollection",
        "features": []
    }
    caracs = {
        "population": {},
        "population_etat": {},
        "nom": {},
        "wikipedia": {},
        "capitale": {},
        "nomade": {},
        "source": {},
        "latLng": {}
    }

    for row in data:
        if row["champ"] == "geometry":
            # print(type(row["valeur"]))
            feature = {
                "type": "Feature",
                "properties": {
                    "annee_debut": row["annee_debut"],
                    "annee_fin": row["annee_fin"],
                    "couleur": row["couleur"],
                    "type_element": row["type"],
                    "id_element": row["id_element"]
                },
                "geometry": json.loads(row["valeur"].replace('"geometry": ', ""))
            }
            geojson["features"].append(feature)

            if row["type"] == "ville":
                coor = json.loads(row["valeur"].replace('"geometry": ', ""))["coordinates"]
                if row["id_element"] not in caracs["latLng"]:
                    caracs["latLng"][row["id_element"]] = []
                caracs["latLng"][row["id_element"]].append([row["annee_debut"], row["annee_fin"], coor])
        else:
            champ = row["champ"]
            if row["id_element"] not in caracs[champ]:
                caracs[champ][row["id_element"]] = []
            if champ in ["nom", "source", "wikipedia"]:
                caracs[champ][row["id_element"]].append([row["annee_debut"], row["annee_fin"], row["valeur"]])
            else:
                caracs[champ][row["id_element"]].append([row["annee_debut"], row["annee_fin"], row["valeur"]])

    return geojson, caracs

def sort_caracs(caracs):
    """It is supposed to sort the cararcs but I don't know how and if it works

    Args:
        caracs (List of characteristics): (population, population_etat, nom, wikipedia, capitale, nomade, source, latLng)
    """
    for key_carac, elmts_carac in caracs.items():
        for id_elmt, elmt in elmts_carac.items():
            elmt.sort(key=lambda x: x[0])

def connect_to_pgsql_database():
    """Connect to the PostgreSQL database and return the connection object

    Returns:
        connection: can be used to interact with the database
    """
    try:
        connection = psycopg2.connect(
            host="localhost",
            port="5432",
            user="Superuser",
            password="password",
            database="cartowiki"
        )
        return connection
    except Exception as error:
        print(f"Error connecting to database: {error}")
        return None

def insert_data_into_new_database(connection_new_database):
    """Inserts data into the new PostgreSQL database"""
    cursor = connection_new_database.cursor()

    global noms_pays, noms_villes, entites_pays, entites_villes, geometrie_pays, population_pays, existence_villes, population_villes, sources_pays, sources_villes, wikipedia_pays, wikipedia_villes, est_capitale

    # Remplissage de la table utilisateurs
    cursor.execute("""INSERT INTO public.utilisateurs (pseudo, mail, mdp_hash, niveau_admin) 
            VALUES ('test', 'test@hotmail.fr', 'test', TRUE) 
            RETURNING id_utilisateur
    """)
    id_utilisateur = cursor.fetchone()[0]
    print(f"Inserted 1 row into utilisateurs")

    # Créer une variable avec la date d'aujourd'hui
    date = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    # Batch insert for noms_pays : don't insert twice the same name
    # Enlever les doublons (il y en a pas on dirait)
    print(f"Before removing duplicates: {len(noms_pays)}")
    for nom in noms_pays:
        if noms_pays.count(nom) > 1:
            noms_pays.remove(nom)
            print(f"Removed {nom} from noms_pays")
            print(f"noms_pays.count(nom) = {noms_pays.count(nom)}")
    print(f"After removing duplicates: {len(noms_pays)}")


    # Remplir les table metadonnees, contributions, modifications pour noms_pays
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(noms_pays))

    # Remplir la table noms_pays
    noms_pays_values = [(row["valeur"], 0, 0,) for row in noms_pays]
    for i in range(len(noms_pays_values)):
        noms_pays_values[i] = (noms_pays_values[i][0], id_modifications[i], id_metas[i],)
    cursor.executemany("INSERT INTO public.noms_pays (nom_pays, id_modification, id_meta) VALUES (%s, %s, %s)", noms_pays_values)
    print(f"Inserted {len(noms_pays_values)} rows into noms_pays")

    # Batch insert for noms_villes : don't insert twice the same name
    # Enlever les doublons (il y en a pas on dirait)
    print(f"Before removing duplicates: {len(noms_villes)}")
    for nom in noms_villes:
        if noms_villes.count(nom) > 1:
            noms_villes.remove(nom)
            print(f"Removed {nom} from noms_villes")
            print(f"noms_villes.count(nom) = {noms_villes.count(nom)}")
    print(f"After removing duplicates: {len(noms_villes)}")

    # Remplir les table metadonnees, contributions, modifications pour noms_villes
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(noms_villes))

    # Remplir la table noms_villes
    noms_villes_values = [(row["valeur"], 0, 0) for row in noms_villes]
    for i in range(len(noms_villes_values)):
        noms_villes_values[i] = (noms_villes_values[i][0], id_modifications[i], id_metas[i],)
    cursor.executemany("""
        INSERT INTO public.noms_villes (nom_ville, id_modification, id_meta)
        VALUES (%s, %s, %s)
    """, noms_villes_values)
    print(f"Inserted {len(noms_villes_values)} rows into noms_villes")

    # Batch insert for entites_pays
    # Remplir les table metadonnees, contributions, modifications pour entites_pays
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(entites_pays))

    # Remplir la table entites_pays
    entites_pays_values = [(row["id"], row["couleur"], 0, 0,) for row in entites_pays]
    for i in range(len(entites_pays_values)):
        entites_pays_values[i] = (entites_pays_values[i][0], entites_pays_values[i][1], id_modifications[i], id_metas[i],)
    cursor.executemany("""
        INSERT INTO public.entites_pays (id_entite_pays, couleur, id_modification, id_meta)
        VALUES (%s, %s, %s, %s)
    """, entites_pays_values)
    print(f"Inserted {len(entites_pays_values)} rows into entites_pays")

    # Batch insert for entites_villes
    # https://gis.stackexchange.com/questions/108533/insert-a-point-into-postgis-using-python
    for i in range(len(entites_villes)-1, -1, -1):
        if (entites_villes[i]["valeur"] == 'null'):
            print("null : " + entites_villes[i])
        if (entites_villes[i]["id_element"] == 24):
            # S'il s'agit de la position après l'an 0 de la ville 24, on la supprime de la liste
            if (entites_villes[i]["annee_debut"]==0):
                del entites_villes[i]
                print("24 deleted from entites_villes")
        if (entites_villes[i]["id_element"] == 32):
            if (entites_villes[i]["annee_debut"]==800):
                del entites_villes[i]
                print("32 deleted from entites_villes")
        if (entites_villes[i]["id_element"] == 196):
            if (entites_villes[i]["annee_debut"]==-400):
                del entites_villes[i]
                print("196 deleted from entites_villes")
        if (entites_villes[i]["id_element"] == 310):
            if (entites_villes[i]["annee_debut"]==-29):
                del entites_villes[i]
                print("310 deleted from entites_villes")
        if (entites_villes[i]["id_element"] == 546):
            if (entites_villes[i]["annee_debut"]==1836):
                del entites_villes[i]
                print("546 deleted from entites_villes")
        if (entites_villes[i]["id_element"] == 646):
            if (entites_villes[i]["annee_debut"]==44):
                del entites_villes[i]
                print("646 deleted from entites_villes")

    # Remplir les table metadonnees, contributions, modifications pour entites_villes
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(entites_villes))

    # Remplir la table entites_villes
    entites_villes_values = [(row["id_element"], row["valeur"].replace('"geometry": ', ""), 0, 0,) for row in entites_villes]
    for i in range(len(entites_villes_values)):
        entites_villes_values[i] = (entites_villes_values[i][0], entites_villes_values[i][1], id_modifications[i], id_metas[i],)
    cursor.executemany("""
        INSERT INTO public.entites_villes (id_entite_ville, position_ville, id_modification, id_meta)
        VALUES (%s, ST_GeomFromGeoJSON(%s), %s, %s)
    """, entites_villes_values)
    print(f"Inserted {len(entites_villes_values)} rows into entites_villes")

    # Batch insert for geometrie_pays
    # Remplir la table periode
    id_periodes = insert_periodes(connection_new_database, geometrie_pays)

    # Remplir les table metadonnees, contributions, modifications pour geometrie_pays
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(geometrie_pays))

    # Remplissage de la table de géométrie
    geometrie_pays_values = [(row["id_element"], row["valeur"].replace('"geometry": ', ""),0 ,0 ,0 ,) for row in geometrie_pays]
    for i in range(len(geometrie_pays_values)):
        geometrie_pays_values[i] = (geometrie_pays_values[i][0], geometrie_pays_values[i][1], id_periodes[i], id_modifications[i], id_metas[i],)

    cursor.executemany("""
        INSERT INTO public.geometrie_pays (id_entite_pays, geometrie, id_periode, id_modification, id_meta)
        VALUES (%s, ST_GeomFromGeoJSON(%s), %s, %s, %s)
    """, geometrie_pays_values)
    print(f"Inserted {len(geometrie_pays_values)} rows into geometrie_pays")

    # Batch insert for population_pays
    # Remplir les table metadonnees, contributions, modifications pour population_pays
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(population_pays))

    # Remplir la table population_pays
    population_pays_values = [(row["id_element"], row["annee_debut"], row["valeur"], 0, 0,) for row in population_pays]
    for i in range(len(population_pays_values)):
        population_pays_values[i] = (population_pays_values[i][0], population_pays_values[i][1], population_pays_values[i][2], id_modifications[i], id_metas[i],)
    cursor.executemany("""
        INSERT INTO public.populations_pays (id_entite_pays, annee, population, id_modification, id_meta)
        VALUES (%s, %s, %s, %s, %s)
    """, population_pays_values)
    print(f"Inserted {len(population_pays_values)} rows into population_pays")

    # Batch insert for existence_villes
    # Remplir les table metadonnees, contributions, modifications pour existence_villes
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(existence_villes))

    # Remplir la table periode
    id_periodes = insert_periodes(connection_new_database, existence_villes)

    # Remplir la table existence_ville
    existence_villes_values = [(row["id_element"], 0, 0, 0,) for row in existence_villes]
    for i in range(len(existence_villes_values)):
        existence_villes_values[i] = (existence_villes_values[i][0], id_periodes[i], id_modifications[i], id_metas[i],)
    cursor.executemany("""
        INSERT INTO public.existence_ville (id_entite_ville, id_periode, id_modification, id_meta)
        VALUES (%s, %s, %s, %s)
    """, existence_villes_values)
    print(f"Inserted {len(existence_villes_values)} rows into existence_ville")
    
    for i in range(len(population_villes)-1, -1, -1):
        if (population_villes[i]["id_element"] == 837):
            del population_villes[i]
            print("837 deleted from population_villes")
        if (population_villes[i]["id_element"] == 1057):
            del population_villes[i]
            print("1057 deleted from population_villes")
        if (population_villes[i]["id_element"] == 1730):
            del population_villes[i]
            print("1730 deleted from population_villes")
    
    for i in range(len(noms_villes)-1, -1, -1):
        if (noms_villes[i]["id_element"] == 837):
            del noms_villes[i]
            print("837 deleted from noms_villes")
        if (noms_villes[i]["id_element"] == 1057):
            del noms_villes[i]
            print("1057 deleted from noms_villes")
        if (noms_villes[i]["id_element"] == 1730):
            del noms_villes[i]
            print("1730 deleted from noms_villes")
    
    for i in range(len(sources_villes)-1, -1, -1):
        if (sources_villes[i]["id_element"] == 837):
            del sources_villes[i]
            print("837 deleted from sources_villes")
        if (sources_villes[i]["id_element"] == 1057):
            del sources_villes[i]
            print("1057 deleted from sources_villes")
        if (sources_villes[i]["id_element"] == 1730):
            del sources_villes[i]
            print("1730 deleted from sources_villes")

    for i in range(len(wikipedia_villes)-1, -1, -1):
        if (wikipedia_villes[i]["id_element"] == 837):
            del wikipedia_villes[i]
            print("837 deleted from wikipedia_villes")
        if (wikipedia_villes[i]["id_element"] == 1057):
            del wikipedia_villes[i]
            print("1057 deleted from wikipedia_villes")
        if (wikipedia_villes[i]["id_element"] == 1730):
            del wikipedia_villes[i]
            print("1730 deleted from wikipedia_villes")


    # Batch insert for population_villes
    # Remplir les table metadonnees, contributions, modifications pour population_villes
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(population_villes))

    # Remplir la table population_villes
    population_villes_values = [(row["id_element"], row["annee_debut"], row["valeur"], 0, 0,) for row in population_villes]
    for i in range(len(population_villes_values)):
        population_villes_values[i] = (population_villes_values[i][0], population_villes_values[i][1], population_villes_values[i][2], id_modifications[i], id_metas[i],)
    cursor.executemany("""
        INSERT INTO public.populations_villes (id_entite_ville, annee, population, id_modification, id_meta)
        VALUES (%s, %s, %s, %s, %s)
    """, population_villes_values)
    print(f"Inserted {len(population_villes_values)} rows into population_villes")
    
    #  Fetch the noms_pays table to get the id_nom_pays according to each nom_pays
    cursor.execute('SELECT * FROM public.noms_pays')
    noms_pays_new_bdd = cursor.fetchall()
    # insert a column into noms_pays to store the id_nom_pays
    for i in range(len(noms_pays)):
        for j in range(len(noms_pays_new_bdd)):
            if (noms_pays[i]["valeur"] == noms_pays_new_bdd[j][1]):
                noms_pays[i]["id_nom_pays"] = noms_pays_new_bdd[j][0]
                break
    
    # Batch insert for pays
    # Remplir les table metadonnees, contributions, modifications pour pays
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(noms_pays))

    # Remplir la table periode
    id_periodes = insert_periodes(connection_new_database, noms_pays)

    # Remplir la table pays
    pays_values = [(row["id_element"], row["id_nom_pays"], 0, 0, 0,) for row in noms_pays]
    for i in range(len(pays_values)):
        pays_values[i] = (pays_values[i][0], pays_values[i][1], id_periodes[i], id_modifications[i], id_metas[i],)
    cursor.executemany("""
        INSERT INTO public.pays (id_entite_pays, id_nom_pays, id_periode, id_modification, id_meta)
        VALUES (%s, %s, %s, %s, %s)
    """, pays_values)
    print(f"Inserted {len(pays_values)} rows into pays")

    # Batch update for sources_pays
    sources_pays_values = [(row["valeur"], row["id_element"], row["annee_debut"], row["annee_debut"], row["annee_fin"], row["annee_fin"],) for row in sources_pays]
    query = """
        UPDATE public.contributions
        SET sources = %s
        WHERE id_meta IN (
            SELECT public.pays.id_meta
            FROM public.pays
            JOIN public.periodes ON public.pays.id_periode = public.periodes.id_periode
            WHERE public.pays.id_entite_pays = %s
            AND (
                (CAST(public.periodes.annee_debut AS int) <= %s AND CAST(public.periodes.annee_fin AS int) >= %s)
                OR (CAST(public.periodes.annee_debut AS int) <= %s AND CAST(public.periodes.annee_fin AS int) >= %s)
            )
        );
    """
    cursor.executemany(query, sources_pays_values)
    # cursor.executemany(query, sources_pays_values)
    print(f"Inserted {len(sources_pays_values)} rows into sources_pays")


    # Batch insert for wikipedia_pays
    wikipedia_pays_values = [(row["valeur"], row["id_element"], row["annee_debut"], row["annee_debut"], row["annee_fin"], row["annee_fin"],) for row in wikipedia_pays]
    cursor.executemany("""
        UPDATE public.metadonnees
        SET wikipedia = %s
        WHERE id_meta IN (
            SELECT public.pays.id_meta
            FROM public.pays
            JOIN public.periodes ON public.pays.id_periode = public.periodes.id_periode
            WHERE public.pays.id_entite_pays = %s
            AND (
                (CAST(public.periodes.annee_debut AS int) <= %s AND CAST(public.periodes.annee_fin AS int) >= %s)
                OR (CAST(public.periodes.annee_debut AS int) <= %s AND CAST(public.periodes.annee_fin AS int) >= %s)
            )
        );
    """, wikipedia_pays_values)
    print(f"Inserted {len(wikipedia_pays_values)} rows into wikipedia_pays")

    # Fetch the noms_villes table to get the id_nom_ville according to each nom_ville
    cursor.execute('SELECT * FROM public.noms_villes')
    noms_villes_new_bdd = cursor.fetchall()
    # insert a column into noms_villes to store the id_nom_ville
    for i in range(len(noms_villes)):
        for j in range(len(noms_villes_new_bdd)):
            if (noms_villes[i]["valeur"] == noms_villes_new_bdd[j][1]):
                noms_villes[i]["id_nom_ville"] = noms_villes_new_bdd[j][0]
                break

    # Batch insert for villes
    # Remplir les table metadonnees, contributions, modifications pour villes
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(noms_villes))

    # Remplir la table periode
    id_periodes = insert_periodes(connection_new_database, noms_villes)

    # Remplir la table villes
    villes_values = [(row["id_element"], row["id_nom_ville"], 0, 0, 0, ) for row in noms_villes]
    for i in range(len(villes_values)):
        villes_values[i] = (villes_values[i][0], villes_values[i][1], id_periodes[i], id_modifications[i], id_metas[i],)
    cursor.executemany("""
        INSERT INTO public.ville (id_entite_ville, id_nom_ville, id_periode, id_modification, id_meta)
        VALUES (%s, %s, %s, %s, %s)
    """, villes_values)
    print(f"Inserted {len(villes_values)} rows into villes")

    # Batch update for sources_villes
    sources_villes_values = [(row["valeur"], row["id_element"], row["annee_debut"], row["annee_debut"], row["annee_fin"], row["annee_fin"],) for row in sources_villes]
    cursor.executemany("""
        UPDATE public.contributions
        SET sources = %s
        WHERE id_meta IN (
            SELECT public.ville.id_meta
            FROM public.ville
            JOIN public.periodes ON public.ville.id_periode = public.periodes.id_periode
            WHERE public.ville.id_entite_ville = %s
            AND (
                (CAST(public.periodes.annee_debut AS int) <= %s AND CAST(public.periodes.annee_fin AS int) >= %s)
                OR (CAST(public.periodes.annee_debut AS int) <= %s AND CAST(public.periodes.annee_fin AS int) >= %s)
            )
        );
    """, sources_villes_values)
    print(f"Inserted {len(sources_villes_values)} rows into sources_villes")

    # Batch insert for wikipedia_ville
    wikipedia_villes_values = [(row["valeur"], row["id_element"], row["annee_debut"], row["annee_debut"], row["annee_fin"], row["annee_fin"],) for row in wikipedia_villes]
    cursor.executemany("""
        UPDATE public.metadonnees
        SET wikipedia = %s
        WHERE id_meta IN (
            SELECT public.ville.id_meta
            FROM public.ville
            JOIN public.periodes ON public.ville.id_periode = public.periodes.id_periode
            WHERE public.ville.id_entite_ville = %s
            AND (
                (CAST(public.periodes.annee_debut AS int) <= %s AND CAST(public.periodes.annee_fin AS int) >= %s)
                OR (CAST(public.periodes.annee_debut AS int) <= %s AND CAST(public.periodes.annee_fin AS int) >= %s)
            )
        );
    """, wikipedia_villes_values)
    print(f"Inserted {len(wikipedia_villes_values)} rows into wikipedia_ville")

    # Batch insert pays_ville (the table where we store in which countries are the cities)
    # ST_CONTAINS
    # I will do an union of tables (entites_pays JOIN geometrie_pays) and (entites_villes JOIN existence_ville) to get the countries of the cities
    # I will do a loop on the cities and for each city I will do a loop on the countries to see if the city is in the country using ST_CONTAINS
    # I will also have to check the dates first
    cursor.execute('''
        SELECT entites_pays.id_entite_pays, entites_villes.id_entite_ville, 
        GREATEST(CAST(periodes_pays.annee_debut AS int), CAST(periodes_ville.annee_debut AS int)) AS annee_debut,
        LEAST(CAST(periodes_pays.annee_fin AS int), CAST(periodes_ville.annee_fin AS int)) AS annee_fin
        FROM public.geometrie_pays JOIN public.entites_pays ON public.geometrie_pays.id_entite_pays = public.entites_pays.id_entite_pays
        JOIN public.periodes AS periodes_pays ON public.geometrie_pays.id_periode = periodes_pays.id_periode, 
        public.existence_ville JOIN public.entites_villes ON public.existence_ville.id_entite_ville = public.entites_villes.id_entite_ville 
        JOIN public.periodes AS periodes_ville ON public.existence_ville.id_periode = periodes_ville.id_periode
        WHERE ST_CONTAINS(public.geometrie_pays.geometrie, public.entites_villes.position_ville) AND (CAST(periodes_pays.annee_debut AS int)<=CAST(periodes_ville.annee_fin AS int) AND CAST(periodes_pays.annee_fin AS int)>=CAST(periodes_ville.annee_debut AS int))
    ''')
    pays_ville = cursor.fetchall()

    # Remplir la table metadonnees, contributions, modifications pour pays_ville
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection_new_database, id_utilisateur, date, len(pays_ville))

    # Remplir la table periode pour pays_ville
    periodes = [(row[2], row[3], ) for row in pays_ville]
    id_periodes = []
    for periode in periodes:
        cursor.execute("""
            INSERT INTO public.periodes (annee_debut, annee_fin)
            VALUES (%s, %s)
            RETURNING id_periode
        """, periode)
        id_periode = cursor.fetchone()[0]
        id_periodes.append(id_periode)
    print(f"Inserted {len(periodes)} rows into periodes")

    # Remplir la table pays_ville
    pays_ville_values = [(row[0], row[1], row[2], row[3], 0, 0, 0,) for row in pays_ville]
    for i in range(len(pays_ville_values)):
        pays_ville_values[i] = (pays_ville_values[i][0], pays_ville_values[i][1], id_periodes[i], id_modifications[i], id_metas[i],)
    print(f"Inserted rows into pays_ville")

    cursor.executemany("""
        INSERT INTO public.pays_ville (id_entite_pays, id_entite_ville, id_periode, id_modification, id_meta)
        VALUES (%s, %s, %s, %s, %s)
    """, pays_ville_values)
    print(f"Inserted {len(pays_ville_values)} rows into pays_ville")

    # Concatener les éléments dont les id sont les mêmes et les annees se suivent parfaitement
    # Dans la table pays_ville, concaténer les élements dont les id_entite_pays et id_entite_ville sont les mêmes et les annees se suivent parfaitement
    concat_pays_ville(connection_new_database, id_utilisateur, date)

    # Batch insert for est_capitale
    find_capitales(connection_new_database, id_utilisateur, date)

    connection_new_database.commit()
    cursor.close()



def insert_metadonnees_contributions_and_modifications(connection, id_utilisateur, date, length):
    id_metas = insert_metadonnees_and_contributions(connection, id_utilisateur, date, length)

    id_modifications = insert_modifications(connection, length)

    return id_metas, id_modifications



def insert_metadonnees_and_contributions(connection, id_utilisateur, date, length):
    cursor = connection.cursor()
    # Batch insert for metadonnees
    id_metas = []
    for _ in range(length):
        cursor.execute("""
            INSERT INTO public.metadonnees DEFAULT VALUES RETURNING id_meta
        """)
        id_meta = cursor.fetchone()[0]  # Fetch the result and get the first column
        id_metas.append(id_meta)
    print(f"Inserted {length} rows into metadonnees")

    # Batch insert for contributions
    cursor.executemany("""
        INSERT INTO public.contributions (id_utilisateur, id_meta, date)
        VALUES (%s, %s, %s)
    """, [(id_utilisateur, id_metas[i], date) for i in range(length)])
    print(f"Inserted {length} rows into contributions")

    cursor.close()
    return id_metas



def insert_modifications(connection, length):
    cursor = connection.cursor()
    # Batch insert for modifications
    id_modifications = []
    for _ in range(length):
        cursor.execute("""
            INSERT INTO public.modifications DEFAULT VALUES RETURNING id_modification
        """)
        id_modification = cursor.fetchone()[0]
        id_modifications.append(id_modification)
    print(f"Inserted {length} rows into modifications")

    cursor.close()
    return id_modifications



def insert_periodes(connection, donnees_a_inserer):
    cursor = connection.cursor()
    # Batch insert for periodes
    periodes = [(row["annee_debut"], row["annee_fin"],) for row in donnees_a_inserer]
    id_periodes = []
    for periode in periodes:
        cursor.execute("""
            INSERT INTO public.periodes (annee_debut, annee_fin)
            VALUES (%s, %s)
            RETURNING id_periode
        """, periode)
        id_periode = cursor.fetchone()[0]
        id_periodes.append(id_periode)
    print(f"Inserted {len(periodes)} rows into periodes")

    cursor.close()
    return id_periodes



def concat_pays_ville(connection, id_utilisateur, date):
    cursor = connection.cursor()

    # Étape 1 : Identifier les lignes à concaténer
    cursor.execute("""
        SELECT id_pays_ville, id_entite_pays, id_entite_ville, periodes.annee_debut, periodes.annee_fin, periodes.id_periode, id_modification, id_meta
        FROM pays_ville JOIN periodes ON pays_ville.id_periode = periodes.id_periode;
    """)
    rows_to_concat = cursor.fetchall()
    print(f"Found {len(rows_to_concat)} rows to concatenate")

    # Etape 2 : Remplissage d'un dictionnaire avec les lignes à concaténer, les clés étant le combo identifiant de pays et de ville
    rows_to_concat_dict = {}
    for row in rows_to_concat:
        key = (row[1], row[2])
        if key not in rows_to_concat_dict:
            rows_to_concat_dict[key] = []
        rows_to_concat_dict[key].append(list(row))
    print(f"Filled a dictionary with {len(rows_to_concat_dict)} keys")

    # Étape 3 : Concaténation des lignes dans une nouvelle liste
    new_rows_concatenated = []
    for key, rows in rows_to_concat_dict.items():
        # Trier les lignes par annee_debut
        rows.sort(key=lambda x: x[3])

        # Concaténer les lignes dans le nouveau dictionnaire quand les dates se suivent parfaitement
        new_rows = []
        for i in range(len(rows) - 1):
            if new_rows == []:
                new_rows.append(rows[i])
            else:
                for j in range(len(new_rows)):
                    # Si la date de fin de la ligne i est égale à la date de début de la ligne j - 1, on concatène les lignes. C'est-à-dire que la date de debut de la ligne i est mise à la place de la date de debut de la ligne j
                    if (rows[i][4] == new_rows[j][3] - 1):
                        new_rows[j][3] = rows[i][3]
                        break
                    # Si la date de début de la ligne i est égale à la date de fin de la ligne j + 1, on concatène les lignes. C'est-à-dire que la date de fin de la ligne i est mise à la place de la date de fin de la ligne j
                    if (rows[i][3] == new_rows[j][4] + 1):
                        new_rows[j][4] = rows[i][4]
                        break
                    # Si on n'est rentré dans aucun de ces cas là alors on ajoute la ligne i à la liste des lignes
                    if (j == len(new_rows) - 1):
                        new_rows.append(rows[i])
        
        # Ajouter la dernière ligne
        new_rows_concatenated.append(new_rows)
    print(f"Concatenated {len(new_rows_concatenated)} rows")
    
    # Étape 4 : Mettre à jour les lignes en supprimant les anciennes lignes et en les remplaçant par les nouvelles lignes
    # Supprimer d'abord les lignes correspondantes dans les tables modifications, contributions, metadonnees et periodes
    compteur = 0
    for row in rows_to_concat:
        cursor.execute("""
            DELETE FROM pays_ville WHERE id_pays_ville = %s;
        """, (row[0],))
        cursor.execute("""
            DELETE FROM modifications WHERE id_modification = %s;
        """, (row[6],))
        cursor.execute("""
            DELETE FROM contributions WHERE id_meta = %s;
        """, (row[7],))
        cursor.execute("""
            DELETE FROM metadonnees WHERE id_meta = %s;
        """, (row[7],))
        cursor.execute("""
            DELETE FROM periodes WHERE id_periode = %s;
        """, (row[5],))
        compteur += 1
        if compteur % 1000 == 0:
            print(f"Deleted {compteur} rows from modifications, contributions, metadonnees, periodes and pays_ville")
    print(f"Deleted {len(rows_to_concat)} rows from modifications, contributions, metadonnees, periodes and pays_ville")

    # Remplir une liste avec les nouvelles lignes concaténées
    simple_list_new_rows_concatenated = []
    for rows in new_rows_concatenated:
        for row in rows:
            simple_list_new_rows_concatenated.append(row)

    # Remplir les tables modifications, contributions, metadonnees et periodes avec les nouvelles lignes
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection, id_utilisateur, date, len(simple_list_new_rows_concatenated))

    periodes = [(row[3], row[4], ) for row in simple_list_new_rows_concatenated]
    id_periodes = []
    for periode in periodes:
        cursor.execute("""
            INSERT INTO public.periodes (annee_debut, annee_fin)
            VALUES (%s, %s)
            RETURNING id_periode
        """, periode)
        id_periode = cursor.fetchone()[0]
        id_periodes.append(id_periode)
    print(f"Inserted {len(periodes)} rows into periodes")

    # Remplir la table pays_ville
    new_rows_concatenated_values = [(row[1], row[2], 0, 0, 0,) for row in simple_list_new_rows_concatenated]
    for i in range(len(new_rows_concatenated_values)):
        new_rows_concatenated_values[i] = (new_rows_concatenated_values[i][0], new_rows_concatenated_values[i][1], id_periodes[i], id_modifications[i], id_metas[i],)
    cursor.executemany("""
        INSERT INTO public.pays_ville (id_entite_pays, id_entite_ville, id_periode, id_modification, id_meta)
        VALUES (%s, %s, %s, %s, %s)
    """, new_rows_concatenated_values)
    print(f"Inserted {len(new_rows_concatenated_values)} rows into pays_ville")

    cursor.close()



def find_capitales(connection, id_utilisateur, date):
    cursor = connection.cursor()
    # Batch insert for est_capitale
    # I will do a loop on the table pays_ville
    # I will insert into the table capitales the cities that are capitals with the dates of the capitals
    # The dates will need to be contained in the dates of the cities
    first_est_capitale_values = [(row["annee_debut"], row["annee_fin"], row["id_element"], row["annee_debut"], row["annee_fin"],) for row in est_capitale]
    est_capitale_values = []
    for capitale in first_est_capitale_values:
        cursor.execute("""
            SELECT id_pays_ville, 
            GREATEST(CAST(public.periodes.annee_debut AS int), %s), 
            LEAST(CAST(public.periodes.annee_fin AS int), %s) 
            FROM public.pays_ville 
            JOIN public.periodes ON public.pays_ville.id_periode = public.periodes.id_periode
            WHERE id_entite_ville = %s AND CAST(public.periodes.annee_fin AS int) >= %s AND  CAST(public.periodes.annee_debut AS int) <= %s
        """, capitale)
        capitale_result = cursor.fetchone()
        if capitale_result is not None:
            est_capitale_values.append(capitale_result)
    print(f"Found {len(est_capitale_values)} rows for est_capitale")

    # Remplir les tables modifications, contributions, metadonnees et periodes avec les nouvelles lignes
    id_metas, id_modifications = insert_metadonnees_contributions_and_modifications(connection, id_utilisateur, date, len(est_capitale_values))

    periodes = [(row[1], row[2], ) for row in est_capitale_values]
    id_periodes = []
    for periode in periodes:
        cursor.execute("""
            INSERT INTO public.periodes (annee_debut, annee_fin)
            VALUES (%s, %s)
            RETURNING id_periode
        """, periode)
        id_periode = cursor.fetchone()[0]
        id_periodes.append(id_periode)
    print(f"Inserted {len(periodes)} rows into periodes")

    # Remplir la table capitales
    est_capitale_values = [(row[0], 0, 0, 0,) for row in est_capitale_values]
    for i in range(len(est_capitale_values)):
        est_capitale_values[i] = (est_capitale_values[i][0], id_periodes[i], id_modifications[i], id_metas[i],)
    cursor.executemany("""
        INSERT INTO public.capitales (id_pays_ville, id_periode, id_modification, id_meta)
        VALUES (%s, %s, %s, %s)
    """, est_capitale_values)
    print(f"Inserted {len(est_capitale_values)} rows into capitales")

    cursor.close()



def main():
    """Main function that calls all the other functions and fills the new database with the data from the old database
    """
    connection_old_database = connect_to_my_sql_database()
    if connection_old_database is None:
        return

    data = fetch_data_from_old_database(connection_old_database)

    # geojson, caracs = generate_geojson_and_caracs(data)
    # sort_caracs(caracs)

    connection_old_database.close()

    connection_new_database = connect_to_pgsql_database()
    if connection_new_database is None:
        return
    try:
        insert_data_into_new_database(connection_new_database)
        # concat_pays_ville(connection_new_database)
        
    finally:
        connection_new_database.close()


if __name__ == "__main__":
    main()