# Description: This script is used to transfer data from the old database to the new database

import mysql.connector
from mysql.connector import Error
import psycopg2
import json


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
    cursor = connection_old_database.cursor(dictionary=True)
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
    def cmp(a, b):
        return (a > b) - (a < b)

    for key_carac, elmts_carac in caracs.items():
        for id_elmt, elmt in elmts_carac.items():
            elmt.sort(key=lambda x: x[0])

def main():
    connection = connect_to_database()
    if connection is None:
        return

    data = fetch_data(connection)
    geojson, caracs = generate_geojson_and_caracs(data)
    sort_caracs(caracs)

    print(json.dumps(geojson, ensure_ascii=False))
    print(';;;')
    print(json.dumps(caracs, ensure_ascii=False))

    connection.close()

if __name__ == "__main__":
    main()