/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){

        // header.inc.php
          $("a#directory").click(function(){
            $("ul#directory").toggle();
          });

          $("a#admin").click(function(){
            $("ul#admin").toggle();
          });

          $("#uin").mask("999999999");
          $(".phone").mask("(999) 999-9999");
  



        //add.php
	$('a#search').colorbox({width:"80%", inline:true, height:"90%", href:"#display_search"});


        //alphadir.php
	$('.alphatable').dataTable( {
					"bPaginate": true,
					"sPaginationType": "full_numbers",
					"bLengthChange": false,
					"bFilter": false,
					"bSort": false,
					"bInfo": false,
					"bRetrieve": true,
					"bAutoWidth": false } );
                                    
                                    
        // dept_edit.                            
                                    
        // key_edit.php

	$('#key_list_table').dataTable( {
		"bPaginate": true,
		"bLengthChange": true,
		"aLengthMenu": [[10, 15, 20], [10, 15, 20]],
		"bFilter": true,
		"bSort": false,
		"bInfo": true,
		"bAutoWidth": false } );
	
	var r;
	function confirm_change()
	{
		r=confirm("Are you sure you want to change this status?");
	}
				
	$('#key_change').submit(function(){ 
			confirm_change();
			if(r==false){
				return false;
			}	 
		});


        // more_info.php
        $('input#add_key').colorbox({width:"40%", height:"35%", inline:true, href:"#add_key_html"});	
	$('input#return_key').colorbox({width:"40%", height:"35%", inline:true, href:"#return_key_html"});	
	
	$('a.key_edit').click(function(){
		var keyid = $(this).attr("id");
		$.colorbox({width:"40%", height:"35%", inline:true, href:"#edit_"+keyid+""});
	});


	$('#active_key_data').dataTable( {
		"bPaginate": true,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": false,
		"bInfo": true,
		"bAutoWidth": true } );
	
	$('#inactive_key_data').dataTable( {
		"bPaginate": true,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": false,
		"bInfo": true,
		"bAutoWidth": true } );
            
            
        // profile.php
        var r2;
	function confirm_remove()
	{
		r2=confirm("Are you sure you want to remove this member?");
		
		if(r2==false){
				$.colorbox.close();
		}
	}
	
	$('input#remove').colorbox({width:"50%", height:"50%", inline:true, href:"#remove_html",
				onComplete:function(){ confirm_remove(); }

	});
            
            
        // reports.php
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

        
     
    // theme_edit.php

     $('#type_list_table').dataTable( {
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": false,
		"bInfo": false,
		"bAutoWidth": false } );

	var r3;
	function confirm_change()
	{
		r3=confirm("Are you sure you want to change this status?");
	}
				
	$('#edit_type').submit(function(){ 
			confirm_change();
			if(r3==false){
				return false;
			}	 
		});
});