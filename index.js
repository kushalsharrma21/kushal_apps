var path = require("path");
var flash = require('express-flash-2');
var express = require("express");
var app     = express();
var cookieParser = require('cookie-parser')
app.use(cookieParser());
var session = require('express-session');
app.use(session({secret: "Secret", saveUninitialized: false, resave: false}));
app.use(express.static(path.join(__dirname, 'public')));
app.use(flash());
require("dotenv").config();
let PORT= process.env.PORT;
app.set('view engine', '.ejs');
app.use('/', require(path.join(__dirname,"routes/blog.js")));
app.listen(PORT);
console.log("Running at Port"+PORT);