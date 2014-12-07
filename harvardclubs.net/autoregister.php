<?php

    // configuration
    require("includes/config.php");
    
/*
    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        if(
        redirect("login.php");    
    }
*/    
    // only if the user is logged in, can he register.
    
    if (isset($_SESSION["user"]))
    {
        
        if(!query("SELECT * FROM users WHERE username = ?", $_SESSION["user"]["email"]))
        {$result = query("INSERT INTO users (username) VALUES (?)", $_SESSION["user"]["email"]);
        }
    redirect("mainController.php");
        
    }
    
    else
    {
        redirect("login.php");
    }
            
?>