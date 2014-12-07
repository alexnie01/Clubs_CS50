// dump info of clubs returned by search
var results = <?php echo json_encode($search_results); ?>;
var infos = <?php echo json_encode($info); ?>;

$(".clickableRow").click(function() {
    // club id of clicked row
    var id = $(this).data("id");
    // empty variable to hold club in scope
    var club;
    var club_info;

    // search for table information of clicked row
    for (var result in results)
    {
        if(results[result]['id'] == id)
        {
            club = results[result];
            break;
        }
    }

    // search results for correct information of clicked row
    for (var info in infos)
    {
        if(infos[info]['id'] == id)
        {
            club_info = infos[info];
            break;
        }
    }
    // modal output for title
    document.getElementById("Modal-Title").innerHTML = club['name'];

    /**
     *  modal output for descriptions: decided against iterating through arrays since 
     *  certain attributes need special formatting
     */
    document.getElementById("modal-id").innerHTML =
        "<p align='center'>Website: " + "<a href=\'http://www." + club_info['website'] + "\'>" + club_info['website'] + "</a></p>" +
        "<p><b>Description: </b>" + club_info['description']+"</p>" +
        "<p><b>Comp: </b>" + club['comp'] + "</p>" +
        "<p><b>Contact Info: </b>" + club_info['contact'] + "</p>" +
        "<p><b>Meeting Time: </b>" + club_info['meeting time'] + "</p>" +
        "<p><b>Meeting Place: </b>" + club_info['meeting place'] + "</p>" +
        "<p><b>Website </b>" + club_info['website'] + "</p>" +
        "<p><b>Size: </b>" + club['size'] + "</p>" +
        "<p><b>Average Hours/Week: </b>" + club['avghours'] + "</p>" +
        "<p><b>Application Deadline: </b>" + club['deadline'] + "</p>";
});