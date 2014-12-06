<?php

    // configuration
    require("../includes/config.php");
    

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("login.php", ["title" => "Login"]);
    }
    
    // only if the user is logged in, can he register.
    
    if (isset($_SESSION["user"]))
    {
        $result = query("INSERT INTO users (user_id) VALUES(?)", $_SESSION["user"]["email"]);
        
    }

    redirect("mainController.php");       
            
?>