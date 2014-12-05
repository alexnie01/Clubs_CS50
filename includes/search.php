<?php
    /* 
     * Contains all functions related to SQL database querying:
     * info on clubs, user lookup, My Interest/Club's I'm In retrieval, updates
     * and removals.
     */
    
    // need import for SQL query function mostly
    require("config.php");
    
    // prep user-input for searching
    function prep_search()
    {
        // standard values for variables user entered into mainTemplate
        $search = $_POST["search"];
        $divisions = [$_POST["category"]];
        $size = $_POST["size"];
        $leadership = ($_POST["leadership"]);
        $min_hours = $_POST["min_hours"];
        $max_hours = $_POST["max_hours"];
        $deadline = $_POST["deadline"];
        
        // wildcard returns all clubs regardless of comp, so default returns all clubs
        $comp = '*';
        
        // casework for entries without user input
        
        if($search == "")
        {
            $search = "*";
        }
        
        /**
         * checks if user actually entered a specific category. If yes, uses wildcard
         * so that all categories will be matched upon SQL query
         */
        if($_POST["category"] == "All Categories")
        {
            $divisions = ['*'];
        }
        
        if($size == "All Sizes")
        {
            $size = "*";
        }
        // checks if user wants only clubs with no comp
        if($_POST["nocomp"] == "yes")
        {
            $comp = "false";
        }
                
        if($max_hours == "")
        {
            // assumed no clubs would have >1000 hours per week since 24*7 << 1000
            $max_hours = 1000;
        }
          
        if($min_hours == "")
        {
            // returns all clubs since they must have non-negative hour commitment
            $min_hours = 0;
        }  
        
        if($deadline == "")
        {
            $deadline = "*";
        }    
        
        if($category == "")
        {
            $category = "*";
        }
        
        // return search information
        return search($query, $size, $comp, $min_hours, $max_hours, $leadership, $deadline, $divisions);
    }
    
    /** returns complete info of all clubs matching intial search conditions
     *  need to pass in all divisions and tags as $divisions array (optional fuure implementation)
     */
    function search($query, $size, $comp, $min_hours, $max_hours, $leadership, $deadline, $divisions)
    {
        // array to store all results
        $results = [];
        
        /**
         * Initially looks for matches among everything except tags and returns table in nested query.
         * Then iterates through that table and returns subset of that with single tag match.
         * After all of this, returns array of unique results.
         */
        foreach($divisions as $division)
        {
            // stores lookup for one tag
            $result = array_unique(query("SELECT clubs.* FROM JOIN clubs ON tags WHERE
                MATCH AGAINST (clubs.name, tags.tag) AGAINST (?* WITH QUERY EXPANSION) AND
                clubs.size = ? AND
                clubs.comp = ? AND
                clubs.avghours <= ? AND
                clubs.avghours >= ? AND
                clubs.leadership = ? AND
                clubs.deadline = ? AND
                MATCH (tags.tag) AGAINST (?* WITH QUERY EXPANSION)", 
                $query, $size, $comp, $min_hours, $max_hours, $leadership, $deadline, $division));
            
            // push each club in the query result into $results
            foreach($result as $club)
            {
                array_push($results, $club);
            }
        }
        
        // no results returned
        if(empty($results))
        {
            return -1;
        }
        
        return array_unique($results);
    }
    // add club to special tables: interest, history or club's I'm in by club_id
    function add_special($table, $user_id, $club_id)
    {
        // history needs special stuff, but is same for now
        if($table = "history")
        {
            return query("INSERT INTO history (user_id, id) VALUES (?, ?)", $user_id, $club_id);
        }
        
        return query("INSERT INTO ? (user_id, id) VALUES (?, ?)", $user_id, $club_id);
    }
    
    // deletes a club from special table
    function remove_special($table, $user_id, $club_id)
    {
        return query("DELETE FROM ? WHERE ?.user_id = ? AND ?.id = ?", $user_id, $club_id);
    }
    
    // user registration
    function register($username, $password)
    {
        // inserts new user: automatically assigned auto-incremented user_id
        return query("INSERT INTO users (username, password) VALUES (?, ?)", $username, $password);
    }
    
    // verifies that user is in database and inputted correct password. Can also be used for lookups
    function lookup_user($username, $password)
    {
        return query("SELECT user_id FROM users WHERE username = ? AND password = ?", $username, $password);
    }
    
    /** Deletes user from database. Checked that user was in database even though logged in to make sure
     *  multiple requests cannot be submitted. Automaticaly logs out afterwards. Returns -1 if error, 0 
     * upon success.
     */
    function delete_user($user_id)
    {
        $user_info = query("SELECT user_id FROM users WHERE user_id = ?", $user_id);
        
        // make sure $user_info is not empty, so user is actually in database
        if (empty($user_info))
        {
            return -1;
        }
        /** purges user from all tables: used multiple queries since MySQL lacks
         *  support for FULL JOIN
         */
        query("DELETE FROM users WHERE user_id = ?", $user_id);
        query("DELETE FROM interests WHERE user_id = ?", $user_id);
        query("DELETE FROM history WHERE user_id = ?", $user_id);
        
        return 0;
    }
?>