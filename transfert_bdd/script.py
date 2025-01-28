import mysql.connector
import json

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

def create_sql_file():
    """Generates an SQL file with insertion and update queries for a PostgreSQL database"""

    sql_content = []
    
    # Définir des ID pour les noms de pays et de villes (par exemple, un compteur ou un mapping manuel)
    noms_pays_ids = {}
    noms_villes_ids = {}

    # Compteur pour générer des IDs uniques (vous pouvez aussi les définir manuellement si vous en avez un)
    id_counter_pays = 1
    id_counter_villes = 1

    noms_pays_values = [(row["valeur"],) for row in noms_pays]
    noms_pays_ids["valeur"] = []
    for row in noms_pays_values:
        noms_pays_ids["valeur"].append(id_counter_pays);
        sql_content.append(f"INSERT INTO public.noms_pays (id_nom_pays, nom_pays) VALUES ({id_counter_pays}, '{row[0]}');")
        id_counter_pays += 1
    print(f"Prepared {len(noms_pays_values)} insertions for noms_pays")

    noms_villes_values = [(row["valeur"],) for row in noms_villes]
    noms_villes_ids["valeur"] = []
    for row in noms_villes_values:
        noms_villes_ids["valeur"].append(id_counter_villes)
        sql_content.append(f"INSERT INTO public.noms_villes (id_nom_ville, nom_ville) VALUES ({id_counter_villes}, '{row[0]}');")
        id_counter_villes += 1    
    print(f"Prepared {len(noms_villes_values)} insertions for noms_villes")

    entites_pays_values = [(row["id"], row["couleur"],) for row in entites_pays]
    for e in entites_pays_values:
        sql_content.append(f"INSERT INTO public.entites_pays (id_entite_pays, couleur) VALUES ({e[0]}, '{e[1]}');")
    print(f"Prepared {len(entites_pays_values)} insertions for entites_pays")

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

    entites_villes_values = [(row["id_element"], row["valeur"].replace('"geometry": ', "")) for row in entites_villes]
    for e in entites_villes_values:
        sql_content.append(f"INSERT INTO public.entites_villes (id_entite_ville, position_ville) VALUES ({e[0]}, ST_GeomFromGeoJSON('{e[1]}'));")
    print(f"Prepared {len(entites_villes_values)} insertions for entites_villes")

    # Batch insert for geometrie_pays
    geometrie_pays_values = [(row["id_element"], row["annee_debut"], row["annee_fin"], row["valeur"].replace('"geometry": ', "")) for row in geometrie_pays]
    for e in geometrie_pays_values:
        sql_content.append(f"INSERT INTO public.geometrie_pays (id_entite_pays, date_debut, date_fin, geometry) VALUES ({e[0]}, {e[1]}, {e[2]}, ST_GeomFromGeoJSON('{e[3]}'));")
    print(f"Prepared {len(geometrie_pays_values)} insertions for geometrie_pays")

    # Batch insert for population_pays
    population_pays_values = [(row["id_element"], row["annee_debut"], row["valeur"]) for row in population_pays]
    for e in population_pays_values:
        sql_content.append(f"INSERT INTO public.populations_pays (id_entite_pays, date, population) VALUES ({e[0]}, {e[1]}, {e[2]});")
    print(f"Prepared {len(population_pays_values)} insertions for population_pays")

    # Batch insert for existence_villes
    population_pays_values = [(row["id_element"], row["annee_debut"], row["annee_fin"]) for row in population_pays]
    for e in population_pays_values:
        sql_content.append(f"INSERT INTO public.existence_ville (id_entite_ville, date_debut, date_fin) VALUES ({e[0]}, {e[1]}, {e[2]});")
    print(f"Prepared {len(population_pays_values)} insertions for existence_ville")

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
    for row in population_villes:
        sql_content += f"""
            INSERT INTO public.populations_villes (id_entite_ville, date, population)
            VALUES ({row["id_element"]}, {row["annee_debut"]}, {row["valeur"]});
        """
    print(f"Inserted {len(population_villes)} rows into population_villes")
    
    for i in range(len(noms_pays)):
        noms_pays[i]["id_nom_pays"] = noms_pays_ids[noms_pays[i]["valeur"]]

    # Batch insert for pays
    for row in noms_pays:
        sql_content += f"""
            INSERT INTO public.pays (id_entite_pays, id_nom_pays, date_debut, date_fin)
            VALUES ({row["id_element"]}, {row["id_nom_pays"]}, {row["annee_debut"]}, {row["annee_fin"]});
        """
    print(f"Inserted {len(noms_pays)} rows into pays")

    # Batch update for sources_pays
    for row in sources_pays:
        sql_content += f"""
            UPDATE public.pays 
            SET sources = {row["valeur"]}
            WHERE id_entite_pays = {row["id_element"]}
            AND ((CAST(date_debut AS int) <= {row["annee_debut"]} AND CAST(date_fin AS int) >= {row["annee_debut"]}) 
            OR (CAST(date_debut AS int) <= {row["annee_fin"]} AND CAST(date_fin AS int) >= {row["annee_fin"]}));
        """
    print(f"Inserted {len(sources_pays)} rows into sources_pays")

    # Batch insert for wikipedia_pays
    for row in wikipedia_pays:
        sql_content += f"""
            UPDATE public.pays
            SET wikipedia = {row["valeur"]}
            WHERE id_entite_pays = {row["id_element"]}
            AND ((CAST(date_debut AS int) <= {row["annee_debut"]} AND CAST(date_fin AS int) >= {row["annee_debut"]})
            OR (CAST(date_debut AS int) <= {row["annee_fin"]} AND CAST(date_fin AS int) >= {row["annee_fin"]}));
        """
    print(f"Inserted {len(wikipedia_pays)} rows into wikipedia_pays")


    # insert a column into noms_villes to store the id_nom_ville
    for i in range(len(noms_villes)):
        noms_villes[i]["id_nom_ville"] = noms_villes_ids[noms_villes[i]["valeur"]]

    # Batch insert for villes
    for row in noms_villes:
        sql_content += f"""
            INSERT INTO public.ville (id_entite_ville, id_nom_ville, date_debut, date_fin)
            VALUES ({row["id_element"]}, {row["id_nom_ville"]}, {row["annee_debut"]}, {row["annee_fin"]});
        """
    print(f"Inserted {len(noms_villes)} rows into villes")

    # Batch update for sources_villes
    for row in sources_villes:
        sql_content += f"""
            UPDATE public.ville
            SET sources = {row["valeur"]}
            WHERE id_entite_ville = {row["id_element"]}
            AND ((CAST(date_debut AS int) <= {row["annee_debut"]} AND CAST(date_fin AS int) >= {row["annee_debut"]})
            OR (CAST(date_debut AS int) <= {row["annee_fin"]} AND CAST(date_fin AS int) >= {row["annee_fin"]}));
        """
    print(f"Inserted {len(sources_villes)} rows into sources_villes")
    
    # Batch insert pays_ville (the table where we store in which countries are the cities)
    # ST_CONTAINS
    # I will do an union of tables (entites_pays JOIN geometrie_pays) and (entites_villes JOIN existence_ville) to get the countries of the cities
    # I will do a loop on the cities and for each city I will do a loop on the countries to see if the city is in the country using ST_CONTAINS
    # I will also have to check the dates first
    # I will also have to check if the country is nomade
    sql_content += f"""
        INSERT INTO public.pays_ville (id_entite_pays, id_entite_ville, date_debut, date_fin) 
        SELECT entites_pays.id_entite_pays, entites_villes.id_entite_ville, 
        GREATEST(CAST(geometrie_pays.date_debut AS int), CAST(existence_ville.date_debut AS int)) AS date_debut,
        LEAST(CAST(geometrie_pays.date_fin AS int), CAST(existence_ville.date_fin AS int)) AS date_fin
        FROM public.geometrie_pays JOIN public.entites_pays ON public.geometrie_pays.id_entite_pays = public.entites_pays.id_entite_pays, 
        public.existence_ville JOIN public.entites_villes ON public.existence_ville.id_entite_ville = public.entites_villes.id_entite_ville 
        WHERE ST_CONTAINS(public.geometrie_pays.geometry, public.entites_villes.position_ville) AND (CAST(geometrie_pays.date_debut AS int)<=CAST(existence_ville.date_fin AS int) AND CAST(geometrie_pays.date_fin AS int)>=CAST(existence_ville.date_debut AS int))
    """
    
    est_capitale_values = [(row["annee_debut"], row["annee_fin"], row["id_element"], row["annee_debut"], row["annee_fin"],) for row in est_capitale]

    # Batch insert for est_capitale
    # I will do a loop on the table pays_ville
    # I will insert into the table capitales the cities that are capitals with the dates of the capitals
    # The dates will need to be contained in the dates of the cities
    for e in est_capitale_values:
        sql_content.append(f"""
            INSERT INTO public.capitales (id_pays_ville, date_debut, date_fin)
            SELECT id_pays_ville, GREATEST(CAST(date_debut AS int), {e[0]}), LEAST(CAST(date_fin AS int), {e[1]})
            FROM public.pays_ville
            WHERE id_entite_ville = {e[2]}
            AND CAST(date_fin AS int) >= {e[4]}
            AND CAST(date_debut AS int) <= {e[3]};
        """)
    
    # Writing the SQL queries to a file
    with open('init-data.sql', 'w', encoding="utf-8") as sql_file:
        for line in sql_content:
            sql_file.write(line + "\n")
    print("SQL file 'insert_statements.sql' has been generated successfully.")
    return

def main():
    """Main function that calls all the other functions and fills the new database with the data from the old database
    """
    connection_old_database = connect_to_my_sql_database()
    if connection_old_database is None:
        return

    data = fetch_data_from_old_database(connection_old_database)

    geojson, caracs = generate_geojson_and_caracs(data)
    sort_caracs(caracs)

    create_sql_file()

if __name__ == "__main__":
    main()