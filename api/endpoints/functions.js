const express = require('express');
const bodyParser = require('body-parser');
const myDB = require('../../backend/database').con;
const fs = require("fs");

function ConvertDate(date){
  if (date.length != 8) return 400;
  for (let i = 0; i < 8; i++){
    if (!(parseInt(date.substr(i, 1)) >= 0 && parseInt(date.substr(i, 1)) <= 9)) return 400;
  }
  year = date.substr(0, 4);
  month = date.substr(4, 2);
  day = date.substr(6, 2);
  switch(month){
    case '01':
    if (day >= 32) return 400;
    break;
    case '02':
    if (day >= 30) return 400;
    break;
    case '03':
    if (day >= 32) return 400;
    break;
    case '04':
    if (day >= 31) return 400;
    break;
    case '05':
    if (day >= 32) return 400;
    break;
    case '06':
    if (day >= 31) return 400;
    break;
    case '07':
    if (day >= 32) return 400;
    break;
    case '08':
    if (day >= 32) return 400;
    break;
    case '09':
    if (day >= 31) return 400;
    break;
    case '10':
    if (day >= 32) return 400;
    break;
    case '11':
    if (day >= 31) return 400;
    break;
    case '12':
    if (day >= 32) return 400;
    break;
    default :
    return 400;
  }
  return (day+"-"+month+"-"+year);
}

function getDate() {
  var d = new Date(),
  month = '' + (d.getMonth() + 1),
  day = '' + d.getDate(),
  year = d.getFullYear();
  
  if (month.length < 2)
  month = '0' + month;
  if (day.length < 2)
  day = '0' + day;
  
  return [year, month, day].join('-');
}

function getTimestamp(){
  var d = new Date();
  var n  = d.toLocaleTimeString();
  var time;
  if (n.length == 10) time = '0'+n.substr(0, 7);
  else time = n.substr(0, 8);
  
  return (getDate()+' '+ time);
}

function findFormat(req){
  let format = req.query.format;
  if (format == null || format == 'json') return 0;
  else if (format == 'csv') return 1;
  else return 400;
}


function toCSV(Json) {
  const fields = Object.keys(Json);
  const values = Object.values(Json);
  let length = fields.length;
  let result = '';
  //1st line of csv
  for (let i = 0; i < length; i++) {
    result += fields[i] + ',';
  }
  result += '\n';
  for (let j = 0; j < length; j++) {
    result += values[j] + ',';
  }
  result += '\n';
  
  return result.slice(0, -2);
}



function toCSVnested(Json){
  let fields = Object.keys(Json); //outer Json keys, values and length
  let values = Object.values(Json);
  let length = fields.length;
  let nestedJsonkeys = Object.keys(values[length-1][0]); //inner list's Json keys, values and length
  let nestedJsonlength = nestedJsonkeys.length;
  var nestedJsonvalues;
  let listlength = values[length - 1].length;
  let result = '';
  
  //1st line of csv
  for (let i = 0; i < length-1; i++) {
    result += fields[i] + ',';
  }
  for (let i = 0; i < nestedJsonlength; i++) {
    result += nestedJsonkeys[i] + ',';
  }
  result += '\n';
  
  //loop through the content
  for (let i = 0; i < listlength; i++){
    for (let j = 0; j < length-1; j++) {
      result += values[j] + ',';
    }
    nestedJsonvalues = Object.values(values[length - 1][i]);
    
    for (let j = 0; j < nestedJsonlength; j++) {
      result += nestedJsonvalues[j] + ',';
    }
    result += '\n';
  }
  
  return result.slice(0, -2);
}

function fillTable(res, file){
  let query;
  try {
    query = fs.readFileSync(file).toString();
  } catch (error) {
    var Json = {
      status:"failed",
      error: error
    };
    res.status(500).send(Json);
    return;
  }
  myDB.query(query, err=>{
    if (err) {
      var Json = {
        status:"failed",
        error: err
      };
      res.status(500).send(Json);
      return;
    };
    var Json = {
      status:"OK"
    };
    res.status(200).send(Json);
    return;
  });
}

module.exports = {ConvertDate, getTimestamp, findFormat ,toCSV, toCSVnested, fillTable};
