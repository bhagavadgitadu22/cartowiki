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
            database="Cartowiki2"
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

    # # Batch insert for entites_pays
    # entites_pays_values = [(row["id"], row["couleur"],) for row in entites_pays]
    # cursor.executemany("""
    #     INSERT INTO public.entites_pays (id_entite_pays, couleur)
    #     VALUES (%s, %s)
    # """, entites_pays_values)
    # print(f"Inserted {len(entites_pays_values)} rows into entites_pays")

    # Batch insert for entites_villes
    # https://gis.stackexchange.com/questions/108533/insert-a-point-into-postgis-using-python
    for i in range(len(entites_villes)-1, -1, -1):
        if (entites_villes[i]["valeur"] == 'null'):
            print("null : " + entites_villes[i])
        if (entites_villes[i]["id_element"] == 24):
            print(entites_villes[i]["id_element"])
            # S'il s'agit de la position après l'an 0 de la ville 24, on la supprime de la liste
            if (entites_villes[i]["annee_debut"]==0):
                print(entites_villes[i])
                del entites_villes[i]
                print("24 deleted")
                print(entites_villes[i])
        if (entites_villes[i]["id_element"] == 32):
            print(entites_villes[i]["id_element"])
            if (entites_villes[i]["annee_debut"]==800):
                print(entites_villes[i])
                del entites_villes[i]
                print("32 deleted")
                print(entites_villes[i])
        if (entites_villes[i]["id_element"] == 196):
            print(entites_villes[i]["id_element"])
            if (entites_villes[i]["annee_debut"]==-400):
                print(entites_villes[i])
                del entites_villes[i]
                print("196 deleted")
                print(entites_villes[i])
        if (entites_villes[i]["id_element"] == 310):
            print(entites_villes[i]["id_element"])
            if (entites_villes[i]["annee_debut"]==-29):
                print(entites_villes[i])
                del entites_villes[i]
                print("310 deleted")
                print(entites_villes[i])
        if (entites_villes[i]["id_element"] == 546):
            print(entites_villes[i]["id_element"])
            if (entites_villes[i]["annee_debut"]==1836):
                print(entites_villes[i])
                del entites_villes[i]
                print("546 deleted")
                print(entites_villes[i])
        if (entites_villes[i]["id_element"] == 646):
            print(entites_villes[i]["id_element"])
            if (entites_villes[i]["annee_debut"]==44):
                print(entites_villes[i])
                del entites_villes[i]
                print("646 deleted")
                print(entites_villes[i])

    # entites_villes_values = [(row["id_element"], row["valeur"].replace('"geometry": ', ""),0,) for row in entites_villes]
    # cursor.executemany("""
    #     INSERT INTO public.entites_villes (id_entite_ville, position_ville, crc_entites_villes)
    #     VALUES (%s, ST_GeomFromGeoJSON(%s),%s)
    # """, entites_villes_values)
    # print(f"Inserted {len(entites_villes_values)} rows into entites_villes")

    # # Batch insert for geometrie_pays
    # geometrie_pays_values = [(row["id_element"] ,row["annee_debut"] ,row["annee_debut"] , row["valeur"].replace('"geometry": ', ""),) for row in geometrie_pays]
    # cursor.executemany("""
    #     INSERT INTO public.geometrie_pays (id_entite_pays, date_debut, date_fin, geometry)
    #     VALUES (%s, %s, %s, ST_GeomFromGeoJSON(%s))
    # """, geometrie_pays_values)
    # print(f"Inserted {len(geometrie_pays_values)} rows into geometrie_pays")

    # # Batch insert for population_pays
    # population_pays_values = [(row["id_element"], row["annee_debut"], row["valeur"],) for row in population_pays]
    # cursor.executemany("""
    #     INSERT INTO public.populations_pays (id_entite_pays, date, population)
    #     VALUES (%s, %s, %s)
    # """, population_pays_values)
    # print(f"Inserted {len(population_pays_values)} rows into population_pays")

    # # Batch insert for existence_villes
    # existence_villes_values = [(row["id_element"], row["annee_debut"], row["annee_fin"],) for row in existence_villes]
    # cursor.executemany("""
    #     INSERT INTO public.existence_ville (id_entite_ville, date_debut, date_fin)
    #     VALUES (%s, %s, %s)
    # """, existence_villes_values)
    # print(f"Inserted {len(existence_villes_values)} rows into existence_ville")
    
    for i in range(len(population_villes)-1, -1, -1):
        if (population_villes[i]["valeur"] == 'null'):
            print("null : " + population_villes[i])
        if (population_villes[i]["id_element"] == 837):
            print(population_villes[i]["id_element"])
            del population_villes[i]
            print("837 deleted")
        if (population_villes[i]["id_element"] == 1057):
            print(population_villes[i]["id_element"])
            del population_villes[i]
            print("1057 deleted")
            print(population_villes[i])
        if (population_villes[i]["id_element"] == 1730):
            print(population_villes[i]["id_element"])
            del population_villes[i]
            print("1730 deleted")
    
    for i in range(len(noms_villes)-1, -1, -1):
        if (noms_villes[i]["valeur"] == 'null'):
            print("null : " + noms_villes[i])
        if (noms_villes[i]["id_element"] == 837):
            print(noms_villes[i]["id_element"])
            del noms_villes[i]
            print("837 deleted")
        if (noms_villes[i]["id_element"] == 1057):
            print(noms_villes[i]["id_element"])
            del noms_villes[i]
            print("1057 deleted")
            print(noms_villes[i])
        if (noms_villes[i]["id_element"] == 1730):
            print(noms_villes[i]["id_element"])
            del noms_villes[i]
            print("1730 deleted")
    
    for i in range(len(sources_villes)-1, -1, -1):
        if (sources_villes[i]["valeur"] == 'null'):
            print("null : " + sources_villes[i])
        if (sources_villes[i]["id_element"] == 837):
            print(sources_villes[i]["id_element"])
            del sources_villes[i]
            print("837 deleted")
        if (sources_villes[i]["id_element"] == 1057):
            print(sources_villes[i]["id_element"])
            del sources_villes[i]
            print("1057 deleted")
            print(sources_villes[i])
        if (sources_villes[i]["id_element"] == 1730):
            print(sources_villes[i]["id_element"])
            del sources_villes[i]
            print("1730 deleted")

    for i in range(len(wikipedia_villes)-1, -1, -1):
        if (wikipedia_villes[i]["valeur"] == 'null'):
            print("null : " + wikipedia_villes[i])
        if (wikipedia_villes[i]["id_element"] == 837):
            print(wikipedia_villes[i]["id_element"])
            del wikipedia_villes[i]
            print("837 deleted")
        if (wikipedia_villes[i]["id_element"] == 1057):
            print(wikipedia_villes[i]["id_element"])
            del wikipedia_villes[i]
            print("1057 deleted")
            print(wikipedia_villes[i])
        if (wikipedia_villes[i]["id_element"] == 1730):
            print(wikipedia_villes[i]["id_element"])
            del wikipedia_villes[i]
            print("1730 deleted")


    # # Batch insert for population_villes
    # population_villes_values = [(row["id_element"], row["annee_debut"], row["valeur"],) for row in population_villes]
    # cursor.executemany("""
    #     INSERT INTO public.populations_villes (id_entite_ville, date, population)
    #     VALUES (%s, %s, %s)
    # """, population_villes_values)
    # print(f"Inserted {len(population_villes_values)} rows into population_villes")
    
    #  Fetch the noms_pays table to get the id_nom_pays according to each nom_pays
    cursor.execute('SELECT * FROM public.noms_pays')
    noms_pays_new_bdd = cursor.fetchall()
    # insert a column into noms_pays to store the id_nom_pays
    for i in range(len(noms_pays)):
        for j in range(len(noms_pays_new_bdd)):
            if (noms_pays[i]["valeur"] == noms_pays_new_bdd[j][1]):
                noms_pays[i]["id_nom_pays"] = noms_pays_new_bdd[j][0]
                break
    
    # # Batch insert for pays
    # pays_values = [(row["id_element"], row["id_nom_pays"], row["annee_debut"], row["annee_fin"] ) for row in noms_pays]
    # cursor.executemany("""
    #     INSERT INTO public.pays (id_entite_pays, id_nom_pays, date_debut, date_fin)
    #     VALUES (%s, %s, %s, %s)
    # """, pays_values)
    # print(f"Inserted {len(pays_values)} rows into pays")

    # # Batch update for sources_pays
    # sources_pays_values = [(row["valeur"], row["id_element"], row["annee_debut"], row["annee_debut"], row["annee_fin"], row["annee_fin"],) for row in sources_pays]
    # cursor.executemany("""
    #     UPDATE public.pays 
    #     SET sources = %s
    #     WHERE id_entite_pays = %s
    #     AND ((CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s) 
    #     OR (CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s))
    # """, sources_pays_values)
    # print(f"Inserted {len(sources_pays_values)} rows into sources_pays")

    # # Batch update for sources_pays abandonned
    # sources_pays_values = [(row["valeur"], row["id_element"], row["annee_debut"], row["annee_debut"], row["annee_fin"], row["annee_fin"], row["id_element"], row["annee_debut"], row["annee_debut"], row["annee_fin"], row["annee_fin"],) for row in sources_pays]
    # cursor.executemany("""
    #     UPDATE public.pays 
    #     SET sources = CONCAT(%s,(
    #         SELECT sources
    #         FROM public.pays 
    #         WHERE id_entite_pays = %s //TODO: changer id_entite_pays par id_nom_pays
    #         AND ((CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s) 
    #         OR (CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s))
    #     ))
    #     WHERE id_entite_pays = %s //TODO: changer id_entite_pays par id_nom_pays
    #     AND ((CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s) 
    #     OR (CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s))
    # """, sources_pays_values)
    # print(f"Inserted {len(sources_pays_values)} rows into sources_pays")

    # # Batch insert for wikipedia_pays
    # wikipedia_pays_values = [(row["valeur"], row["id_element"], row["annee_debut"], row["annee_debut"], row["annee_fin"], row["annee_fin"],) for row in wikipedia_pays]
    # cursor.executemany("""
    #     UPDATE public.pays
    #     SET wikipedia = %s
    #     WHERE id_entite_pays = %s
    #     AND ((CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s)
    #     OR (CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s))
    # """, wikipedia_pays_values)
    # print(f"Inserted {len(wikipedia_pays_values)} rows into wikipedia_pays")

    # Fetch the noms_villes table to get the id_nom_ville according to each nom_ville
    cursor.execute('SELECT * FROM public.noms_villes')
    noms_villes_new_bdd = cursor.fetchall()
    # insert a column into noms_villes to store the id_nom_ville
    for i in range(len(noms_villes)):
        for j in range(len(noms_villes_new_bdd)):
            if (noms_villes[i]["valeur"] == noms_villes_new_bdd[j][1]):
                noms_villes[i]["id_nom_ville"] = noms_villes_new_bdd[j][0]
                break

    # # Batch insert for villes
    # villes_values = [(row["id_element"], row["id_nom_ville"], row["annee_debut"], row["annee_fin"] ) for row in noms_villes]
    # cursor.executemany("""
    #     INSERT INTO public.ville (id_entite_ville, id_nom_ville, date_debut, date_fin)
    #     VALUES (%s, %s, %s, %s)
    # """, villes_values)
    # print(f"Inserted {len(villes_values)} rows into villes")

    # # Batch update for sources_villes
    # sources_villes_values = [(row["valeur"], row["id_element"], row["annee_debut"], row["annee_debut"], row["annee_fin"], row["annee_fin"],) for row in sources_villes]
    # cursor.executemany("""
    #     UPDATE public.ville
    #     SET sources = %s
    #     WHERE id_entite_ville = %s
    #     AND ((CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s)
    #     OR (CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s))
    # """, sources_villes_values)
    # print(f"Inserted {len(sources_villes_values)} rows into sources_villes")

    # # Batch insert for wikipedia_ville
    # wikipedia_villes_values = [(row["valeur"], row["id_element"], row["annee_debut"], row["annee_debut"], row["annee_fin"], row["annee_fin"],) for row in wikipedia_villes]
    # cursor.executemany("""
    #     UPDATE public.ville
    #     SET wikipedia = %s
    #     WHERE id_entite_ville = %s
    #     AND ((CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s)
    #     OR (CAST(date_debut AS int) <= %s AND CAST(date_fin AS int) >= %s))
    # """, wikipedia_villes_values)
    # print(f"Inserted {len(wikipedia_villes_values)} rows into wikipedia_ville")



    connection_new_database.commit()


def main():
    """Main function that calls all the other functions and fills the new database with the data from the old database
    """
    connection_old_database = connect_to_my_sql_database()
    if connection_old_database is None:
        return

    data = fetch_data_from_old_database(connection_old_database)

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