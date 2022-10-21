<?php

	include "sqlconn.php";

	$bm = microtime(true);

	session_start();

	if(!$_SESSION['user'])
	{
		die('No Direct Access Allowed!');
	}

	date_default_timezone_set('Asia/Manila');

	$lcuser  = $_SESSION['user'] ;
	$lcusername = $_SESSION['username'] ;
	$branch =  $_SESSION['branch_code'] ;
	$xbranch =  $_SESSION['branch_code'] ;
	$glbranchname = $_SESSION['branch_name'];
	$dept_code = $_SESSION['dept_code'] ;
	$lcdeptname = $_SESSION['dept_name'];
	$division_code = $_SESSION['divcode'];
	$lcdivname = $_SESSION['divname'];
	$lcaccrights = $_SESSION['type'];

	$dmonth = array(
		'01' => 'January',
		'02' => 'February',
		'03' => 'March',
		'04' => 'April',
		'05' => 'May',
		'06' => 'June',
		'07' => 'July',
		'08' => 'August',
		'09' => 'September',
		'10' => 'October',
		'11' => 'November',
		'12' => 'December',
	);

	$settings['division_code'] = 'SP';

	if (!empty($_POST) OR !empty($_GET))
	{

		// redirect to the the results
		$input = (!empty($_POST)) ? $_POST: $_GET;

		$catcode 	= 'C000000011';
		$subcat 	= 'S000000019';

		$yearmonth 	= $input['year'].$input['month'];
		$current 	= date('Y/m/d H:i:s');
		$qrymonth 	= date('F Y');

		$sqlquery = " select b.branch_name, case when ltrim(rtrim(a.branch_code))='S399' then 'S306' else a.branch_code end as branch_code
			 , b.branch_known_name, sum(a.amount) as total,
			 (sum(a.amount) * 0.4) as stotal, (sum(a.amount) * 0.1) as sstotal
			 from deduction_master a
			 left join ref_branch b on a.branch_code=b.branch_code
			 left join deduction_slip_print_details dspd on a.dm_no=dspd.dm_ctrl_no
			 left join deduction_slip_prints dsp on dspd.deduction_slip_prints_id=dsp.id
			 where a.category_code='$catcode' and a.division_code='".$settings['division_code']."'
			 and a.subcat_code='".$subcat."' and a.vposted=1 and a.dept_code='OPS' and
			 ( convert(char(6), a.review_date, 112) = '{$yearmonth}' or convert(char(6), ltrim(rtrim(cast(dsp.printed_date as varchar)))) = '{$yearmonth}' )
			 group by a.branch_code,
			 b.branch_name, b.branch_known_name order by a.branch_code ";

			 // convert(char(6), a.review_date, 112) = '$yearmonth'

		$sqlresult 	= mssql_query($sqlquery);

		$data 	= array();
		$branches 	= array();

		while($row = mssql_fetch_object($sqlresult))
		{
			$data[] = $row;
			$branches[] = "'".$row->branch_code."'";
		}

		$branches[] = "'S801'";
		$branches[] = "'S802'";
		$branches[] = "'S803'";

		// add the S399 to exempted since its already been add as S306
		$branches[] = "'S399'";

		/**
		 * This part will just append the branch not found above
		 */
		$branchqry 	= " select * from ref_branch where branch_code not in ( ".implode(', ', $branches)." ) and isactive=1 ";

		$branchres 	= mssql_query($branchqry);

		while($br = mssql_fetch_object($branchres))
		{
			$m = new stdClass;
			$m->branch_name	= $br->branch_name;
			$m->branch_code	= $br->branch_code;
			$m->branch_known_name	= $br->branch_known_name;
			$m->total 	= number_format(0, 2);
			$m->stotal 	= number_format(0, 2);
			$m->sstotal = number_format(0, 2);

			$data[] = $m;
		}

		usort($data, 'cmp');

		// check if the selected month and year is same with the present
		if (date('Ym') == $yearmonth)
		{
			$period_covered = date('Y/m').'/01 - '.date('Y/m/d');
			$lastday = date('Y/m/d');
		}
		else
		{
			$lastday = date('Y/m/d', strtotime('-1 second', strtotime('+1 month', strtotime($input['year'].'/'.$input['month'].'/01 00:00:00'))));
			$period_covered = $input['year'].'/'.$input['month'].'/01 - '.$lastday;
		}

		if ($input['download'] == 'yes')
		{
			// we will execute the excel import
			$title = "SP_Receiving_Income_".$yearmonth;

			$file_type = "vnd.ms-excel";
			$file_ending = "xls";
			$app = "application/";

			header("Content-Type: $app$file_type");
			header("Content-Disposition: attachment; filename=$title.$file_ending");
			header("Pragma: no-cache");
			header("Expires: 0");

			$fields = array();
			/*    FORMATTING FOR EXCEL DOCUMENTS ('.xls')   */
			//create title with timestamp:
			if ($Use_Title == 1){
				echo("$title\n");
			}

			print("Supermarket Receiving Income - ".date('F', strtotime($lastday))."\n");
			print("Period Covered : ".$period_covered."\n");
			print("\n");
			//define separator (defines columns in excel & tabs in word)
			$sep = "\t"; //tabbed character
			//start of printing column names as names of MSSQL fields
			$col = mssql_num_fields($sqlresult);
			for ($i = 0; $i < $col; $i++) {
				if (mssql_field_name($sqlresult,$i) == 'total'):
					echo "Total Receiving Income" . "\t";
				elseif(mssql_field_name($sqlresult,$i) == 'stotal'):
					echo "40% (for invty losses)" . "\t";
				elseif(mssql_field_name($sqlresult,$i) == 'sstotal'):
					echo "10% (store incentive)" . "\t";
				else:
					echo mssql_field_name($sqlresult,$i) . "\t";
				endif;
				$fields[] = mssql_field_name($sqlresult,$i);
			}
			print("\n");
			//end of printing column names
			//start while loop to get data

			foreach($data as $d)
			{
				$schema_insert = "";
				for($j=0; $j<mssql_num_fields($sqlresult);$j++)
				{
					if(!isset($d->{$fields[$j]}))
					{
						$schema_insert .= "NULL".$sep;
					}
					elseif ($d->{$fields[$j]} != "") {
						if ($j>=2)
						{
							$schema_insert .= number_format($d->{$fields[$j]}, 2).$sep;
						}
						else
						{
							$schema_insert .= $d->{$fields[$j]}.$sep;
						}
					}
					else
					{
						$schema_insert .= ".$sep";
					}
				}
				$schema_insert = str_replace($sep."$", "", $schema_insert);
				$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
				$schema_insert .= "\t";
				print(trim($schema_insert));
				print "\n";
			}

			exit();

		}

	}

	function cmp($a, $b)
	{
		if($a->branch_code == $b->branch_code) return 0;
		return ($a->branch_code < $b->branch_code) ? -1: 1;
	}

	function &load_class($class)
	{
		static $objects = array();

		require('class.encrypt.php');

		$name = $class;
		$objects[$class] =& instantiate_class(new $name());
		return $objects[$class];
	}

	function &instantiate_class(&$class_object)
	{
		return $class_object;
	}

	// load the encryptor
	$enc =& load_class('Ever_Encrypt');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Supermarket Receiving Income <?php echo date('F-Y').' as of '.date('Y/m/d H:i:s'); ?></title>
	<link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>
