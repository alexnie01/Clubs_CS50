<?php
    /* 
     * Contains all functions related to SQL database querying:
     * info on clubs, user lookup, My Interest/Club's I'm In retrieval, updates
     * and removals.
     */
    
    // need import for SQL query function mostly
    require("config.php");
    
    // returns complete info of all clubs matching intial search conditions
    function search($hahaha)
    {
        //return query("SELECT clubs.* FROM JOIN(clubs, tags) WHERE
        
    }
    
    // user registration
    function register($username, $password)
    {
        // inserts new user: automatically assigned auto-incremented user_id
        return query("INSERT INTO users (username, password) VALUES (?, ?)", $username, $password);
    }
    
    // verifies that user is in database and inputted correct password
    function login($username, $password)
    {
        return query("SELECT user_id FROM users WHERE username = ? AND password = ?", $username, $password);
    }
    
    
    
    

?>
