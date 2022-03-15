const express = require('express');
const router = express.Router();
const mysql = require('mysql');
const functions = require('./functions');
const bodyParser = require('body-parser');
const myDB = require('../../backend/database').con;

function myQuery(date_to, date_from){
    let myQuery = `select Station.stationProvider as Operator, count(Pass.passID) as NumberOfPasses
    from Pass INNER JOIN Station on Pass.stationRef = Station.stationID INNER JOIN Operator ON Operator.operatorName = Station.stationProvider
    WHERE Pass.timestamp BETWEEN '${date_from}' AND '${date_to}'
    GROUP BY Station.stationProvider;`;
    return myQuery;  
}
function PassesAlloc(req,res){
    
    var flag = 0;
        let date_from = req.params.date_from;
        let date_to = req.params.date_to;
        
        if (date_from.length == 0 || date_to.length == 0){
            res.status(400).send("400: Bad Request");
            return;
        }
        
        if (functions.ConvertDate(date_to) == 400 || functions.ConvertDate(date_from) == 400){
            res.status(400).send("400: Bad Request");
            return;
        }
        else {
            myDB.query(myQuery(date_to, date_from), function (err, result){
                if (err) {
                    res.status(500).send("500: Internal Server Error \n"+err);
                    return;
                }
                else if (result.length == 0){
                    res.status(402).send("402: No data");
                    return;
                }
                
                let Json = {
                    RequestTimestamp : functions.getTimestamp(),
                    PeriodFrom : functions.ConvertDate(date_from),
                    PeriodTo : functions.ConvertDate(date_to),
                    OPList : result
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


router.get('/PassesAlloc/:date_from/:date_to', PassesAlloc);
module.exports = router;