<style type="text/css">
	body {
		padding-top: 60px;
	}

	body, p {
		font-family: "Trebuchet MS", Arial, sans-serif;
		font-size: 11px;
	}

	button {
		font-family: "Trebuchet MS", Arial, sans-serif;
	}

	table .blue {
		color: #049cdb;
		border-bottom-color: #049cdb;
		text-align: center;
		font-weight: 900;
		font-size: 12px;
	}

	ul {
		list-style: none;
		margin-left: 0;
	}

	table tfoot tr td {
		color: #FF8040;
		font-weight: bolder;
		font-size: 13px;
	}

</style>
<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="./index.html">Supermarket Receiving Income</a>
				<ul class="nav pull-right">
				  <li class="dropdown" id="menu2">
				    <a class="dropdown-toggle" data-toggle="dropdown" href="#menu2">
				      <i class="icon-user icon-white"></i>&nbsp;&nbsp; <?php echo $lcusername; ?>
				      <b class="caret"></b>
				    </a>
				    <ul class="dropdown-menu">
				      <li><a href="register.php?/edit/<?php echo $enc->encode(SELF); ?>"><i class="icon-edit"></i> Edit Profile</a></li>
				      <li><a href="#"><i class="icon-off"></i> LogOut</a></li>
				    </ul>
				  </li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="span12 mheader">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">

					<div class="pull-right">
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn"><i class="icon-refresh"></i> Reset/Clear</a>
						<a href="deductionmain.php" class="btn"><i class="icon-arrow-left"></i> Back</a>
						<a href="?year=<?php echo $input['year'].'&month='.$input['month']; ?>&download=yes" id="adownload" class="btn btn-success"><i class="icon-download-alt icon-white"></i> Download Results</a>
					</div>

					<ul>
						<li>
							<div class="input-prepend">
								<span class="add-on">Year &nbsp;&nbsp;</span>
								<input type="text" class="span1" name="year" value="<?php echo (empty($input['year'])) ? date('Y'): $input['year']; ?>" id="input01" style="text-align: center;">&nbsp;&nbsp;
							</div>
						</li>
						<li>
							<div class="input-prepend">
								<span class="add-on">Month </span>
								<select name="month" id="idmonth" class="span2">
									<?php
										foreach($dmonth as $key => $val):
											$selected = '';
											if ($input['month'] == $key):
												$selected = "selected='selected'";
											endif;
											echo "<option value='{$key}' {$selected}>{$val}</option>";
										endforeach;
									?>
								</select>
								<?php if(!empty($period_covered) OR trim($period_covered) !== ''): ?>
								<span> &nbsp;&nbsp;Period Covered: <em class="label label-success">&nbsp;&nbsp;<?php echo str_replace('-', '&mdash;', $period_covered); ?>&nbsp;&nbsp;</em></span>
								<?php endif; ?>
							</div>
						</li>
						<li>
							<div>
								<button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> Load Data/Refresh</button>
							</div>
						</li>
					</ul>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="blue">Branch</th>
							<th class="blue">Site</th>
							<th class="blue">Total Receiving Income</th>
							<th class="blue">40% (for Invty losses)</th>
							<th class="blue">10% (store incentive)</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if(!empty($data)):
								$gtotal 	= 0;
								$gstotal 	= 0;
								$gsstotal 	= 0;
								foreach($data as $mrow):
									$gtotal 	+= $mrow->total;
									$gstotal 	+= $mrow->stotal;
									$gsstotal 	+= $mrow->sstotal;
						?>
						<tr>
							<td><?php echo $mrow->branch_known_name; ?></td>
							<td style="text-align: center;"><?php echo $mrow->branch_code; ?></td>
							<td style="text-align: right;"><?php echo number_format($mrow->total, 2); ?></td>
							<td style="text-align: right;"><?php echo number_format($mrow->stotal, 2); ?></td>
							<td style="text-align: right;"><?php echo number_format($mrow->sstotal, 2); ?></td>
						</tr>
						<?php
								endforeach;
							endif;
						?>
					</tbody>
					<tfoot>
						<tr>
							<td><strong>TOTAL</strong>&nbsp;</td>
							<td>&nbsp;</td>
							<td style="text-align: right;"><strong><?php echo number_format($gtotal, 2); ?></strong></td>
							<td style="text-align: right;"><strong><?php echo number_format($gstotal, 2); ?></strong></td>
							<td style="text-align: right;"><strong><?php echo number_format($gsstotal, 2); ?></strong></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<div>
					<p>
						EVER-ITD. Copyright &copy; <?php echo date('Y'); ?>.
						<em><?php echo 'Page loads in '.number_format((microtime(true)-$bm), 4).' seconds'; ?></em>
					</p>
				</div>
			</div>
		</div>
	</div>
	<script src="js/jquery1.7.js"></script>
	<script src="js/bootstrap.js"></script>
	<script type="text/javascript">
		(function(){
			$('#adownload').on('click', function(e){
				var numrow = <?php echo count($data); ?>,
					target = $('.mheader'),
					notifier = $('.notifier');

				if (numrow == 0)
				{
					notifier.children('.close').trigger('click');
					target.prepend('<div class="notifier alert alert-error">No data to download!<a href="#" class="close" data-dismiss="alert">&times;</a></div>');
					e.preventDefault();
				}
			});
		})();

	</script>
</body>
</html>
