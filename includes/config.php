<?php

    /**
     * config.php
     *
     * CS50 FINAL PROJECT
     *
     * Configures pages.
     */

    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    // requirements
    require("constants.php");
    require("functions.php");
    
    

    // URL that CS50 ID should ask users to trust; must be a prefix of RETURN_TO and
    // must be registered with CS50, per https://manual.cs50.net/ID
    define("TRUST_ROOT", "http://www.harvardclubs.net/");

    // URL to which CS50 ID should return users;
    // must be registered with CS50, per https://manual.cs50.net/id/
    define("RETURN_TO", "http://www.harvardclubs.net/return_to.php");

    // CS50 Library; ideally, this should not be inside public_html (or DocumentRoot)
    require("CS50.php");

    // enable sessions to track information in $_SESSION
    session_start();

    // require authentication for all pages except /login.php, /logout.php, and /register.php
    if (!in_array($_SERVER["PHP_SELF"], ["/login.php", "/logout.php", "/register.php", "/mainController.php"]))
    {
        if (empty($_SESSION["id"]))
        {
            redirect("login.php");
        }
    }

?>
