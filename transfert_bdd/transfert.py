import mysql.connector
from mysql.connector import Error
import json

def connect_to_database():
    try:
        connection = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='base_cartowiki'
        )
        return connection
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None

def fetch_data(connection):
    cursor = connection.cursor(dictionary=True)
    cursor.execute('SELECT * FROM formes JOIN elements ON formes.id_element = elements.id')
    return cursor.fetchall()

def generate_geojson_and_caracs(data):
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