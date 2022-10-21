<?php

include "sqlconn.php";

$bm = microtime(true);

session_start();

if (!$_SESSION['user']) {
	die('No Direct Access Allowed!');
}

date_default_timezone_set('Asia/Manila');

$lcuser        = $_SESSION['user'];
$lcusername    = $_SESSION['username'];
$branch        =  $_SESSION['branch_code'];
$xbranch       =  $_SESSION['branch_code'];
$glbranchname  = $_SESSION['branch_name'];
$dept_code     = $_SESSION['dept_code'];
$lcdeptname    = $_SESSION['dept_name'];
$division_code = $_SESSION['divcode'];
$lcdivname     = $_SESSION['divname'];
$lcaccrights   = $_SESSION['type'];

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



// var_dump($_GET);
// die();
$branches[] = "'S801'";
$branches[] = "'S802'";
$branches[] = "'S803'";

$branchqry 	= " select case when branch_code='S399' then 'S306' else branch_code end as branch_code,
		branch_name, branch_prefix, cntl_prefix,
		isactive, dmno, dmno_last_fr, dmno_last_to, audit_user, audit_date, branch_known_name
		from ref_branch where branch_code not in ( " . implode(', ', $branches) . " ) and isactive=1
		order by branch_code ";

$branchres 	= mssql_query($branchqry);

$branch = array();
$sites	= array();
while ($brrow = mssql_fetch_object($branchres)) {
	$branch[] = $brrow;
	$sites[]  = trim($brrow->branch_code);
}

// departments
$sqldept = " select * from ref_department ";
$resdept = mssql_query($sqldept);
$depts = array();

// configurations
$settings['dept_code']     = '%';
$settings['division_code'] = 'SP';

// category references
// $sqlcategory 	= " select * from ref_category where dept_code='".$dept_code."' ";
$sqlcategory 	= " select * from ref_category ";
$rescategory 	= mssql_query($sqlcategory);

$input = !empty($_POST) ? $_POST : $_GET;

// only for download because the chk has been serialize
if ($_GET['chk'])
	$input['chk'] = unserialize(urldecode($input['chk']));

$category  = array();
$ccategory = array();
$selected_cat = array();
while ($rptcat = mssql_fetch_object($rescategory)) {

	// checker for setting the list of selected category_codes
	if (isset($input['chk']) and in_array(trim($rptcat->category_code), $input['chk'])) {
		$rptcat->chk = ' checked="checked" ';
		$selected_cat[] = '"' . trim($rptcat->category_code) . '"';
	}

	$category[] = $rptcat;
	$ccategory[] = "'" . $rptcat->category_code . "'"; // appending & prepending singles qoute, can be used in implode
}

// only for download because the chk has been serialize
if ($_GET['chkdept'])
	$input['chkdept'] = unserialize(urldecode($input['chkdept']));

while ($dept = mssql_fetch_object($resdept)) {
	// checker for setting the list of selected category_codes
	if (isset($input['chkdept']) and in_array(trim($dept->dept_code), $input['chkdept'])) {
		$dept->chk = ' checked="checked" ';
		$selected_dept[] = '\'' . trim($dept->dept_code) . '\'';
	}
	$depts['record_dept'][] = $dept;
}

// status
$statuses 	= array();
$cstatuses 	= array();
$statquery 	= " select * from ref_status where vposted not in(4,5) ";
$statres 	= mssql_query($statquery);
while ($statrow = mssql_fetch_object($statres)) {
	$statuses[] = $statrow;
	$cstatuses[] = "'" . $statrow->vposted . "'";
}





