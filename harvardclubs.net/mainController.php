<?php

    // configuration (add back ../ after sending to Aditya)
    require("includes/search.php");
    
    // if user reached via GET
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // performs empty search that retrieves all clubs
        $results = search("", 0, 0, true, 0, 1000, 0, '1000-01-01',[""]); 
        
        // renders first page
        print("GET request received");
        render("testsearch1.html", ["search_results" => $results[0], "info" => $results[1]]);
        

    }
    
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //print("POST request received");
        // user submitted universal search
        $results = universal_search();
        
        // retrieve function: given a $table variable and $user_id, retrieve from this $table
        
        // deletion funtion
        
        //return to mainTemplate page
        render("testsearch1.html", ["search_results" => $results[0], "info" => $results[1]]);
        
    }
       
?>
