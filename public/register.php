<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // TODO
        // validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        else if($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Your passwords do not match.");
        }
        
        else
        {
            $result = query("INSERT INTO users (user_id, password) VALUES(?, ?)", $_POST["username"], $_POST["password"]);
        
            if ($result === false)
            {
                apologize("Username already in use.");
            }
            
            $rows = query("SELECT LAST_INSERT_ID() AS id");
            $id = $rows[0]["id"];  
            
            // storing the id in session
            $_SESSION["id"] = $id;  
            
            // redirect to index
            redirect("mainController.php");
        }
    }

?>