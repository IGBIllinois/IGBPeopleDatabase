
    <?php 

$page_title = "IGB People Database Reports"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';
?>
<script>
$(document).ready(function(){

  $("a#adv_search_text").click(function(){
  $("div#adv_search").toggle();
  });
  

	$('#search_results').dataTable( {
		"bPaginate": true,
		"sPaginationType": "full_numbers",
		"bLengthChange": false,
		"bFilter": false,
		"bSort": false,
		"bInfo": false,
		"bAutoWidth": true } );
  

});

$(window).bind('beforeunload',function(){
    if(1){ //whatever check you wish to do
        //return 'Are you sure you want to leave?';
    }
});
$(window).unload(function(){
    $.ajax({
        type: 'POST',
        url: 'reports.php',
        data: 'value=unload',
        async: false // this will not work if set to 'true'
    });
    alert('done');
});

</script>
<?php
echo("<h1> Reports </h1>");
echo("<form method=POST action=reports.php>");
echo("<BR><input type='submit' name='forwarding' value='Forwarding Addresses for Alumni' style='width:250px'/>");
echo("<BR><BR><input type='submit' name='peopledbusers' value='IGB People Database users' style='width:250px'/>");
echo("</form>");

$user = new user($db);

if($_POST['value']=='unload'){
        try {

            if(file_exists("excel/databaseUsers.xls")) {
            unlink("excel/databaseUsers.xls"); // remove every file
            }
            if(file_exists("excel/forwardingAddresses.xls")) {
            unlink("excel/forwardingAddresses.xls"); // remove every file
            }

        } catch(Exception $e) {
//            echo($e);
//            echo($e->getMessage());
        }

}

if(isset($_POST['forwarding'])) {
    $results = $user->get_forwarding_addresses();
    //print_r($results);
    write_forwarding_address_report($results, $db, "forwardingAddresses.xls");
}

if(isset($_POST['peopledbusers'])) {
    $results = $user->get_database_users();
    //print_r($results);
    write_database_users_report($results, $db, "databaseUsers.xls");
}

include ("includes/footer.inc.php"); 

