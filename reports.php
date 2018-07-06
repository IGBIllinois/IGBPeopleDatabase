
    <?php 

$page_title = "IGB People Database Reports"; 

include 'includes/header.inc.php'; 

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
?>

<BR>
<form class='form-inline' action='report.php' method='post'>

 <input  type='submit'  name='forwarding' value='Forwarding Addresses for Alumni' style='width:250px'/>
  <select
                name='report_type' class='input-medium'>
                <option value='xls'>Excel 2003</option>
                <option value='xlsx'>Excel 2007</option>
                <option value='csv'>CSV</option>
        </select>

</form>
<BR>
<form class='form-inline' action='report.php' method='post'>

    <input  type='submit' name='peopledbusers' value='IGB People Database users' style='width:250px'/>
  <select
                name='report_type' class='input-medium'>
                <option value='xls'>Excel 2003</option>
                <option value='xlsx'>Excel 2007</option>
                <option value='csv'>CSV</option>
        </select>

</form>

<?php


include ("includes/footer.inc.php"); 

