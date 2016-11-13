'use_strict';

var express = require('express');

var app = express();

app.use(express.static('public'));

app.get('/api/:REQUEST', function(){
    
});

app.listen(80, function(){
    console.log("Server listening on port 80");
});