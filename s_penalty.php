<?php

include 'sqlconn.php';

$bm = microtime(true);

session_start();

if (!$_SESSION['user']) {
	die('No Direct Access Allowed!');
}

date_default_timezone_set('Asia/Manila');

$lcuser  = $_SESSION['user'];
$lcusername = $_SESSION['username'];
$branch =  $_SESSION['branch_code'];
$xbranch =  $_SESSION['branch_code'];
$glbranchname = $_SESSION['branch_name'];
$dept_code = $_SESSION['dept_code'];
$lcdeptname = $_SESSION['dept_name'];
$division_code = $_SESSION['divcode'];
$lcdivname = $_SESSION['divname'];
$lcaccrights = $_SESSION['type'];

// statically set the months
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

/**
 * Settings
 */
$settings['division_code'] = 'SP';

// DS-Codes must be exempted
$exempt = array(
	"'S801'",
	"'S802'",
	"'S803'",
);

// $brquery 	= " select * from ref_branch where branch_code not in (".implode(', ', $exempt).") and isactive=1 order by branch_code ";
$brquery 	= " select case when branch_code='S399' then 'S306' else branch_code end as branch_code,
		branch_name, branch_prefix, cntl_prefix,
		isactive, dmno, dmno_last_fr, dmno_last_to, audit_user, audit_date, branch_known_name
		from ref_branch where branch_code not in ( " . implode(', ', $exempt) . " ) and isactive=1
		order by branch_code ";
$brresult 	= mssql_query($brquery);

$branchlist 	= array();
$brlist 		= array();

while ($row = mssql_fetch_object($brresult)) {
	$row->branch_code = trim($row->branch_code);
	$branchlist[] = $row; // header top
	$brlist[] = "'" . $row->branch_code . "'";
}

// memory release
mssql_free_result($brresult);

$category 		= array();

// if another category was added, it must be with this format
$category[] 	= "'C000000011'";

// get the subcategory
$subquery 	= " select * from ref_subcategory where category_code in (" . implode(', ', $category) . ") ";
$subresult 	= mssql_query($subquery);

$subcatlist = array(); // header left side
$tcontents	= array(); // holder for the data

$query = "";

$mcount = 0;

// catch the method
$input = (!empty($_POST)) ? $_POST : $_GET;


// passing objects to array to be access like $sample->properties
while ($row1 = mssql_fetch_object($subresult)) {
	$subcatlist[] = $row1;
}

// memory release
mssql_free_result($subresult);

