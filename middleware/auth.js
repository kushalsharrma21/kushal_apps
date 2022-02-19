
var auth = function (req, res, next) {
    if(!req.session.user_id){
      res.redirect("log_in");
    }
    else{
        
        res.redirect("dashboard");
        next();
    }
   
  }


  module.exports = auth;