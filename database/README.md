# MariaDB/MySQL Database

- The database is implemented using MariaDB.
Το αρχείο `Tolls.sql` contains all the sql code. The `sql_data` folder contains all the mock data used to operate the app.
- Installation:
  - Start the server using XAMPP.
  - Create a DB named `InterTolls`
  - Initialize the DB using the `sql_data` folder

### Example:

Connect into mysql/mariadb create th DB `multe-pass`
```bash
mysql -u root -p
CREATE DATABASE `InterTolls`;
```

Insert the dump into the DB

```bash
mysql -u root -p InterTolls < Tolls.sql
```
