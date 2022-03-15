const express = require('express');
const app = express();
const port = 9103;
var path = require('path');

app.listen(port, () => {
    console.log('App is running on port %s', port);
});

app.get('/interoperability/api', (req, res) => {
  res.sendFile(path.join(__dirname + '/index.html'));
});

//load all endpoints
const PassesPerStation=require("./endpoints/PassesPerStation.js");
const PassesAnalysis=require("./endpoints/PassesAnalysis.js");
const PassesCost=require("./endpoints/PassesCost.js");
const ChargesBy=require("./endpoints/ChargesBy.js");
const PassesAlloc=require("./endpoints/PassesAlloc.js");
const Admin=require("./endpoints/admin.js");

//bind all endpoints to app router
app.use('/interoperability/api',PassesPerStation);
app.use('/interoperability/api',PassesCost);
app.use('/interoperability/api',PassesAnalysis);
app.use('/interoperability/api',ChargesBy);
app.use('/interoperability/api',PassesAlloc);
app.use('/interoperability/api',Admin);
