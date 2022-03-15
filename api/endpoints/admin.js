const host_name = process.env.HOST_NAME || 'localhost';
const myDB = require('../../backend/database').con;
const express = require('express');
const router = express.Router();
const mysql = require('mysql');
const functions = require('./functions');




function healthcheck(req,res){
  const DB = {
    host: host_name,
    user: "root",
    password: "password",
    database:"InterTolls"
  };
  var con = mysql.createConnection(DB);
  con.connect(function(err) {
    if (err) {
      console.log("Error Connecting!");
      var Json = {
        status:"failed",
        dbconnnection: DB
      };
      res.status(500).send(Json);
      return;
    };
    console.log("Connected!");
    var Json = {
      status:"OK",
      dbconnnection: DB
    };
    res.status(200).send(Json);
    return;
  });
  
}

function resetpasses(req,res){  
    myquery="DELETE FROM Pass";
    myDB.query(myquery, err=>{
      if (err) {
        var Json = {
          status:"failed",
          error: err
        };
        res.status(500).send(Json);
        return;
      };
      var Json = {
        status:"OK",
      };
      res.status(200).send(Json);
      return;
    });
  }

function resetstations(req,res){
    
    myquery=`DELETE FROM Station`;
    myDB.query(myquery, function (err){
      if (err) {
        var Json = {
          status:"failed",
          error: err
        };
        res.status(500).send(Json);
        return;
      };
      functions.fillTable(res, "../database/sql_data/Station.sql");
      });
    }

function resetvehicles(req,res){
    
    myquery="DELETE FROM Vehicle";
    myDB.query(myquery, function (err){
      if (err) {
        var Json = {
          status:"failed",
          error: err
        };
        res.status(500).send(Json);
        return;
      };
      functions.fillTable(res, "../database/sql_data/Vehicle.sql");
      });
    }

function resetoperators(req,res){
  
    myquery="DELETE FROM Operator";
    myDB.query(myquery, function (err){
      if (err) {
        var Json = {
          status:"failed",
          error: err
        };
        res.status(500).send(Json);
        return;
      };
      functions.fillTable(res, "../database/sql_data/Operator.sql");
      });
    }

function fillpasses(req, res){
  myquery="DELETE FROM Pass";
  myDB.query(myquery, function (err){
    if (err) {
      var Json = {
        status:"failed",
        error: err
      };
      res.status(500).send(Json);
      return;
    };
    functions.fillTable(res, "../database/sql_data/Pass.sql");
    });
}

router.get('/admin/healthcheck', healthcheck);
router.post('/admin/resetpasses', resetpasses);
router.post('/admin/fillpasses', fillpasses);
router.post('/admin/resetstations', resetstations);
router.post('/admin/resetvehicles', resetvehicles);
router.post('/admin/resetoperators', resetoperators);
module.exports = router;
