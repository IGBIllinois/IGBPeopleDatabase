
    <?php 

$page_title = "IGB People Database Reports"; 

require_once 'includes/header.inc.php'; 


echo("<h1> Reports </h1>");
?>

<BR>
<form class='form-inline' action='report.php' method='post'>

 <input  type='submit'  name='forwarding' value='Forwarding Addresses for Alumni' style='width:250px'/>
  <select
                name='report_type' class='input-medium'>
                <option value='xlsx'>Excel 2007</option>
                <option value='csv'>CSV</option>
        </select>

</form>
<BR>
<form class='form-inline' action='report.php' method='post'>

    <input  type='submit' name='peopledbusers' value='IGB People Database users' style='width:250px'/>
  <select
                name='report_type' class='input-medium'>
                <option value='xlsx'>Excel 2007</option>
                <option value='csv'>CSV</option>
        </select>

</form>

<?php


require_once ("includes/footer.inc.php"); 

