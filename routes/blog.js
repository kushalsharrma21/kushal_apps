var express = require("express");
const router = express.Router();
var csurf = require('csurf');
const stripe = require('stripe')(process.env.Stripe_Api_key);
var csrfProtection = csurf({ cookie: true });
var app = express();
app.use(express.json());
const blogs=require("../data/blog");
const user=require("../controllers/User.controller");
const auth=require("../middleware/auth");
const bodyParser = require('body-parser');
const { body } = require('express-validator');
const urencode =bodyParser.urlencoded({ extended: false }); 
const multer  = require('multer');
//const Users  = require('../model/users');
var mongoose = require('mongoose'); 

var storage =   multer.diskStorage({  
    destination: function (req, file, callback) {  
            callback(null, 'public'); 
    },  
    filename: function (req, file, callback) {  
      callback(null, file.originalname);  
    }
  });  

  var upload = multer({
    storage: storage,
    fileFilter: (req, file, cb) => {
      if (file.mimetype == "image/png" || file.mimetype == "image/jpg" || file.mimetype == "image/jpeg") {
        cb(null, true);
      } else {
        cb(null, false);
        return cb(new Error('Only .png, .jpg and .jpeg format allowed!'));
      }
    }
  });


router.get('/',function(req,res){
      res.render('home');
  });


  router.get('/stripe', async function(req,res){

    const customer = await stripe.customers.create({
      description: 'My First Test Customer (created for API docs)',
      email:'kushalsharma660@gmail.com',
      address:{
        city:"Shimlas",
        state:'Himachal',
        country:"India",
        postal_code:124555
      },
      phone:98325532523,
      name:"Kushal Sharma Jack"
    });

    Customer_id=customer.id;
    customer_object=customer.object;
    if(customer_object=="customer" && Customer_id){

  const token = await stripe.tokens.create({
    card: {
      number: '4242424242424242',
      exp_month: 11,
      exp_year: 2022,
      cvc: '314',
    },
  });
  token_object=token.object;
  
    //  if(token_object=="token"){

      const charge = await stripe.charges.create({
        amount: 50000,
        currency: 'INR',
        source: 'tok_amex',
        description: 'My First Test Charge (created for API docs)',
      });
 
      return res.json({charge}); 
      
   
    
    }
    
});

var csrfProtection = csurf({ cookie: true })
router.get('/form', function(req,res){

    res.render('index');
});

const validation= [
  body('email').isEmail().withMessage('Email is not valid'),
  body('pass').isLength({ min: 5 }).withMessage('Password should be 5 characters'),
];

router.post('/form', upload.single('filetoupload'),validation,
user.create);

router.get('/blog',function(req,res){
    res.render('blogs',{
        blogs:blogs
    });
});

router.get('/dashboard', user.dash);

    router.get('/blogpost/:id',function(req,res){

    myblogs= blogs.filter((e)=>{
        return e.id == req.params.id;
    });
    
    res.render('blogPage',{
        blo:myblogs
    });
    
});

router.get('/log_in',user.login);
router.get('/logout',user.logout);
router.post('/log_in', urencode, user.log_in);
router.get('/get', user.get);
router.get('/forget', user.forget);

// mongoose.connect(
//   `mongodb+srv://kushal:kushal@Cluster0.oai7e.mongodb.net/myFirstDatabase?retryWrites=true&w=majority`, 
//   {
//     useNewUrlParser: true,
//     useUnifiedTopology: true
//   }
// );
// router.get('/insert/data', async function(req,res){

//   const new_modal=[
//     {   
//       First_Name: "kushal",
//       Last_Name: "sharma"
//     },
//     {
//       First_Name: "deepak",
//       Last_Name: "sharma"
//     },
//     {
//       First_Name: "rajevv",
//       Last_Name: "pal"
//     }
//     ];
  
//     var myquery = { First_Name: "rajevv"};
//     var newvalues = { $set: {First_Name: "Jatt", Last_Name: "Canyon 123" } };

//     await Users.find().
//     then((response)=>{
//       res.send(response);
//     }).catch((err)=>{
//       res.send(err);
//     });
// });

// router.get('/flash', user.flash);
// router.get('/flashget', user.flashget);


module.exports = router;