const mysql = require('mysql');
const host_name = process.env.HOST_NAME || 'localhost';

const myDB = {
    host: host_name,
    user: "root",
    password: "password",
    database:"InterTolls"
  };


  const con = mysql.createConnection(myDB);

  con.connect((err) => {
    if (err) {
        console.error(err);
        return;
    }
    console.log("Sql connection successful");
});

module.exports = { con };