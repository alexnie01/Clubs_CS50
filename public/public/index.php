<?php

    // configuration
    require("../includes/config.php"); 

?>

<!DOCTYPE html>

<html lang="en-us">
  <head>
    <meta charset="utf-8">
    <title>CS50 Clubs</title>
  </head>
  <body>

    <?php 
    
        if (isset($_SESSION["user"]))
        {
            echo "<div>You are logged in.  <a href='logout.php'>Log out</a>.</div>";
            echo "<div>Your unique ID for this site is <b>" . htmlspecialchars($_SESSION["user"]["identity"]) . "</b>.</div>";
            if (isset($_SESSION["user"]["fullname"]))
                echo "<div>Your name is <b>" . htmlspecialchars($_SESSION["user"]["fullname"]) . "</b>.</div>";
            if (isset($_SESSION["user"]["email"]))
                echo "<div>Your email address is <b>" . htmlspecialchars($_SESSION["user"]["email"]) . "</b>.</div>";
        }
        else
            echo "You are not logged in.  <a href='templogin.php'>Log in</a>.";
  
      ?>


    </ul>
  </body>
</html>

