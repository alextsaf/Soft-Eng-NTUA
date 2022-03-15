const express = require('express');
const router = express.Router();
const mysql = require('mysql');
const functions = require('./functions');
const bodyParser = require('body-parser');
const myDB = require('../../backend/database').con;


function ChargesBy(req,res){

  var flag = 0;
  var con = mysql.createConnection(myDB);

    let op_ID = req.params.op_ID;
    let date_from = req.params.date_from;
    let date_to = req.params.date_to;

    if (op_ID.length == 0 || date_from.length == 0 || date_to.length == 0){
      res.status(400).send("400: Bad Request");
      return;
    }

    if (functions.ConvertDate(date_to) == 400 || functions.ConvertDate(date_from) == 400){
      res.status(400).send("400: Bad Request");
      return;
    }
    else {
      let myQuery=`SELECT Vehicle.tagProvider as VisitingOperator, COUNT(Pass.passID) as NumberOfPasses, SUM(Pass.charge) as PassesCost
      FROM Operator INNER JOIN Station ON Operator.operatorName = Station.stationProvider INNER JOIN Pass ON Pass.stationRef = Station.stationID
      INNER JOIN Vehicle ON Pass.vehicleRef = Vehicle.vehicleID
      WHERE Operator.operatorName = '${op_ID}' AND Pass.timestamp BETWEEN '${date_from}' AND '${date_to}' AND Vehicle.tagProvider <> Operator.operatorName
      GROUP BY Vehicle.tagProvider`;
      myDB.query(myQuery, function (err, result, fields){
        if (err) {
          res.status(500).send("500: Internal Server Error \n"+err);
          return;
        }
        else if (result.length == 0){
          res.status(402).send("402: No data");
          return;
        }

        let Json = {
          op_ID : op_ID,
          RequestTimestamp : functions.getTimestamp(),
          PeriodFrom : functions.ConvertDate(date_from),
          PeriodTo : functions.ConvertDate(date_to),
          PPOList : result
        };
        let format = functions.findFormat(req);

        if (format == 0){
          res.status(200).send(Json);
          return;
        }
        else if (format == 1){
          res.status(200).send(functions.toCSVnested(Json));
          return;
        }
        else {
          res.status(400).send("400: Bad Request")
          return;
        }
      });
    };
  }

router.get('/ChargesBy/:op_ID/:date_from/:date_to', ChargesBy);
module.exports = router;
