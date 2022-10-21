<?
session_start();
?>

<head>
	<SCRIPT LANGUAGE="JavaScript">
		<!-- Begin
		function placeFocus() {
			if (document.forms.length > 0) {
				var field = document.forms[0];
				for (i = 0; i < field.length; i++) {
					if (field.elements[i].type == "text") {
						document.forms[0].elements[i].focus();
						break;
					}
				}
			}
		}
		//  End 
		-->
	</script>
	<title>Debit Memo Deduction System</title>
	<style type="text/css">
		.style1 {
			color: #FFFFFF;
			font-size: 12px;
			font-weight: bold;
		}

		body {
			background: url(images/bg_body2.gif);
		}
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include('sqlconn.php');
	?>
	<!-- <script defer language="javascript" src="js/jquery-ui.js"></script> -->
	<!-- load latest build of jquery.js -->
	<script type="text/javascript" src="jquery.js"></script>
	<!-- load jquery.dimensions.js (a dependency) -->
	<script type="text/javascript" src="jquery.dimensions.js"></script>
	<!-- load jquery.gradient.js (this is what we're testing!) -->
	<script type="text/javascript" src="jquery.gradient.js"></script>
	<script type="text/javascript" charset="utf-8">
		$(function() {
			$('#userAgent').html(navigator.userAgent);
			$('#test1-flex').gradient({
				from: '003366',
				to: '333333',
				direction: 'horizontal'
			});
			$('#test2-flex').gradient({
				from: '003366',
				to: '333333',
				direction: 'vertical'
			});
			$('#test1').gradient({
				from: '003366',
				to: '333333',
				direction: 'horizontal'
			});
			$('#test2').gradient({
				from: '003366',
				to: '333333',
				direction: 'vertical'
			});
			$('#test3').gradient({
				from: '003366',
				to: '333333',
				direction: 'horizontal',
				length: 75
			});
			$('#test4').gradient({
				from: '003366',
				to: '333333',
				direction: 'horizontal',
				length: 75,
				position: 'bottom'
			});
			$('#test5').gradient({
				from: 'C0CFE2',
				to: 'ffffff',
				direction: 'vertical',
				length: 250
			});
			$('#test6').gradient({
				from: '003366',
				to: '333333',
				direction: 'vertical',
				length: 75,
				position: 'right'
			});
			$('#test7').gradient({
				from: 'C0CFE2',
				to: 'ffffff'
			});
		});
	</script>
	<!--style="filter:progid:DXImageTransform.Microsoft.Gradient(endColorstr='#ffffff', startColorstr='#C0CFE2', gradientType='0');" -->
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<link href="css/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css" />
	<script language="javascript" src="js/jquery-min.js"></script>

	<script type="text/javascript">
		$(function() {
			$('#cmdidclose').button({
				icons: {
					primary: 'ui-icon-closethick'
				}
			});
			$('#cmdidsub').button({
				icons: {
					primary: 'ui-icon-key'
				}
			});
			$('#cmdidclose').click(function() {
				x = confirm('Are you sure you want to close this?');
				if (x) {
					window.close();
				}
				return false;
			});
		});

		function validateme() {
			if (jQuery.trim($('#txtiduser').val()) == "") {
				alert("Please don't leave username blank!");
				$('#txtiduser').focus();
				return false;
			}
			if (jQuery.trim($('#txtiduser').val()) == "") {
				alert("Please don't leave password blank!");
				$('#txtidpass').focus();
				return false;
			}
			return true;
		}
	</script>
	<script type="text/javascript" language="javascript">
		document.onkeyup = function(e) {
			if (window.event) // IE
			{
				keyn = window.event.keyCode
				if (keyn == 113) {
					$('#asignup').css('visibility', 'visible');
				}
			} else if (e.which) // Netscape/Firefox/Opera
			{
				keyn = e.which
				if (keyn == 16 && 113) {
					$('#asignup').css('visibility', 'visible');
				}
			}
		}
	</script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" onLoad="placeFocus()" id="test7">
	<form name="login" action="verify_login.php" method="POST" onSubmit="return validateme()">
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		<table border="1" width="300" height="130" cellpadding="0" cellspacing="0" align="center" bordercolordark="#cccccc" bordercolorlight="#cccccc" bgcolor="#FFFFFF">
			<tr>
				<td valign="top">
					<table align="left" border="0" width="297" height="79" cellpadding="0" cellspacing="0">
						<tr>
							<td height="18" bgcolor="#7692AF">
								<p class="ui-state-default ui-corner-all ui-helper-clearfix" style="padding:4px;">
									<span class="ui-icon ui-icon-locked" style="float:left; margin:-2px 5px 0 0;"></span>
									LOG IN
								</p>
							</td>
						</tr>
						<tr>
							<td height="55" valign="top">
								<table border="0" width="290" align="center" height="44" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="5">&nbsp;</td>
									</tr>
									<tr>
										<td></td>
										<td>User ID </td>
										<td>
											<input type="text" name="txtuserid" id="txtiduser" maxlength="30" size="30" />
										</td>
										<td></td>

									</tr>
									<tr>
										<td></td>
										<td>Password</td>
										<td><input type="password" name="txtpass" id="txtidpass" maxlength="20" size="30" /></td>
										<td></td>
									</tr>
									<tr>
										<td colspan="5"></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table border="0" width="290" height="35" cellpadding="0" cellspacing="0">

									<tr>
										<td width="132" height="18" align="center"><a id="asignup" href="register.php" style="visibility: hidden;">Sign-Up</a></td>

										<td width="100" align="right">
											<button name="btnclose" id="cmdidclose" title="Close" value="Close">Close</button>
										</td>

										<td width="117" align="center"><button type="submit" name="btn" id="cmdidsub" title="Login" value="Login">Login</button></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

	</form>
</body>

</html>