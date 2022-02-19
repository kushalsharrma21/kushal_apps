var mongoose = require('mongoose'); 
var Schema = mongoose.Schema;
var SomeModelSchema = new Schema({
    First_Name: String,
    Last_Name: String
  });
var Users = mongoose.model('Users', SomeModelSchema );
module.exports = Users;