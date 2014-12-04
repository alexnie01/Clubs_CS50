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
               
        //check that the stock symbol exists and that the number of shares to be sold is not more than shares owned
        if((XXXX)
        {
            //insert into MySQL
            $result=query("INSERT INTO tablesXX () VALUES (?,?,?)");
            
            //return to portfolio page
            redirect("index.php");
            
        } else
        {
            Apologize("Search is invalid.");
        }
    }
       
?>
