<?php

    $servername = "localhost";
    $username = "brsqluser";
    $password = "JzL3rber5U9DQQbT8LVK7TU";
    $dbname = "brainerdb";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    $select_clients="SELECT * FROM `admins_list`";
    $select_clients_result = $conn->query($select_clients);
	while($select_clients_result_row=$select_clients_result->fetch_assoc())
	{
	    
    $cust_id=$select_clients_result_row['cust_id'];
 
    $sql_loc_info ="select * from cust_locations where cust_id ='$cust_id' AND active=1";
	$stmt_loc_info = $conn->query($sql_loc_info);
	while($stmt_loc_info_row=$stmt_loc_info->fetch_assoc())
	{
	    
    $loc_Id=$stmt_loc_info_row['cust_location_id'];	
    $select_google_search_url="SELECT * FROM `cust_full_url` WHERE `cust_id` ='$cust_id' AND loc_id='$loc_Id'";
    $select_google_search_url_result = $conn->query($select_google_search_url);
        
        if ($select_google_search_url_result->num_rows > 0) {
          // output data of each row
         $select_google_search_url_result_row = $select_google_search_url_result->fetch_assoc();
         $location_name=$select_google_search_url_result_row['google_url'];
         $location_name_yelp=$select_google_search_url_result_row['yelp_url'];
         
         
                        //////////////////google reviews and rating API///////////////////////////////////
             
        if(!empty($location_name))
        {
        
              $url_components = parse_url($location_name);
              parse_str($url_components['query'], $params);
              $place_id = $params['placeid'];
            
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/place/details/json?key=AIzaSyAGpHosisLOL8fiZEFDJ6mkqs_DDng_fOw&placeid=$place_id");
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
             $result = curl_exec($ch);
           
             $decode= json_decode($result,true);
            //  echo "<pre>";
            //  print_r($decode);
            
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
          
        if($decode['status']=="ZERO_RESULTS" OR $decode['status']=="INVALID_REQUEST")
        
             {
                //   $google_rating_total="0.0";
                //   $google_reviews_total=0;
             }
             
             else
             {
            
            
            $google_rating_total=$decode['result']['rating']; 
            $google_reviews_total=$decode['result']['user_ratings_total'];
             
            //  $delete_main_google_rating="DELETE FROM main_google_data where cust_id='$cust_id' AND loc_id='$loc_Id'";
            //  $delete_main_google_rating_res = $conn->query($delete_main_google_rating); 
             
            
             ///////////////insert google rating to database//////////////
             
            $select_google_rating="SELECT * FROM google_rating where cust_id='$cust_id' AND loc_id='$loc_Id' AND loc_url='$location_name'";
            $select_google_rating_result = $conn->query($select_google_rating);
            $dt = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
            $last_update= $dt->format('m/d/Y, H:i:s A');
           
              if ($select_google_rating_result->num_rows > 0) 
              {
                
              echo  $update_google_rating="UPDATE google_rating SET cust_id='$cust_id', loc_id='$loc_Id', google_total_rating='$google_rating_total', google_total_review='$google_reviews_total', last_updated='$last_update' where cust_id='$cust_id' AND loc_id='$loc_Id' AND loc_url='$location_name'";
                $conn->query($update_google_rating);
                
               /* $google_write_review="UPDATE cust_full_url SET google_url='$new_google_write_review_url' where cust_id='$cust_id' AND loc_id='$loc_Id'";
                $conn->query($google_write_review);*/
              }
              else
             {
                echo  $insert_google_rating="INSERT INTO google_rating (cust_id, loc_id, google_total_rating, google_total_review, last_updated, loc_url) VALUES ('$cust_id', '$loc_Id', '$google_rating_total','$google_reviews_total','$last_update','$location_name')";
                 $conn->query($insert_google_rating);  
             }
             
           
            
            $user_review=$decode['result']['reviews'];
            
            foreach($user_review as $real_reviews)
            {
              
               $google_user_name= $real_reviews['author_name'];
               $google_auth_url=$real_reviews['author_url'];
               $google_user_arr=explode("/",$google_auth_url);
               $google_user_id=$google_user_arr[5];
               $google_user_rating= $real_reviews['rating'];
               $google_user_review= $real_reviews['text'];
               $time_google= $real_reviews['time'];
               $user_google_text_date = date('Y-m-d', $time_google);
               $dt = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
               $last_updated= $dt->format('m/d/Y, H:i:s A');
               $google_user_review1="$google_user_review";
               
              $select_google_data="SELECT * FROM `main_google_data` where cust_id='$cust_id' AND loc_id='$loc_Id' AND loc_url='$location_name' AND user_id='$google_user_id'";
              $select_google_data_result = $conn->query($select_google_data);
              if ($select_google_data_result->num_rows > 0) 
                {
                  $update_google_data='UPDATE main_google_data SET google_rating="'.$google_user_rating.'", google_review="'.$google_user_review1.'",time="'.$user_google_text_date.'",last_updated="'.$last_updated.'", loc_url="'.$location_name.'" where cust_id="'.$cust_id.'" AND loc_id="'.$loc_Id.'" AND loc_url="'.$location_name.'" AND user_id="'.$google_user_id.'"';
                  $update_google_data_result = $conn->query($update_google_data);  
                }
                  else
                {
                  $insert_google_data='INSERT INTO main_google_data (cust_id, loc_id, user_name, google_rating, google_review, time, last_updated, author_url, user_id, loc_url) VALUES ("'.$cust_id.'", "'.$loc_Id.'", "'.$google_user_name.'", "'.$google_user_rating.'", "'.$google_user_review1.'","'.$user_google_text_date.'","'.$last_updated.'","'.$google_auth_url.'","'.$google_user_id.'","'.$location_name.'")';
                  mysqli_query($conn, $insert_google_data);
              
                }
            }
          
            
         }
         
          
         }
         
        }
        
       
	}
	
	}
	
	
	
	$select_for_admin="SELECT * FROM `master_admin`";
	$select_for_admin_result = $conn->query($select_for_admin);
	while($select_for_admin_row=$select_for_admin_result->fetch_assoc())
	{
	  $admin_cust_id= $select_for_admin_row['cust_id'];
	
     $sql_loc_info ="select * from cust_locations where cust_id ='$admin_cust_id' AND active=1";
	$stmt_loc_info = $conn->query($sql_loc_info);
	while($stmt_loc_info_row=$stmt_loc_info->fetch_assoc())
	{
	    
    $admin_loc_Id=$stmt_loc_info_row['cust_location_id'];	
    $select_google_search_url="SELECT * FROM `cust_full_url` WHERE `cust_id` ='$admin_cust_id' AND loc_id='$admin_loc_Id'";
    $select_google_search_url_result = $conn->query($select_google_search_url);
        
        if ($select_google_search_url_result->num_rows > 0) {
          // output data of each row
         $select_google_search_url_result_row = $select_google_search_url_result->fetch_assoc();
         $location_name=$select_google_search_url_result_row['google_url'];
         $location_name_yelp=$select_google_search_url_result_row['yelp_url'];
         
         
                        //////////////////google reviews and rating API///////////////////////////////////
             
        if(!empty($location_name))
        {
             $url_components = parse_url($location_name);
              parse_str($url_components['query'], $params);
              $place_id = $params['placeid'];
            
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/place/details/json?key=AIzaSyAGpHosisLOL8fiZEFDJ6mkqs_DDng_fOw&placeid=$place_id");
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
             $result = curl_exec($ch);
           
             $decode= json_decode($result,true);
            //  echo "<pre>";
            //  print_r($decode);
            
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            
            
                 if($decode['status']=="ZERO_RESULTS" OR $decode['status']=="INVALID_REQUEST")
             {
                //   $google_rating_total="0.0";
                //   $google_reviews_total=0;
             }
             
             else
             {
            
            
            $google_rating_total=$decode['result']['rating']; 
            $google_reviews_total=$decode['result']['user_ratings_total'];
             
            //  $delete_main_google_rating="DELETE FROM main_google_data where cust_id='$admin_cust_id' AND loc_id='$admin_loc_Id'";
            //  $delete_main_google_rating_res = $conn->query($delete_main_google_rating); 
             
            
            //=============================  /insert google Location  rating to database===================================//////////////
             
            $select_google_rating="SELECT * FROM google_rating where cust_id='$admin_cust_id' AND loc_id='$admin_loc_Id'";
            $select_google_rating_result = $conn->query($select_google_rating);
            $dt = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
            $last_update= $dt->format('m/d/Y, H:i:s A');
           
              if ($select_google_rating_result->num_rows > 0) 
              {
                
                $update_google_rating="UPDATE google_rating SET cust_id='$admin_cust_id', loc_id='$admin_loc_Id', google_total_rating='$google_rating_total', google_total_review='$google_reviews_total', last_updated='$last_update' where cust_id='$admin_cust_id' AND loc_id='$admin_loc_Id' AND loc_url='$location_name'";
                $conn->query($update_google_rating);
                
               /* $google_write_review="UPDATE cust_full_url SET google_url='$new_google_write_review_url' where cust_id='$cust_id' AND loc_id='$loc_Id'";
                $conn->query($google_write_review);*/
                
              }
              else
             {
                  $insert_google_rating="INSERT INTO google_rating (cust_id, loc_id, google_total_rating, google_total_review, last_updated, loc_url) VALUES ('$admin_cust_id', '$admin_loc_Id', '$google_rating_total','$google_reviews_total','$last_update','$location_name')";
                 $conn->query($insert_google_rating);  
             }
            
            foreach($user_review as $real_reviews)
            {
              
              $google_user_name= $real_reviews['author_name'];
              $google_auth_url=$real_reviews['author_url'];
              $google_user_arr=explode("/",$google_auth_url);
              $google_user_id=$google_user_arr[5];
              $google_user_rating= $real_reviews['rating'];
              $google_user_review= $real_reviews['text'];
              $time_google= $real_reviews['time'];
              $user_google_text_date = date('Y-m-d', $time_google);
              $dt = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
              $last_updated= $dt->format('m/d/Y, H:i:s A');
              $google_user_review1="$google_user_review";
               
              $select_google_data="SELECT * FROM `main_google_data` where cust_id='$admin_cust_id' AND loc_id='$admin_loc_Id' AND loc_url='$location_name' AND user_id='$google_user_id'";
              $select_google_data_result = $conn->query($select_google_data);
              if ($select_google_data_result->num_rows > 0) 
                {
                  $update_google_data='UPDATE main_google_data SET google_rating="'.$google_user_rating.'", google_review="'.$google_user_review1.'",time="'.$user_google_text_date.'",last_updated="'.$last_updated.'", loc_url="'.$location_name.'" where cust_id="'.$admin_cust_id.'" AND loc_id="'.$admin_loc_Id.'" AND loc_url="'.$location_name.'" AND user_id="'.$google_user_id.'"';
                  $update_google_data_result = $conn->query($update_google_data);  
                }
                  else
                {
                  $insert_google_data='INSERT INTO main_google_data (cust_id, loc_id, user_name, google_rating, google_review, time, last_updated, author_url, user_id, loc_url) VALUES ("'.$admin_cust_id.'", "'.$admin_loc_Id.'", "'.$google_user_name.'", "'.$google_user_rating.'", "'.$google_user_review1.'","'.$user_google_text_date.'","'.$last_updated.'","'.$google_auth_url.'","'.$google_user_id.'","'.$location_name.'")';
                  mysqli_query($conn, $insert_google_data);
              
                }
            }
          
            
         }
         
          
         }
        }
	}
	   
	   
	   
	   
	   
	   
	   
	   
	   
	}
	
	
	
	
	?>