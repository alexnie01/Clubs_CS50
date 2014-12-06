<?php
    /* 
     * Contains all functions related to SQL database querying:
     * info on clubs, user lookup, My Interest/Club's I'm In retrieval, updates
     * and removals.
     */
    
    // need import for SQL query function mostly
    require("config.php");
    
    // prep user-input for searching
    function universal_search()
    {
        // standard values for variables user entered into mainTemplate
        $search = $_POST["search"];
        $divisions = [$_POST["division"]];
        $size = $_POST["size"];
        $leadership = ($_POST["leadership"]);
        $min_hours = $_POST["min_hours"];
        $max_hours = $_POST["max_hours"];
        $deadline = $_POST["deadline"];
        $comp = true;
        
        /**
         * checks if user actually entered a specific division. If yes, use blank then add wildcard
         * through concatenation in SQL so that all categories will be matched upon SQL query
         */
        if($_POST["division"] == "All Categories")
        {
            $divisions = [''];
        }
        
        // checks if user specified size. If not, 0 returns all clubs.size
        if($size == "All Sizes")
        {
            $size = 0;
        }
        
        /** checks if user wants only clubs with no comp. if user does not check box then
         *  nocomp will not be passed through POST by HTML. Only activates when user
         *  wants no comps only, so set $comp to false. Setting $comp to true returns
         *  all clubs while setting it to false returns clubs only with no comp due to the logic
         *  in the SQL query. Written this way because people who care about comp are mostly
         *  interested in whether a club does NOT have a comp.
         */  
        if(array_key_exists("nocomp", $_POST))
        {
            $comp = false;
        }
        
        // checks if user entered in max_hours argument
        if(sizeof($max_hours) == 0)
        {
            // assumed no clubs would have >1000 hours per week since 24*7 << 1000
            $max_hours = 1000;
        }
        
        // checks if user entered in min_hours argument
        if(sizeof($min_hours) == 0)
        {
            // returns all clubs since they must have non-negative hour commitment
            $min_hours = 0;
        }  
        
        // checks if user entered in deadline argument
        if(sizeof($deadline) == "")
        {
            $deadline = "1000-01-01";
        }
        
        // return search information
        return search($search, $size, $comp, $min_hours, $max_hours, $leadership, $deadline, $divisions);
    }
    
    /** returns complete info of all clubs matching intial search conditions
     *  need to pass in all divisions and tags as $divisions array (optional fuure implementation)
     */
    function search($query, $size, $comp, $min_hours, $max_hours, $leadership, $deadline, $divisions)
    {
        // array to store all results
        $results = [];
        
        /**
         * Iterates through for each division selected (currently limited to 1) and matches attributes across
         * clubs and tags tables. Used JOIN to allow simulataneous searches.
         * To deal with multiple instances arising from each club due to non-unique JOIN over tags.id, used
         * array_unique().
         */
        foreach($divisions as $division)
        {
            // stores lookup for one tag
            $result = array_unique(query("SELECT * FROM clubs JOIN tags ON (clubs.id = tags.id) WHERE 
                (clubs.name LIKE CONCAT(?,'%') OR tags.tag LIKE CONCAT(?,'%')) AND
                (clubs.size = ? OR ? = 0) AND
                clubs.avghours >= ? AND
                clubs.avghours <= ? AND
                (clubs.leadership = ? OR ? = 0)", $query, $query, $size, $size, $min_hours, $max_hours, $leadership, $leadership), SORT_REGULAR);
           /*     
                (clubs.comp = ? OR ? = TRUE) AND
                clubs.avghours <= ? AND
                clubs.avghours >= ? AND
                (clubs.leadership = ? OR ? = 0) AND
                tags.tag LIKE CONCAT(?,'*'),
                $query, $query, $size, $size, $comp, $comp, $min_hours, $max_hours, $leadership, $leadership, $division), SORT_REGULAR);
            */
            //MATCH (name) AGAINST (CONCAT(?,'*') IN NATURAL LANGUAGE MODE)", $query), SORT_REGULAR);
            /**
            $result = array_unique(query("SELECT clubs.* FROM clubs JOIN tags ON (clubs.id = tags.id) WHERE
                MATCH (clubs.name) AGAINST (? WITH QUERY EXPANSION)", $query), SORT_REGULAR);
            */
            /*
            WHERE
                MATCH (clubs.name) AGAINST (? WITH QUERY EXPANSION) OR
                MATCH (tags.tag) AGAINST (? WITH QUERY EXPANSION) AND
                clubs.size = ? AND
                clubs.comp = ? AND
                clubs.avghours <= ? AND
                clubs.avghours >= ? AND
                clubs.leadership = ? AND
                (clubs.deadline = 1 OR TRUE) AND
                MATCH (tags.tag) AGAINST (? WITH QUERY EXPANSION)", 
                $query, $query, $size, $comp, $min_hours, $max_hours, $leadership, $deadline, $division));
            */
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
        
        return array_unique($results, SORT_REGULAR);
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
        return query("SELECT user_id, password FROM users WHERE username = ? AND password = ?", $username, $password);
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
