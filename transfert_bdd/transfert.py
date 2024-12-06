# Description: This script is used to transfer data from the old database to the new database

import mysql.connector
from mysql.connector import Error
import psycopg2
import json

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

    global noms_pays, noms_villes, geometrie_pays, population_pays, existence_villes, population_villes, sources_pays, sources_villes, est_capitale, entites_pays, entites_villes

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
    cursor.execute('SELECT id_element ,valeur FROM elements JOIN formes ON formes.id_element = elements.id WHERE champ = "geometry" AND type = "ville"')
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
    #On a besoin que des dates des geometries pour savoir à quelles dates les villes existent. Leur géométrie est déjà dans la table entites_villes

    #Fetch population ville
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "population" AND elements.type = "ville"')
    population_villes = cursor.fetchall()

    #Fetch sources pays
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "source" AND elements.type = "pays"')
    sources_pays = cursor.fetchall()

    #Fetch sources ville
    cursor.execute('SELECT id_element, valeur, annee_debut, annee_fin FROM formes JOIN elements ON formes.id_element = elements.id WHERE champ = "source" AND elements.type = "ville"')
    sources_villes = cursor.fetchall()

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
                "geometry": json.loads(row["valeur"].replace('"geometry": ', ""))  # This is where the error occurs
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
            database="CartoWiki"
        )
        return connection
    except Exception as error:
        print(f"Error connecting to database: {error}")
        return None

def insert_data_into_new_database(connection_new_database, geojson, caracs):
    """Inserts data into the new PostgreSQL database"""
    cursor = connection_new_database.cursor()
    # # Batch insert for noms_pays
    # noms_pays_values = [(row["valeur"],) for row in noms_pays]
    # cursor.executemany("INSERT INTO public.noms_pays (nom_pays) VALUES (%s)", noms_pays_values)
    # print(f"Inserted {len(noms_pays_values)} rows into noms_pays")
    
    # # Batch insert for noms_villes
    # noms_villes_values = [(row["valeur"],) for row in noms_villes]
    # cursor.executemany("""
    #     INSERT INTO public.noms_villes (nom_ville)
    #     VALUES (%s)
    # """, noms_villes_values)
    # print(f"Inserted {len(noms_villes_values)} rows into noms_villes")

    # Batch insert for entites_pays
    # entites_pays_values = [(row["id"], row["couleur"],) for row in entites_pays]
    # print(entites_pays_values)
    # cursor.executemany("""
    #     INSERT INTO public.entite_pays (id_entite_pays, couleur)
    #     VALUES (%s, %s)
    # """, entites_pays_values)
    # print(f"Inserted {len(entites_pays_values)} rows into entites_pays")

    # # Batch insert for entites_pays without colors //TODO: Add colors
    # entites_pays_values = [(row["id"],1) for row in entites_pays]
    # cursor.executemany("""
    #     INSERT INTO public.entite_pays (id_entite_pays, couleur)
    #     VALUES (%s, %s)
    # """, entites_pays_values)
    # print(f"Inserted {len(entites_pays_values)} rows into entites_pays")

    # Batch insert for entites_villes
    # https://gis.stackexchange.com/questions/108533/insert-a-point-into-postgis-using-python
    entites_villes_values = [(row["id_element"], row["valeur"].replace('"geometry": ', ""),0,) for row in entites_villes]
    # print(entites_villes_values)
    cursor.executemany("""
        INSERT INTO public.entites_villes (id_entite_ville, position_ville, crc_entites_villes)
        VALUES (%s, ST_GeomFromGeoJSON(%s), %s)
    """, entites_villes_values)
    print(f"Inserted {len(entites_villes_values)} rows into entites_villes")


    # for row in caracs["population"]:
    #     cursor.execute("""
    #         INSERT INTO public.population (id_element, annee_debut, annee_fin, population)
    #         VALUES (%s, %s, %s, %s)
    #     """, (row["id_element"], row["annee_debut"], row["annee_fin"], row["population"]))
    #     # Insert data into the appropriate tables

    #     # cursor.execute("""
    #     #     INSERT INTO public.utilisateurs (pseudo, mail, mdp_hash, niveau_admin, crc_utilisateurs)
    #     #     VALUES (%s, %s, %s, %s, %s)
    #     # """, (row['pseudo'], row['mail'], row['mdp_hash'], row['niveau_admin'], row['crc_utilisateurs']))
    #     # Add more insert statements for other tables as needed
    connection_new_database.commit()


def main():
    """Main function that calls all the other functions and fills the new database with the data from the old database
    """
    connection_old_database = connect_to_my_sql_database()
    if connection_old_database is None:
        return

    data = fetch_data_from_old_database(connection_old_database)
    # print('noms_pays')
    # print(noms_pays)
    # print('noms_villes')
    # print(noms_villes)
    # print('entites_pays')
    # print(entites_pays)
    # print('entites_villes')
    # print(entites_villes)
    # print('geometrie_pays')
    # print(geometrie_pays)
    # print('existence_villes')
    # print(existence_villes)
    # print('population_pays')
    # print(population_pays)
    # print('population_villes')
    # print(population_villes)
    # print('sources_pays')
    # print(sources_pays)
    # print('sources_villes')
    # print(sources_villes)
    # print('est_capitale')
    # print(est_capitale)
    # print('data')
    # print(data)

    geojson, caracs = generate_geojson_and_caracs(data)
    sort_caracs(caracs)

    # print(json.dumps(geojson, ensure_ascii=False))
    # print(';;;')
    # print(json.dumps(caracs, ensure_ascii=False))

    connection_old_database.close()

    connection_new_database = connect_to_pgsql_database()
    if connection_new_database is None:
        return
    try:
        insert_data_into_new_database(connection_new_database, geojson, caracs)
    finally:
        connection_new_database.close()


if __name__ == "__main__":
    main()