 <?php

    // configuration
    require("../includes/config.php");
    
    // if user reached via POST
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        render("index.php", ["title" => "Search"];
    }
    
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //get variables user entered into mainTemplate
        $search = ($_POST["search"]);
        $category = ($_POST["category"]);
        $size = ($_POST["size"]);
        $leadership = ($_POST["leadership"]);
        $nocomp = ($_POST["nocomp"]);
        $minhours = ($_POST["minhours"]);
        $maxhours = ($_POST["maxhours"]);
        $deadline = ($_POST["deadline"]);
        
        // make inputted values all lowercase
        $search = array_map('strtolower', $search)
        
        if($search == "")
        {
            $search = "*";
        }
        
        if($category == "All Categories")
        {
            $category = "*";
        }
        
        if($size == "All Sizes")
        {
            $size = "*";
        }
        
        if($nocomp == "no")
        {
            $nocomp = "*";
        }
                
        if($maxhours == "")
        {
            $maxhours = 1000;
        }
          
        if($minhours == "")
        {
            $maxhours = 0;
        }  
        
        if($deadline == "")
        {
            $deadline = "*";
        }    
        
        if($category == "")
        {
            $category = "*";
        } 
       
        // insert into MySQL
        $searchresult=query("SELECT clubs.* FROM JOIN (clubs, divisions, tags) WHERE (
            MATCH(clubs.name) AGAINST (? WITH QUERY EXPANSION) 
            AND clubs.size = ? 
            AND clubs.leadership = ? 
            AND clubs.comp  = ? 
            AND clubs.avghours <= ? 
            AND clubs.avghours >= ? 
            AND clubs.deadline >= ?
            AND division.category = ? 
            AND MATCH(tags.tag) AGAINST (? WITH QUERY EXPANSION) 
            AND clubs.id = division.id 
            AND clubs.id = tags.id)", 
            $search, $size, $leadership, $nocomp, $maxhours, $minhours, $deadline, $category, $search);
            
        // retrieve function: given a $table variable and $user_id, retrieve from this $table
        function retrieve($table, $user_id) {
            return query("SELECT clubs.* FROM JOIN (users, ?) WHERE (
            ?.user_id = ? AND
            ?.id = clubs.id AND"),
            $table, $table, $SESSION("id"), $table);
            }
            
        // deletion funtion
        function deletions($table) {
            return query("DELETE FROM ?.user_id = ? AND ?.id = ?"),
            $table, $user_id, $table, $id);
            }
            
        //return to mainTemplate page
        render("../templates/mainTemplate.html", ["searchhistory" => $searchhistory, ..]);
            
        //debugging:
        foreach($searchresults as $row) {
            foreach($row as $element){
                echo("<script>console.log('PHP: ".json_encode($searchresults[$rows[$element]].", ")."');</script>");
                console.log();
            }
            echo("<script>console.log('PHP: "."\n"."');</script>");
        }
    }
       
?>
