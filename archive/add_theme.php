<?php
include_once 'includes/main.inc.php';

if (isset($_POST['addTheme'])) {

	$name = $_POST['name'];
	$acnym = $_POST['acnym'];
	$leader_netid = $_POST['leader_netid'];

	$name = trim(rtrim($name));
	$acnym = trim(rtrim($acnym));
	$leader_netid = trim(rtrim($leader_netid));
	$errors = 0;
	
	if ($name == "") {
		$nameMsg = "<br><b class='error'>Pleae enter Theme Name</b>";
		$errors++;
	}
	if (($acnym == "")) // || ($width > max_printer_width) whaat is max length?
	{
		$costMsg = "<br><b class='error'>Please enter a valid cost</b>";
		$errors++;
	}
	
	if (($width == "") || ($width > max_printer_width) || !(eregi("^[0-9]{1,2}$", $width))) {
		$widthMsg = "<br><b class='error'>Please enter a valid Width.  Maximum is " . max_printer_width . "</b>";
		$errors++;
	}
	
	if ($errors == 0) {
	
		addPaperType($db,$name,$cost,$width,$default);	
		header("Location: paperTypes.php");
	
	}

}

//include 'includes/header.inc.php';

?>

<form action='add_theme.php' method='post'>
<table>
	<tr>
		<td colspan='3' class='header'>Add New Theme</td>
	</tr>
	<tr>
		<td class='right'>Theme Name:</td>
		<td class='left'><input type='text' name='name' maxlength='40' value='<?php if (isset($name)) { echo $name; } ?>' /></td>
	</tr>
	<tr>
		<td class='right'>Acronym:</td>
		<td class='left'><input type='text' name='acnym' value='<?php if (isset($acnym)) { echo $acnym; } ?>' size='10'/> </td>
	</tr>
	<tr>
		<td class='right'>Theme Leader NetID:</td>
		<td class='left'>
        	<input type='text' name='leader_netid' 
            value='<?php if (isset($leader_netid)) { echo $leader_netid; } ?>' maxlength='8' size='10'/> 
        </td>
	</tr>
	
	<tr>
		<td class='right'></td>
		<td class='left'><input type='submit' name='addTheme' value='Submit' /></td>
	</tr>
	
</table>
</form>
<?php 

	if (isset($nameMsg)){echo $nameMsg; }
	if (isset($costMsg)){echo $costMsg; }
	if (isset($widthMsg)){echo $widthMsg; }
?>
<?php include_once 'includes/footer.inc.php'; ?>