// this part handles the events
if (!empty($input)) {

	$yearmonth = $input['year'] . $input['month'];

	$load_status = ($input['status'] == 'all') ? "in (" . implode(', ', $cstatuses) . ")" : "= " . $input['status'];
	$load_status = ('= ' . $input['status'] === '= 6') ? '= 1' : $load_status;

	if (date('Ym') == $yearmonth) {
		$period_covered = date('Y/m') . '/01 - ' . date('Y/m/d');
		$lastday = date('Y/m/d');
	} else {
		$lastday = date('Y/m/d', strtotime('-1 second', strtotime('+1 month', strtotime($input['year'] . '/' . $input['month'] . '/01 00:00:00'))));
		$period_covered = $input['year'] . '/' . $input['month'] . '/01 - ' . $lastday;
	}

	// status switching
	$selected_status = '';
	switch ($input['status']) {
		case 1:
			$selected_status = " convert(char(6), a.review_date, 112) = '{$yearmonth}' ";
			$status_label    = "Approved Date";
			$dField          = "a.review_date";
			break;

		case 6:
			$selected_status = " convert(char(6), ltrim(rtrim(cast(dsp.printed_date as varchar)))) = '{$yearmonth}' ";
			$status_label    = "Printed Date";
			$dField          = "cast(cast(dsp.printed_date as varchar(10)) as datetime) as review_date";
			break;

		default:
			$selected_status = " ( convert(char(6), a.review_date, 112) = '{$yearmonth}'
					OR convert(char(6), ltrim(rtrim(cast(dsp.printed_date as varchar)))) = '{$yearmonth}' ) ";
			$status_label    = "Approved/Printed Date";
			$dField 				 = "a.review_date";
			break;
	}

	$top = '';

	$top = ($top == '' or !isset($input['top'])) ? 200 : $input['top'] * 200;

	$top = ($input['download'] == 'yes') ? '' : $top;

	$sqlreports = "select distinct :top :select
			from deduction_master a
			left join ref_buyer b on a.buyerid=b.buyerid
			left join ref_payment c on a.paymentid=c.paymentid
			left join ref_category d on a.category_code=d.category_code
			left join deduction_slip_print_details dspd on dspd.dm_ctrl_no=a.dm_no
			left join deduction_slip_prints dsp on dsp.id=dspd.deduction_slip_prints_id
			left join ref_prod_dept e on a.department = e.deptcode
			where a.division_code='" . $settings['division_code'] . "' and
			a.vposted {$load_status} and :stats
			:wherein :category :dept
			order by a.suppliername, a.review_date, a.branch_code";

	$select = "a.dm_no, a.dm_no_acctg, a.branch_code, a.division_code, a.dept_code, a.category_code,
			a.subcat_code, $dField, a.promo, a.vendorcode, a.suppliername, a.dm_date, a.period,
			a.amount, a.remarks1, b.buyer_code, c.paymentdesc, d.category_name, dsp.printed_date,ltrim(rtrim(a.department))+' - '+ltrim(rtrim(e.deptname)) as deptname ";

	// $sqlreports = str_replace(':top', 'top 200', $sqlreports);
	$sqlreports = str_replace(':top', '', $sqlreports);
	$sqlreports = str_replace(':select', $select, $sqlreports);
	$sqlreports = str_replace(':wherein', $wherein, $sqlreports);

	// categories
	$selected_cat = (isset($input['chk'])) ? ' and a.category_code in ('
		. implode(', ', $selected_cat) . ')' : '';
	$selected_dep = (isset($input['chkdept'])) ? ' and a.dept_code in ('
		. implode(', ', $selected_dept) . ')' : ' and a.dept_code like \'%' . $settings['dept_code'] . '%\'';

	$sqlreports = str_replace(':category', $selected_cat, $sqlreports);
	$sqlreports = str_replace(':dept', $selected_dep, $sqlreports);
	$sqlreports = str_replace(':stats', $selected_status, $sqlreports);


	$resreports = mssql_query($sqlreports);

	$reports 	= array();
	$xsites = array();

	// excel generation
	if ($input['download'] == 'yes') {
		while ($rpt = mssql_fetch_object($resreports)) {
			// overwrite the S399
			if (trim($rpt->branch_code) == 'S399') {
				$rpt->branch_code = 'S306';
			}
			$reports[] = $rpt;
		}

		$title = "SP_Receiving_Income_" . $yearmonth;

		$file_type = "vnd.ms-excel";
		$file_ending = "xls";
		$app = "application/";
		//
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

		print("Supermarket Receiving Income - " . date('F', strtotime($lastday)) . "\n");
		print("Period Covered : " . $period_covered . "\n");
		print("\n");
		//define separator (defines columns in excel & tabs in word)
		$sep = "\t"; //tabbed character
		//start of printing column names as names of MSSQL fields

		// generate header
		echo ":: \t";
		echo "Ref # \t";
		echo "DM No. \t";
		echo "Type \t";
		echo "Type of Deductions \t";
		echo "Promo\t";
		echo "Buy \t";
		echo "Itm Dept \t";
		echo "Supplier Code \t";
		echo "Supplier Name \t";

		foreach ($branch as $dbr) {
			echo $dbr->branch_code . " \t";
		}

		echo "Total \t";
		echo "Date Encoded \t";
		echo $status_label . " \t";
		echo "Type \t";

		print("\n");
		//end of printing column names
		//start while loop to get data

		foreach ($reports as $drpt) {
			${trim($drpt->branch_code)} = $drpt->amount;

			$schema_insert = "";

			$schema_insert .= $input['month'] . "\t";
			$schema_insert .= $drpt->dm_no . "\t";
			$schema_insert .= $drpt->dm_no_acctg . "\t";
			$schema_insert .= $drpt->category_name . "\t";
			$schema_insert .= $drpt->remarks1 . "\t";
			$schema_insert .= $drpt->promo . "\t";
			$schema_insert .= $drpt->buyer_code . "\t";
			$schema_insert .= $drpt->deptname . "\t";
			$schema_insert .= $drpt->vendorcode . "\t";
			$schema_insert .= trim(preg_replace('([$#])', '', $drpt->suppliername)) . "\t";

			$_temp = '';
			// for($x=0, $y=count($sites)-1; $x <= $y; $x++)
			// {
			// 	$_temp = empty(${$sites[$x]}) ? '': number_format(${$sites[$x]}, 2);
			// 	$schema_insert .= $_temp."\t";
			// }

			foreach ($sites as $site) {
				$_temp = empty(${trim($site)}) ? '' : number_format(${trim($site)}, 2);
				$schema_insert .= $_temp . "\t";
			}

			$schema_insert .= $drpt->amount . "\t";
			$schema_insert .= date('M-d-Y', strtotime($drpt->dm_date)) . "\t";
			$schema_insert .= date('M-d-Y', strtotime($drpt->review_date)) . (('Approved/Printed Date' === $status_label) ? '/' . date('M-d-Y') : '') . "\t";
			$schema_insert .= $drpt->paymentdesc . "\t";

			$schema_insert = str_replace($sep . "$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			${trim($drpt->branch_code)} = '';
			print(trim($schema_insert));
			print "\n";
		}

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

global $cachefile;
$cachefilename = 'reports-' . md5(implode('.', $input));

$cachefile = $cachefilename . '.cache';

$cachetime = 60; // 1min cache time

if (file_exists($cachefile) and time() - $cachetime < filemtime($cachefile)) {
	// $data = readfile($cachefile);
	// $data = unserialize($data);
	// exit;
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Total Deductions <?php echo date('F-Y') . ' as of ' . date('Y/m/d H:i:s'); ?></title>
	<link href="css/bootstrap.css?<?php echo mt_rand(0, 32); ?>" rel="stylesheet">
	<link href="css/bootstrap-responsive.css?<?php echo mt_rand(0, 32); ?>" rel="stylesheet">
</head>
<style type="text/css">
	body {
		padding-top: 60px;
	}

	body,
	p,
	.categories label.checkbox,
	.department label.checkbox {
		font-family: "Trebuchet MS", Arial, sans-serif;
		font-size: 11px;
	}

	.btn {
		font-family: "Trebuchet MS", Arial, sans-serif;
		font-weight: 900;
		font-size: 11px;
	}

	table {
		font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
	}

	table .blue {
		color: #049cdb;
		border-bottom-color: #049cdb;
		text-align: center;
		font-size: 10px;
	}

	ul {
		list-style: none;
		margin-left: 0;
	}

	table tfoot tr td {
		color: #FF8040;
		font-weight: bolder;
		font-size: 12px;
	}

	.home.subtitle {
		color: #002E47;
		line-height: 1.5;
		font-size: 22px;
		font-weight: 400;
		margin-top: 10px;
		margin-bottom: 25px;
		text-shadow: 0px 1px 0px #0383B6;
	}

	.accordion-heading {
		-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, .1), inset 0px 1px 0px rgba(255, 255, 255, .1);
		-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, .1), inset 0px 1px 0px rgba(255, 255, 255, .1);
		box-shadow: 0px 1px 0px rgba(0, 0, 0, .1), inset 0px 1px 0px rgba(255, 255, 255, .1);
	}

	.in {
		border-radius-bottom-left: none;
		-webkit-border-radius-bottom-left: none;
		-moz-border-radius-bottom-left: none;
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
				<a class="brand" href="./index.html">Total Deductions</a>
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
						<a href="new/deduction/deductionmain.php" class="btn"><i class="icon-arrow-left"></i> Back</a>
						<a href="?year=<?php echo $input['year'] . '&month=' . $input['month'] . '&status=' . $input['status'] . '&chk=' . urlencode(serialize($input['chk'])) . '&chkdept=' . urlencode(serialize($input['chkdept'])); ?>&download=yes" id="adownload" class="btn btn-success"><i class="icon-download-alt icon-white"></i> Download Results</a>
					</div>

					<ul>
						<li>
							<div class="input-prepend">
								<span class="add-on"><i class="icon-calendar"></i> Year&nbsp;&nbsp;</span>
								<input type="text" class="span2" name="year" value="<?php echo (empty($input['year'])) ? date('Y') : $input['year']; ?>" id="input01" style="text-align: center;">&nbsp;&nbsp;
							</div>
						</li>
						<li>
							<div class="input-prepend">
								<span class="add-on"><i class="icon-calendar"></i> Month </span>
								<select name="month" id="idmonth" class="span2">
									<?php
									foreach ($dmonth as $key => $val) :
										$selected = '';
										if ($input['month'] == $key or (!isset($input['month']) and $key == date('m'))) :
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
							<div class="input-prepend">
								<span class="add-on"><i class="icon-cog"></i> Status</span>
								<select name="status" id="idstatus" class="span2">
									<option value="all" <?php echo (empty($input['status'])) ? 'selected="selected"' : ''; ?>>-All-</option>
									<?php
									foreach ($statuses as $stat) {
										$sel = ($input['status'] == $stat->vposted) ? 'selected="selected"' : '';
										echo '<option value="' . $stat->vposted . '" ' . $sel . '>' . $stat->description . '</option>';
									}
									?>
								</select>
							</div>
						</li>
						<li style="margin-bottom: 10px;">
							<span class="add-on"><i class="icon-indent-left"></i> Departments [ <a href="#dept" id="cmdtoggler2">Show</a> ]</span>
							<div class="controls department" style="padding: 0; margin: 0; display: none;">
								<?php
								foreach ($depts['record_dept'] as $dep) :
								?>
									<label class="checkbox">
										<input type="checkbox" name="chkdept[]" <?php echo $dep->chk; ?> value="<?php echo trim($dep->dept_code); ?>"> <?php echo $dep->dept_name; ?>
									</label>
								<?php
								endforeach;
								?>
							</div>
						</li>
						<li style="margin-bottom: 20px;">
							<span class="add-on"><i class="icon-list"></i> Categories [ <a href="#cat" id="cmdtoggler">Show</a> ]</span>
							<div class="controls categories" style="padding: 0; margin: 0; display: none;">
								<?php
								foreach ($category as $cat) :
								?>
									<label class="checkbox">
										<input type="checkbox" name="chk[]" <?php echo $cat->chk; ?> value="<?php echo trim($cat->category_code); ?>"> <?php echo $cat->category_name; ?>
									</label>
								<?php
								endforeach;
								?>
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
			<div class="span12" style="width: 3800px; padding-right: 10px;">

				<div class="accordion" id="accordion2">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse1"><?php echo 'Page # 1 - ' . $period_covered; ?></a>
						</div>
						<div class="accordion-body collapse" id="collapse1">
							<div class="accordion-inner" style="padding: 0;">
								<table class="table table-striped table-bordered" style="margin-bottom: 0 !important; border: none !important;">
									<thead>
										<tr>
											<th class="blue" width="20">::</th>
											<th class="blue">Ref #</th>
											<th class="blue">DM No.</th>
											<th class="blue">Type</th>
											<th class="blue">Type of Deductions</th>
											<th class="blue">Buy</th>
											<th class="blue">Itm Dept</th>
											<th class="blue">SupCode</th>
											<th class="blue">SupName</th>
											<?php
											foreach ($branch as $br) {
												echo '<th class="blue">' . $br->branch_code . '</th>';
											}
											?>
											<th class="blue">Total</th>
											<th class="blue">Date Encoded</th>
											<th class="blue">Date Approved</th>
											<th class="blue">Type</th>
										</tr>
									</thead>
									<tbody id="mcontent">
										<?php
										if (!is_null($resreports)) {
											$data    = array();
											$numrow  = mssql_num_rows($resreports);
											$no_page = floor($numrow / 200);

											$cnt = 1;
											$i   = 0;
											while ($rpt = mssql_fetch_object($resreports)) {
												if ($cnt === 200) {
													$i++;
													$cnt = 1;
												}

												if (trim($rpt->branch_code) == 'S399') {
													$rpt->branch_code = 'S306';
												}

												$rpt->{trim($rpt->branch_code)} = $rpt->amount;
												$data[$i]['dm' . $rpt->dm_no] = $rpt;
												foreach ($sites as $site) {
													if (trim($site) === trim($rpt->branch_code)) {
														$data[$i]['totals'][trim($site)] += $rpt->{trim($site)};
														$data['totals'][trim($site)]     += $rpt->{trim($site)};
													}
												}
												$cnt++;
											}
										}
										?>

										<?php
										if (isset($data[0])) :
											foreach ($data[0] as $batch) :
												if (is_object($batch)) :
										?>
													<tr id="data1">
														<td><?php echo $input['month']; ?></td>
														<td><?php echo $batch->dm_no; ?></td>
														<td><?php echo $batch->dm_no_acctg; ?></td>
														<td style="text-align: left;"><?php echo $batch->category_name; ?></td>
														<td style="text-align: left;"><?php echo $batch->remarks; ?></td>
														<td style="text-align: center;"><?php echo $batch->buyer_code; ?></td>
														<td style="text-align: center;"><?php echo $batch->deptname; ?></td>
														<td style="text-align: left;"><?php echo $batch->vendorcode; ?></td>
														<td style="text-align: left;" id="mid<?php echo $batch->dm_no; ?>"><?php echo preg_replace('([$#])', '', $batch->suppliername); ?></td>
														<?php
														foreach ($sites as $key => $site) {
															echo '<td style="text-align: right;">' . trim($batch->{trim($site)}) . '</td>';
														}
														?>
														<td style="text-align: right;"><?php echo $batch->amount; ?></td>
														<td style="text-align: right;"><?php echo date('M-d-Y', strtotime($batch->dm_date)); ?></td>
														<td style="text-align: right;"><?php echo date('M-d-Y', strtotime($batch->review_date)); ?></td>
														<td style="text-align: left;"><?php echo $batch->paymentdesc; ?></td>
													</tr>
										<?php endif;
											endforeach;
										endif; ?>
										<tr>
											<td colspan="2"><strong>SUB-TOTAL</strong>&nbsp;</td>
											<td colspan="6">&nbsp;</td>
											<?php
											foreach ($branch as $brr) {
												echo '<td style="text-align: right;">' . number_format($data[0]['totals'][trim($brr->branch_code)], 2) . '</td>';
											}
											?>
											<td style="text-align: right;"><?php echo number_format($total, 2); ?></td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="100%">&nbsp;</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<?php
					for ($a = 1, $b = $i; $a <= $b; $a++) :
					?>
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $a + 1; ?>">
									<?php echo 'Page # ' . ($a + 1) . ' - ' . $period_covered; ?>
								</a>
							</div>
							<div id="collapse<?php echo $a + 1; ?>" class="accordion-body collapse">
								<div class="accordion-inner" style="padding: 0;">
								</div>
							</div>
						</div>
					<?php endfor; ?>
					<div>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th class="blue" width="20%">SUMMARY OF TOTALS</th>
									<?php
									foreach ($branch as $br) {
										echo '<th class="blue">' . $br->branch_code . '</th>';
									}
									?>
									<th class="blue">All Branches</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td><strong>GRAND -TOTAL</strong>&nbsp;</td>
									<?php
									foreach ($branch as $brr) {
										echo '<td style="text-align: right;">' . number_format($data['totals'][trim($brr->branch_code)], 2) . '</td>';
										$ggtotal += $data['totals'][trim($brr->branch_code)];
									}
									?>
									<td style="text-align: right;"><?php echo number_format($ggtotal, 2); ?></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
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
	<script id="tbl-template" type="text/x-handlebars-template">
		<table class="table table-striped table-bordered" style="margin-bottom: 0 !important; border: none !important;">
			<thead>
				<tr>
					<th class="blue" width="20">::</th>
					<th class="blue">Ref #</th>
					<th class="blue">DM No.</th>
					<th class="blue">Type</th>
					<th class="blue">Type of Deductions</th>
					<th class="blue">Buy</th>
					<th class="blue">Itm Dept</th>
					<th class="blue">SupCode</th>
					<th class="blue">SupName</th>
					<?php
					foreach ($branch as $br) {
						echo '<th class="blue">' . $br->branch_code . '</th>';
					}
					?>
					<th class="blue">Total</th>
					<th class="blue">Date Encoded</th>
					<th class="blue">Date Approved</th>
					<th class="blue">Type</th>
				</tr>
			</thead>
			<tbody id="mcontent">
				{{{ content }}}
		     </tbody>
		     {{{ foot }}}
		</table>
	</script>
	<script id="entry-template" type="text/x-handlebars-template">
		<tr id="data1">
		<td><?php echo $input['month']; ?></td>
		<td>{{ dm_no }}</td>
		<td>{{ dm_no_acctg }}</td>
		<td style="text-align: left;">{{ category_name }}</td>
		<td style="text-align: left;">{{ remarks1 }}</td>
		<td style="text-align: center;">{{ buyer_code }}</td>
		<td style="text-align: center;">{{ deptname }}</td>
		<td style="text-align: left;">{{ vendorcode }}</td>
		<td style="text-align: left;" id="mid<?php echo $batch->dm_no; ?>">{{ suppliername }}</td>
		<?php
		foreach ($sites as $key => $site) {
			echo '<td style="text-align: right;">{{' . trim($site) . '}}</td>';
		}
		?>
		<td style="text-align: right;">{{ amount }}</td>
		<td style="text-align: right;">{{ dm_date }}</td>
		<td style="text-align: right;">{{ review_date }}</td>
		<td style="text-align: left;">{{ paymentdesc }}</td>
        </tr>
	</script>
	<script id="subtotal-template" type="text/x-handlebars-template">
		<tr>
			<td colspan="2"><strong>SUB-TOTAL</strong>&nbsp;</td>
			<td colspan="6">&nbsp;</td>
			<?php
			foreach ($branch as $brr) {
				echo '<td style="text-align: right;">{{' . trim($brr->branch_code) . '}}</td>';
			}
			?>
			<td style="text-align: right;">0.00</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</script>
	<script src="js/jquery1.7.js?<?php echo mt_rand(0, 32); ?>"></script>
	<script src="js/bootstrap.js?<?php echo mt_rand(0, 32); ?>"></script>
	<script src="js/underscorejs.js?<?php echo mt_rand(0, 32); ?>"></script>
	<script src="js/handlebars.js?<?php echo mt_rand(0, 32); ?>"></script>
	<script type="text/javascript">
		(function() {
			$('#adownload').on('click', function(e) {
				var numrow = <?php echo count($data); ?>,
					target = $('.mheader'),
					notifier = $('.notifier');

				if (numrow == 0) {
					notifier.children('.close').trigger('click');
					target.prepend('<div class="notifier alert alert-error">No data to download!<a href="#" class="close" data-dismiss="alert">&times;</a></div>');
					e.preventDefault();
				}
			});

			var mdata = <?php echo json_encode($data); ?>,
				tbltemplate = Handlebars.compile($('#tbl-template').html()),
				template = Handlebars.compile($('#entry-template').html()),
				template2 = Handlebars.compile($('#subtotal-template').html()),
				$mcontent = $('');

			$('.accordion-toggle').on('click', function(e) {
				var $this, curPage, curcontent, xpg;
				$this = $(this);
				if ($this.text() === '<?php echo "Page # 1 - " . $period_covered; ?>') {
					return;
				} else {
					xpg = $this.text().split('#')[1].split('-');
					curPage = parseInt(xpg[0]);
					curcontent = {
						content: '',
						foot: ''
					};

					_.each(mdata[curPage], function(a, b) {
						var content, subtotal, tholder;
						if (b === 'totals') {
							subtotal = template2(a);
							curcontent.foot = subtotal;
						} else {
							curcontent.content = curcontent.content + template(a);
						}
					});
					_gcontent = tbltemplate(curcontent);
					$($this.attr('href') + ' .accordion-inner').empty().html(_gcontent);
				}
			});

			$('#cmdtoggler, #cmdtoggler2').on('click', function(e) {
				e.preventDefault();
				var $this = $(this),
					$sel = ($this.attr('href') === '#cat') ? $('.categories') : $('.department');
				if ($(this).html() === 'Show') {
					$(this).html('Hide');
				} else {
					$(this).html('Show');
				}
				$sel.slideToggle(400);
			});
		})();
	</script>
</body>

</html>
<?php
$buffer = ob_get_contents();
ob_end_clean();

echo $buffer;
