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
 	$('.alphatable').hide();
	$('#A.alphatable').dataTable( {
					"bPaginate": true,
					"sPaginationType": "full_numbers",
					"bLengthChange": false,
					"bFilter": false,
					"bSort": false,
					"bInfo": false,
					"bRetrieve": true,
					"bAutoWidth": true } );
	$('#A.alphatable').show();
	$('#A_wrapper').show(); 
	$('#A.dir').css("z-index","1");
		
 
 	 $(".alphabutton").click(function(){
									  
									 		  
          var letter = $(this).attr("id");
		 
		  	$('#'+letter+'.alphatable').dataTable( {
							"bPaginate": true,
							"sPaginationType": "full_numbers",
							"bLengthChange": false,
							"bFilter": false,
							"bSort": false,
							"bInfo": false,
							"bRetrieve": true,
							"bAutoWidth": true } );
		  
		  
		/*$('#'+letter+'_wrapper').toggle();*/
		$('.alphatable').hide();
		$('.dataTables_wrapper').hide();
		$('.dir').css("z-index","-1");
		$('#'+letter+'.dir').css("z-index","1");
		$('#'+letter+'.alphatable').show();
         $('#'+letter+'_wrapper').show();      
        }); 
 
});








</script>



<?php


//variables
$user_enabled = 1;




$table_html = "";

$filters = NULL;
	
$user = new user($db);
$html = "";

	for($i=0; $i<26; $i++){
		
		$letter = $alphabet[$i];
		$search_results = $user->alpha_search($letter);
		
		if (count($search_results) == 0) { 
		  	$html .= "<input type='button' name='".$letter."' id='".$letter."' value='".$letter."' 
					class='alphabutton disabled' > ";
	  	}
		else {
			$html .= "<input type='button' name='".$letter."' id='".$letter."' value='".$letter."' 
					class='alphabutton' > ";
	
			$letter_html .= "<div class='dir' id='".$letter."'>";
			$letter_html .= result_table( $letter, $search_results, "alphatable" );
			$letter_html .= "</div>";
		
		}
		


	}
	



?> 



<h1> IGB Alpha Directory </h1>
<br>
<h3></h3>

<div class="section">


	<?php 
	
	echo $html;
    ?> 
</div>




	<?php 
    echo $letter_html; 
	?> 



 
<?php 

include ("includes/footer.inc.php"); 

?> 
