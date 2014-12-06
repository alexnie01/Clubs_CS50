 <?php

    // configuration
    require("../includes/search.php");
    
    // if user reached via GET
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // performs empty search that retrieves all clubs
        $search_results = search("", 0, 0, true, 0, 1000, 0, '1000-01-01',[""]); 
        
        // renders first page
        render("testsearch0.html", ["search_results" => $search_results]);
        print("GET request received");

    }
    
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        print("POST request received");
        // user submitted universal search
        $search_results = universal_search();
        
        // retrieve function: given a $table variable and $user_id, retrieve from this $table
        
        // deletion funtion
        
        //return to mainTemplate page
        render("testsearch0.html", ["search_results" => $search_results]);
        
    }
       
?>
