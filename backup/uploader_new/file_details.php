<?php
  /** File details
   *  Sept. 01, 2011
   *  EVER-Ith
   **/

   // session
   session_start();

    if (isset($_SESSION['user'])):
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
        $height = $xbranch == 'S399' ? 460 : 460;
        date_default_timezone_set('Asia/Manila');
    else:
        die('Could not connect. Please re-log-in!');
    endif;

    include '../sqlconn.php';

	// get the information based on filename
	// no validations yet

	if(!isset($_POST['typo'])):

		$_upload_file_branch = explode('_', $_POST['filename']);

		$_query = "select deduction_master.* from deduction_master where deduction_master.eposted='".$_POST['id']."'";

		$_result= mssql_query($_query);

		echo '<table class="zebra-striped">';
		echo '<thead>';
		echo '<tr>';
		echo '<th width="5%" class="blue"><strong>Control#</strong></th>';
		echo '<th width="4%" class="blue"><strong>Branch</strong></th>';
		echo '<th width="7%" class="blue"><strong>Dept</strong></th>';
		echo '<th width="5%" class="blue"><strong>Division</strong></th>';
		echo '<th width="13%" class="blue"><strong>Vendor Name</strong></th>';
		echo '<th width="13%" class="blue"><strong>Category</strong> </th>';
		echo '<th width="13%" class="blue"><strong>Sub Category</strong></th>';
		echo '<th width="5%" class="blue"><strong>Amt</strong></th>';
		echo '<th width="5%" class="blue"><strong>Status</strong></th>';
		echo '<th width="17%" class="blue"><strong>Remarks</strong></th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		$counter=0;
		$status = '';

		while($mval = mssql_fetch_object($_result)):
			$counter++;

			switch($d_row['vposted']):
				case 0: //unposted
					$status = 'New'; 
					break;
				case 1:
					$status = 'Approved';
					break;
				case 2:
					$status = 'Cancelled';
					break;
				case 3:
					$status = 'Extracted';
					break;
			endswitch;

			echo '<tr>';
			echo '<td>'.$counter.'</td>';
			echo '<td>'.$mval->branch_code.'</td>';
			echo '<td>'.$mval->dept_code.'</td>';
			echo '<td>'.$mval->division_code.'</td>';
			echo '<td>'.$mval->vendorcode.'</td>';
			echo '<td>'.$mval->category_code.'</td>';
			echo '<td>'.$mval->subcat_code.'</td>';
			echo '<td align="right" style="padding-right: 2px;">'.number_format($mval->amount, 2).'</td>';
			echo '<td>'.$status.'</td>';
			echo '<td>'.$mval->remarks.'</td>';
			echo '</tr>';

		endwhile;

		echo '</tbody>';
		echo '</table>';
	
	else:
		
		ob_start();

			$file = pathinfo(__file__, PATHINFO_BASENAME);
			// $xval = file_get_contents('HTTP://'.$_POST['href']);
			$xval = file_get_contents('HTTP://everloyalty.ever.ph'.$_POST['href']);
		
		ob_end_clean();

		echo $xval;

	endif;