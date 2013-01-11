<?php #alpha

$page_title = "IGB People Database Search"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';

if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}

?>

<script>



$(document).ready(function(){




 $("ul#directory").show();
 
 	 $('.dir').css("z-index","-1");
 	 $('.themetable').hide();

 
 	 $(".themebutton").click(function(){
									  
									 		  
          var theme = $(this).attr("id");
		 
		  	$('#'+theme+'.themetable').dataTable( {
							"bPaginate": true,
							"sPaginationType": "full_numbers",
							"bLengthChange": false,
							"bFilter": false,
							"bSort": false,
							"bInfo": false,
							"bRetrieve": true,
							"bAutoWidth": true } );
		  
		  
		$('.dir').css("z-index","-1");
		$('#'+theme+'.dir').css("z-index","1");
		
		$('.themetable').hide();
		$('.dataTables_wrapper').hide();
		$('#'+theme+'.themetable').show();
         $('#'+theme+'_wrapper').show();      
        }); 
 
});








</script>



<?php




$theme_list = $db->query($select_theme);


$table_html = "";


	
$user = new user($db);
$html = "";

	foreach( $theme_list as $key=>$option )
    {
		
		$filters = array();
		$filters["users.theme_id"] = array($option['theme_id'], "WHERE");
		$filters["users.theme_1_id"] = array($option['theme_id'], "OR");
		$filters["users.theme_2_id"] = array($option['theme_id'], "OR");
		
		$search_results = $user->search($filters);
		
		if (count($search_results) == 0) { 
		  	$html .= "<input type='button' name='".$option['theme_id']."' id='".$option['short_name']."' value='".$option['short_name']."' 
					class='themebutton disabled' > ";
	  	}
		else {
			$html .= "<input type='button' name='".$option['theme_id']."' id='".$option['short_name']."' value='".$option['short_name']."' 
					class='themebutton' > ";
	
			$theme_html .= "<div class='dir' id='".$option['short_name']."'>";
			$theme_html .= result_table( $option['short_name'], $search_results, "themetable" );
			$theme_html .= "</div>";
		
		}
		
		

		
    }


	
	



?> 



<h1> IGB Theme Directory </h1>
<br>
<h3></h3>

<div class="section">


	<?php 
	
	echo $html;
    ?> 
</div>




	<?php 
    echo $theme_html; 
	?> 



 
<?php 

include ("includes/footer.inc.php"); 

?> 
