# Backend used to insert elements into the DB.
# Used in terminal, with two arguments
# First is a path to a csv file containing elements for entity
# Second is the Entity we want to insert the elements
# CSV files must arrange the elements in a certain way
# For Vehicle, Station, Pass and Charge(Pass), we use the format given with sample data

import sys
import pandas as pd
import os
import mariadb

tables = {'Vehicle', 'Pass', 'Station', 'Charge', 'Operator', 'initial'}
operators = [('aodos', 'AO'), ('gefyra', 'GF'), ('egnatia', 'EG'), ('kentriki_odos', 'KO'), ('moreas', 'MR'), ('nea_odos', 'NE'), ('olympia_odos', 'OO')]
flag = 0
host_name = os.environ.get('DB_HOST_NAME', 'localhost')
mydb = mariadb.connect(host=host_name,
user='root',
passwd='password',
db='InterTolls')
cursor = mydb.cursor()





if len(sys.argv) == 1:
    table = 'initial'
elif len(sys.argv) == 3:
    table = sys.argv[2]
    csv_file = sys.argv[1]
else:
    table = 'ERROR'

#3 Instances were lost due to time zone differences.
try:
    sql = "SET time_zone='+00:00'"
    cursor.execute(sql)
    mydb.commit()
except:
    flag = 1


if table not in tables:
    print('Usage: "python3 insert.py <csv_file> <table>" OR "python3 insert.py" \n <csv_file> : path to a CSV file containing data \n <table> : a valid table in the DB (excluding Settlement)')
else:
    if table == 'initial':
        for op in operators:
            try:
                sqlop = "INSERT INTO Operator Values(%s, %s)"
                opattr = (op[0], op[1])
                cursor.execute(sqlop, opattr)
                mydb.commit()
            except:
                flag = 1
    elif table == 'Vehicle':
        data = pd.read_csv(csv_file)
        for row in data.itertuples():
            try:
                rowData = tuple(row[1].split(';'))
                sql = "INSERT INTO Vehicle VALUES (%s, %s, %s, %s, %s)"
                cursor.execute(sql, rowData)
                mydb.commit()
            except:
                flag = 1
    elif table == 'Station':
        data = pd.read_csv(csv_file)
        for row in data.itertuples():
            try:
                rowData = tuple(row[1].split(';'))
                sql = "INSERT INTO Station VALUES (%s, %s, %s)"
                cursor.execute(sql, rowData)
                mydb.commit()
            except:
                flag = 1
    elif table == 'Operator':
        data = pd.read_csv(csv_file)
        for row in data.itertuples():
            try:
                sqlop = "INSERT INTO Operator Values(%s, %s)"
                cursor.execute(sqlop, rowData)
                mydb.commit()
            except:
                flag = 1
    elif table == 'Pass':
        data = pd.read_csv(csv_file)
        for row in data.itertuples():
            try:
                rowData = tuple(row[1].split(';'))
                date, time = rowData[1].split(' ')
                day, month, year = date.split('/')
                time = time + ':00'
                timestamp = year+'-'+month+'-'+day+' '+time
                passattr = (rowData[0], timestamp, rowData[4], rowData[3], rowData[2])
                sql = "INSERT INTO Pass VALUES (%s, %s, %s, %s, %s)"
                cursor.execute(sql, passattr)
                mydb.commit()
            except:
                flag = 1
            try:
                chargeattr = (rowData[0], rowData[5][0:2],  rowData[7], 'unsettled')
                if chargeattr[1] != chargeattr[2]:
                    sql = "INSERT INTO Charge(passID, operatorCred, operatorDeb, status) VALUES (%s, %s, %s, %s)"
                    cursor.execute(sql, chargeattr)
                    mydb.commit()
            except:
                flag = 1
        print("SUCCESS")
