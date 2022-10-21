<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>

<script type="text/javascript" src="js/bsn.AutoSuggest_c_2.0.js"></script>
<link rel="stylesheet" href="css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />

</head>
<?php
if ($_POST['txtx'] != '') {
	echo $txtx = $_POST['txtx'];
} 
?>
<body>
<div id="wrapper">
<div id="content">
<div>
<form method="post" action="">
	<small style="float:right">Hidden ID Field: <input type="text" id="testid" value="" style="font-size: 10px; width: 20px;" disabled="disabled" /></small>
	<label for="testinput">Person</label>
	<input style="width: 200px" type="text" id="testinput" value="<?php echo $txtx;?>" name="txtx" /> 
	<input type="submit" value="submit" />
</form>
</div>
</div>
</div>
<script type="text/javascript">
	var options = {
		script:"test.php?json=true&",
		varname:"input",
		json:true,
		callback: function (obj) { document.getElementById('testid').value = obj.id; }
	};
	var as_json = new AutoSuggest('testinput', options);
	
	
	var options_xml = {
		script:"test.php?",
		varname:"input"
	};
	var as_xml = new AutoSuggest('testinput_xml', options_xml);
</script>
</body>
</html>
