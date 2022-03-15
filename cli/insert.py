# Backend used to insert elements into the DB.
# Used in terminal, with two arguments
# First is a path to a csv file containing elements for entity
# Second is the Entity we want to insert the elements
# CSV files must arrange the elements in a certain way
# For Vehicle, Station, Pass and Charge(Pass), we use the format given with sample data

import sys
from typing import Counter
import pandas as pd
import os
import mariadb


def insertPass(CSV):
    errors = set({})
    counter = 0
    host_name = os.environ.get('DB_HOST_NAME', 'localhost')
    mydb = mariadb.connect(host=host_name,
    user='root',
    passwd='password',
    db='InterTolls')
    cursor = mydb.cursor()

    csv_file = CSV

    data = pd.read_csv(csv_file)
    #3 Instances were lost due to time zone differences.
    try:
        sql = "SET time_zone='+00:00'"
        cursor.execute(sql)
        mydb.commit()
    except:
        return "Could not adjust time zone"+str(sys.exc_info()[1])
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
            errors.add(sys.exc_info()[0])
            counter = counter + 1
        try:
            chargeattr = (rowData[0], rowData[5][0:2],  rowData[7], 'unsettled')
            if chargeattr[1] != chargeattr[2]:
                sql = "INSERT INTO Charge(passID, operatorCred, operatorDeb, status) VALUES (%s, %s, %s, %s)"
                cursor.execute(sql, chargeattr)
                mydb.commit()
        except:
            errors.add(sys.exc_info()[0])
            counter = counter + 1
    if (errors):
        return "Lost "+str(counter)+" instances due to"+str(errors)
    else:
        return "SUCCESS"
