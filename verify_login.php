<?php
session_start();

error_reporting(E_ALL ^ E_NOTICE);
include('function.php');
include('sqlconn.php');

if (isset($_POST['txtuserid'])) {
	$txtUser = $_POST['txtuserid'];
} else {
	$txtUser = '';
}

if (isset($_POST['txtpass'])) {
	$txtPassword = $_POST['txtpass'];
} else {
	$txtPassword = '';
}


if (strlen($txtUser) > 0 ) {
	// get user detail
	$q_emp = "select a.user_name,a.password,a.branch_code,a.name,a.division_code,a.dept_code,a.access_right,
	a.branch_code,b.branch_name,a.division_code,c.division_name,a.dept_code,d.dept_name,a.buyerid
	from ref_users a left join ref_branch b on a.branch_code = b.branch_code
	left join ref_division c on a.division_code = c.division_code
	left join ref_department d on a.dept_code = d.dept_code
	where a.isactive = 1 and user_name = '{$txtUser}'  " ;
	$r_emp = mssql_query($q_emp);
	$e_row = mssql_fetch_array($r_emp);

	$lcUser       = trim($e_row["user_name"]);
	$lcPass       = trim($e_row["password"]);
	$lcbranch     = trim($e_row["branch_code"]);
	$lcbranchname = trim($e_row["branch_name"]);
	$lcusertype   = trim($e_row["access_right"]);
	$lcname       = trim($e_row["name"]);
	$lcdivcode    = trim($e_row["division_code"]);
	$lcdivname    = trim($e_row["division_name"]);
	$lcdeptcode   = trim($e_row['dept_code']);
	$lcdeptname   = trim($e_row["dept_name"]);
	$lcbuyerid    = trim($e_row["buyerid"]);

	if ($lcUser != $txtUser) {
		include('login.php');
?>
		<script type="text/javascript">alert('Invalid User ID!')</script>
<?php
	} else {
		include("sqlconn.php");
		$q_pass = "EXEC DECRYPT_PASS '{$lcPass}'";
		$r_pass = mssql_query($q_pass);
		$p_row = mssql_fetch_array($r_pass);

		if ($txtPassword == $p_row["pass"])
		{
		// store user details in session
			$_SESSION['user']        = $lcUser ;
			$_SESSION['type']        = $lcusertype ;
			$_SESSION['branch_code'] = $lcbranch ;
			$_SESSION['branch_name'] = $lcbranchname;
			$_SESSION['username']    = $lcname ;
			$_SESSION['dept_code']   = $lcdeptcode ;
			$_SESSION['dept_name']   = $lcdeptname ;
			$_SESSION['divcode']     = $lcdivcode;
			$_SESSION['divname']     = $lcdivname ;
			$_SESSION['lcbuyerid']   = $lcbuyerid;

			?>
			<script>
            		//window.open('deductionmain.php?s=1','','scrollbars=yes,resizable=yes,width=1024,height=768,left=0,top=0,dependent') ;
				//window.close('login.php')
				window.location.href = 'deductionmain.php?s=1';
			</script>
			<?php
		} else {
			include('login.php');
			?>
			<script type="text/javascript">
			<!--
				alert('Invalid Password!');
			//-->
			</script>
			<?php
		}

	}
} else {
	include('login.php');
?>
	<script type="text/javascript">
		alert('User ID is a required field');
	</script>
<?php
}
?>

