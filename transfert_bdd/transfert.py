import mysql.connector
from mysql.connector import Error

try:
    # Establish the connection
    connection = mysql.connector.connect(
        host='localhost',  # or '127.0.0.1'
        user='root',  # replace with your MySQL username
        password='',  # replace with your MySQL password
        database='base_cartowiki'  # replace with your database name
    )

    if connection.is_connected():
        print("Successfully connected to the database")

        # Create a cursor object
        cursor = connection.cursor()

        # Execute a query
        cursor.execute("SELECT DATABASE();")

        # Fetch the result
        record = cursor.fetchone()
        print("You're connected to database:", record)

except Error as e:
    print("Error while connecting to MySQL", e)

finally:
    if connection.is_connected():
        cursor.close()
        connection.close()
        print("MySQL connection is closed")