<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$page_title = "IGB People Database"; 

require_once 'includes/header.inc.php'; 


$user_id = $_GET['user_id'];

$user = new user($db, $user_id);

$user_theme_result = $user->get_theme_history();


// User's name should be same for all records
$name = $user_theme_result[0]['first_name'] . " " . $user_theme_result[0]['last_name'] ;
echo("<h1>Theme History for $name</h1>");
echo("<table>");
echo("<tr><th>User</th><th>Theme</th><th>Start Date</th><th>End Date</th></tr>");
foreach($user_theme_result as $user_theme) {
    
    echo("<tr><td>");
    echo($user_theme['first_name'] . " " . $user_theme['last_name'] . "</td>");
    echo("<td>". $user_theme['theme_name']. "</td>");
    echo("<td>". date("Y-m-d", strtotime($user_theme['start_date'])). "</td>");
    echo("<td>". (($user_theme['end_date'] == null || $user_theme['end_date'] == 0) ? "present" : date("Y-m-d", strtotime($user_theme['end_date']))). "</td>");
    echo("</tr>");
}
echo("</table>");

require_once ("includes/footer.inc.php"); 