// check if the selected month and year is same with the present
if (!empty($_POST) or !empty($_GET)) {

	$monthyear 	= $input['year'] . $input['month'];

	if (date('Ym') == $monthyear) {
		$period_covered = date('Y/m') . '/01 - ' . date('Y/m/d');
		$lastday = date('Y/m/d');
	} else {
		$lastday = date('Y/m/d', strtotime('-1 second', strtotime('+1 month', strtotime($input['year'] . '/' . $input['month'] . '/01 00:00:00'))));
		$period_covered = $input['year'] . '/' . $input['month'] . '/01 - ' . $lastday;
	}

	$query = "select a.subcat_code, a.subcat_name, sum(b.amount) as amt, upper(b.branch_code) as branch_code from ref_subcategory a
				left join deduction_master b on a.subcat_code=b.subcat_code and b.branch_code not in (" . implode(', ', $exempt) . ") and isactive=1
				where a.category_code in (" . implode(', ', $category) . ") and b.division_code='" . $settings['division_code'] . "'
				and b.dept_code='OPS' and
				convert(char(6), b.review_date, 112) = '" . $monthyear . "' and vposted=1
				group by a.subcat_code, a.subcat_name, b.branch_code order by a.subcat_code, b.branch_code";

	$result = mssql_query($query);

	while ($mrow = mssql_fetch_object($result)) {
		// overwrite S399
		if (trim($mrow->branch_code) == 'S399') {
			$mrow->branch_code = 'S306';
		}
		$mrow->branch_code = trim($mrow->branch_code);
		$tcontents[$mrow->subcat_code][$mrow->branch_code] = $mrow->amt;
	}

	// check if the request is down
	if ($input['download'] == 'yes') {
		// we will execute the excel import
		$title = "SP_Summary_" . $monthyear;

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
		if ($Use_Title == 1) {
			echo ("$title\n");
		}

		print("SUMMARY OF PENALTY REPORTS - " . date('F', strtotime($lastday)) . "\n");
		print("Period Covered : " . $period_covered . "\n");
		print("\n");
		//define separator (defines columns in excel & tabs in word)
		$sep = "\t"; //tabbed character
		//start of printing column names as names of MSSQL fields
		echo "Report Type \t";

		foreach ($branchlist as $br) :
			echo trim($br->branch_prefix) . "\t";
		endforeach;

		print("\n");
		//end of printing column names
		//start while loop to get data

		foreach ($subcatlist as $subcat) :
			$schema_insert = "";
			$schema_insert .= trim($subcat->subcat_name) . $sep;
			foreach ($branchlist as $b) :
				$schema_insert .= number_format($tcontents[$subcat->subcat_code][$b->branch_code], 2) . $sep;
			endforeach;
			$schema_insert = str_replace($sep . "$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";
		endforeach;

		exit();
	}
}

function &load_class($class)
{
	static $objects = array();

	require('class.encrypt.php');

	$name = $class;
	$objects[$class] = &instantiate_class(new $name());
	return $objects[$class];
}

function &instantiate_class(&$class_object)
{
	return $class_object;
}

// load the encryptor
$enc = &load_class('Ever_Encrypt');

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Supermarket Penalty Summary Reports <?php echo date('F-Y') . ' as of ' . date('Y/m/d H:i:s'); ?></title>
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>
<style type="text/css">
	body {
		padding-top: 60px;
	}

	body,
	p {
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

	.s-content {
		width: 3800px;
	}

	/*css for ie6*/
	/*
	.row { margin:0; }

	.span1,
	.span2,
	.span3,
	.span4,
	.span5,
	.span6,
	.span7,
	.span8,
	.span9,
	.span10,
	.span11,
	.span12 { float:left ; margin-left:20px; display:inline; }

	.span1.first-child,
	.span2.first-child,
	.span3.first-child,
	.span4.first-child,
	.span5.first-child,
	.span6.first-child,
	.span7.first-child,
	.span8.first-child,
	.span9.first-child,
	.span10.first-child,
	.span11.first-child,
	.span12.first-child { margin-left:0; }

	.span1 { width: 60px; }
	.span2 { width: 140px; }
	.span3 { width: 220px; }
	.span4 { width: 300px; }
	.span5 { width: 380px; }
	.span6 { width: 460px; }
	.span7 { width: 540px; }
	.span8 { width: 620px; }
	.span9 { width: 700px; }
	.span10 { width: 780px; }
	.span11 { width: 860px; }
	.span12, .container { width: 940px; }

	.offset1 { margin-left: 100px; }
	.offset2 { margin-left: 180px; }
	.offset3 { margin-left: 260px; }
	.offset4 { margin-left: 340px; }
	.offset5 { margin-left: 420px; }
	.offset6 { margin-left: 500px; }
	.offset7 { margin-left: 580px; }
	.offset8 { margin-left: 660px; }
	.offset9 { margin-left: 740px; }
	.offset10 { margin-left: 820px; }
	.offset11 { margin-left: 900px; }
	*/
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
				<a class="brand" href="./index.html">Supermarket Penalty Summary Reports</a>
				<ul class="nav pull-right">
					<li class="dropdown" id="menu2">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#menu2">
							<i class="icon-user icon-white"></i>&nbsp;&nbsp; <?php echo $lcusername; ?>
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a href="register.php?/edit/<?php echo $enc->encode(SELF); ?>"><i class="icon-edit"></i> Edit Profile</a></li>
							<li><a href="login.php"><i class="icon-off"></i> LogOut</a></li>
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
						<a href="?year=<?php echo $input['year'] . '&month=' . $input['month']; ?>&download=yes" id="adownload" class="btn btn-success"><i class="icon-download-alt icon-white"></i> Download Results</a>
					</div>

					<ul>
						<li>
							<div class="input-prepend">
								<span class="add-on">Year &nbsp;&nbsp;</span>
								<input type="text" class="span1" name="year" value="<?php echo (empty($input['year'])) ? date('Y') : $input['year']; ?>" id="input01" style="text-align: center;">&nbsp;&nbsp;
							</div>
						</li>
						<li>
							<div class="input-prepend">
								<span class="add-on">Month </span>
								<select name="month" id="idmonth" class="span2">
									<?php
									foreach ($dmonth as $key => $val) :
										$selected = '';
										if ($input['month'] == $key) :
											$selected = "selected='selected'";
										endif;
										echo "<option value='{$key}' {$selected}>{$val}</option>";
									endforeach;
									?>
								</select>
								<?php if (!empty($period_covered) or trim($period_covered) !== '') : ?>
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
	</div>
	<div class="s-content">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th class="blue">Report Type</th>
					<?php
					foreach ($branchlist as $br) :
						// $val = (strtoupper(trim($br->branch_code)) !== 'S399') ? '': '<em> (S306) </em>';
						echo "<th class='blue'>" . $br->branch_code . ' - ' . $br->branch_known_name . "</th>";
					endforeach;
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($subcatlist) && !empty($input)) :
					foreach ($subcatlist as $subcat) :
				?>
						<tr>
							<td><?php echo trim($subcat->subcat_name); ?></td>
							<?php
							foreach ($branchlist as $b) :
								${$b->branch_code} = ${$b->branch_code} + $tcontents[$subcat->subcat_code][$b->branch_code];
							?>
								<td style="text-align: right;"><?php echo number_format($tcontents[$subcat->subcat_code][$b->branch_code], 2); ?></td>
							<?php
							endforeach;
							?>
						</tr>
				<?php
					endforeach;
				endif;
				?>
			</tbody>
			<tfoot>
				<tr>
					<td><strong>TOTAL</strong>&nbsp;</td>
					<?php
					foreach ($branchlist as $brn) :
					?>
						<td style="text-align: right;"><strong><?php echo number_format(${$brn->branch_code}, 2); ?></strong></td>
					<?php endforeach; ?>
				</tr>
			</tfoot>
		</table>
	</div>

	<div class="container">
		<div class="row">
			<div class="span12">
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<div>
					<p>
						EVER-ITD. Copyright &copy; <?php echo date('Y'); ?>.
						<em><?php echo 'Page loads in ' . number_format((microtime(true) - $bm), 4) . ' seconds'; ?></em>
					</p>
				</div>
			</div>
		</div>
	</div>
	<script src="js/jquery1.7.js"></script>
	<script src="js/bootstrap.js"></script>
	<script type="text/javascript">
		(function() {
			$('#adownload').on('click', function(e) {
				var numrow = <?php echo count($tcontents); ?>,
					target = $('.mheader'),
					notifier = $('.notifier');

				if (numrow == 0) {
					notifier.children('.close').trigger('click');
					target.prepend('<div class="notifier alert alert-error">No data to download!<a href="#" class="close" data-dismiss="alert">&times;</a></div>');
					e.preventDefault();
				}
			});
		})();

		// script for IE6
		// ;(function() {
		// 	if ($.browser.msie === true && $.browser.version === '6.0') {  // anti pattern
		// 		// fix spans
		// 		$('.row div[class^="span"]:first-child').not('[class*="offset"]').addClass('first-child');

		// 		// fix offsets
		// 		$('.row div[class*="offset"]:first-child').each(function () {
		// 			var margin_left = parseInt($(this).css('margin-left'), 10) - 20;
		// 			$(this).css('margin-left', margin_left);
		// 		});
		// 	}
		// })();
	</script>
</body>

</html>