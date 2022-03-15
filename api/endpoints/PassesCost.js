const express = require('express');
const router = express.Router();
const mysql = require('mysql');
const functions = require('./functions');
const bodyParser = require('body-parser');
const myDB = require('../../backend/database').con;

function PassesCost(req,res){

  let op1_ID = req.params.op1_ID;
  let op2_ID = req.params.op2_ID;
  let date_from = req.params.date_from;
  let date_to = req.params.date_to;

  if (op1_ID.length == 0 || op2_ID.length == 0 || date_from.length == 0 || date_to.length == 0){
    res.status(400).send("400: Bad Request");
    return;
  }

  if (functions.ConvertDate(date_to) == 400 || functions.ConvertDate(date_from) == 400){
    res.status(400).send("400: Bad Request");
    return;
  }
  else{
  let myQuery=`SELECT COUNT(Pass.passID) as NumberOfPasses, SUM(Pass.charge) as PassesCost
  FROM Operator INNER JOIN Station ON Operator.operatorName = Station.stationProvider INNER JOIN Pass ON Pass.stationRef = Station.stationID
  INNER JOIN Vehicle ON Pass.vehicleRef = Vehicle.vehicleID
  WHERE Operator.operatorName = '${op1_ID}' AND Vehicle.tagProvider = '${op2_ID}' AND Pass.timestamp BETWEEN '${date_from}' AND '${date_to}'`;
  myDB.query(myQuery, function (err, result, fields){
    if (err) {
      res.status(500).send("500: Internal Server Error \n"+err);
      return;
    }
    if (result.length == 0){
      res.status(402).send("402: No data");
      return;
    }

    let obj = result[0];

    if (obj.NumberOfPasses == 0){
      res.status(402).send("402: No data");
      return;
    }

    let Json = {
      op1_ID : op1_ID,
      op2_ID : op2_ID,
      RequestTimestamp : functions.getTimestamp(),
      PeriodFrom : functions.ConvertDate(date_from),
      PeriodTo : functions.ConvertDate(date_to),
      NumberOfPasses : obj.NumberOfPasses,
      PassesCost : obj.PassesCost
    };
    let format = functions.findFormat(req);

    if (format == 0){
      res.status(200).send(Json);
      return;
    }
    else if (format == 1){
      res.status(200).send(functions.toCSV(Json));
      return;
    }
    else {
      res.status(400).send("400: Bad Request")
      return;
    }

  });
}
}

router.get('/PassesCost/:op1_ID/:op2_ID/:date_from/:date_to', PassesCost);
module.exports = router;
