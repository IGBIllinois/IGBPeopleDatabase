<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$page_title = "IGB People Database"; 

require_once 'includes/header.inc.php'; 


$user_id = $_GET['user_id'];

$query = "SELECT users.first_name as first_name, users.last_name as last_name, themes.name as theme_name, user_theme.start_date as start_date, user_theme.end_date as end_date from
        (users LEFT JOIN user_theme on users.user_id = user_theme.user_id)
        LEFT JOIN themes on user_theme.theme_id=themes.theme_id where (user_theme.theme_id is not null and user_theme.theme_id != 0) and ". (($user_id == null) ? " " : " users.user_id='". $user_id . "' and") . " (user_theme.start_date is not null or user_theme.end_date is not null) order by active, user_theme.start_date";

$user_theme_result = $db->query($query);


// User's name should be same for all records
$name = $user_theme_result[0]['first_name'] . " " . $user_theme_result[0]['last_name'] ;
echo("<h1>Theme History for $name</h1>");
echo("<table>");
echo("<tr><td>User</td><td>Theme</td><td>Start Date</td><td>End Date</td></tr>");
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