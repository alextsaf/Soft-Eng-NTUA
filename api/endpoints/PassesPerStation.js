const express = require('express');
const router = express.Router();
const mysql = require('mysql');
const functions = require('./functions');
const bodyParser = require('body-parser');
const myDB = require('../../backend/database').con;

function PassesPerStation(req,res){

  let stationID = req.params.stationID;
  let date_from = req.params.date_from;
  let date_to = req.params.date_to;

  if (stationID.length == 0 || date_from.length == 0 || date_to.length == 0){
    res.status(400).send("400: Bad Request");
    return;
  }

  if (functions.ConvertDate(date_to) == 400 || functions.ConvertDate(date_from) == 400){
    res.status(400).send("400: Bad Request");
    return;
  }

  // let myQuery=`SELECT Pass.passID, Pass.timestamp, Vehicle.vehicleID, Vehicle.tagProvider, 'PassType', Pass.charge
  // FROM Pass INNER JOIN Vehicle ON Pass.vehicleRef = Vehicle.vehicleID INNER JOIN Station ON Pass.stationRef = Station.stationID
  // WHERE Station.stationID = '${stationID}' AND Pass.timestamp BETWEEN '${date_from}' AND '${date_to}'`;
else {
  var myQuery=`SELECT ROW_NUMBER() OVER(ORDER BY Pass.passID) as PassIndex, Pass.passID as PassID, date_format(Pass.timestamp,'%Y-%m-%d %k:%i:%s') as PassTimeStamp, Vehicle.vehicleID as VehicleID, Vehicle.tagProvider as TagProvider,
  CASE
  WHEN Station.stationProvider = Vehicle.tagProvider THEN 'home' ELSE 'away'
  END as PassType,
  Pass.charge as PassCharge
  FROM Pass INNER JOIN Vehicle ON Pass.vehicleRef = Vehicle.vehicleID INNER JOIN Station ON Pass.stationRef = Station.stationID
  WHERE Station.stationID = '${stationID}' AND Pass.timestamp BETWEEN '${date_from}' AND '${date_to}' `;

  myDB.query(myQuery, function (err, result, fields){
    if (err) {
      res.status(500).send("500: Internal Server Error \n"+err);
      return;
    }

    if (result.length == 0){
      res.status(402).send("402: No data");
      return;
    }

    let helperQuery=`SELECT stationProvider FROM Station WHERE stationID = '${stationID}' `;

    myDB.query(helperQuery, function (err, operator, fields){
      if (err) {
        res.status(500).send("500: Internal Server Error \n"+err);
        return;
      }


      let stationOperator = operator.stationProvider;
      let numberOfPasses = result.length;

      let Json = {
        Station : stationID,
        StationOperator : stationOperator,
        RequestTimestamp : functions.getTimestamp(),
        PeriodFrom : functions.ConvertDate(date_from),
        PeriodTo : functions.ConvertDate(date_to),
        NumberOfPasses : numberOfPasses,
        PassesList : result
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
  });
}
    }
router.get('/PassesPerStation/:stationID/:date_from/:date_to', PassesPerStation);
module.exports = router;
