<?php
error_reporting(0);
    session_start();
    $x = strlen($_SESSION['user']);
    date_default_timezone_set('Asia/Manila');

    if ($x > 0 ) {
        include('sqlconn.php');
        include 'ajax/funcGenerateDsNumber.php';
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
    }
    else {
        header("HTTP/1.0 403 Access denied");
        die("Un-Authorized Access");
    }

    $transType = json_decode(htmlspecialchars(strip_tags($_GET['rprn']), ENT_QUOTES));
    $ttrans = ($transType) ? 0: 1;
    $transType = ($transType === 'yes') ? 1: 0;
    $pbranch = htmlspecialchars(strip_tags($_POST['cmbranch']));
    $need = $_get['need'];    


if($need)
{
    if(@trim($_GET['branch']))
    {
   
   $pbranch = htmlspecialchars(strip_tags($_GET['branch']), ENT_QUOTES);    
     

    // /$brCount = str_word_count($pbranch);
    $pbranch = explode('-',$pbranch,-1);
      
         // $pbranch = explode('-',$pbranch,-2);
    } 
    
   /* $pbranch = array_map('trim', $pbranch);       // trim the array
    $pbranch = array_filter($pbranch);*/
   /* echo*/ 

    $pbranch = implode('\', \'',$pbranch); 
}
else
{
    //notthings doing 
}   

$prt = $_GET['prt'];
  
    // Old Code
    // $ctrlno = json_decode(htmlspecialchars(strip_tags($_POST['chk']), ENT_QUOTES));
    // $ctrlno = explode('-', $ctrlno);
    // New Code - just change the method from get to post
    // fetch the details of single

 $ctrlno = $_POST['chk']; 


    if( @$_GET['ctrlno'] )
    {
        $ctrlno = htmlspecialchars(strip_tags($_GET['ctrlno']), ENT_QUOTES);
        $ctrlno = explode('-', $ctrlno);
    }

        $ctrlno = array_map('trim', $ctrlno);       // trim the array
        $ctrlno = array_filter($ctrlno);            // remove empty array, value = "" OR ''

        $where = implode('\', \'', $ctrlno);

    // TODO: if empty ang ctrlno dapat na mag-redirect to
    // the previous page. para maiwasan ang pag-insert ng data sa
    // deduction_print, may js validator na pero for safety kailangan
    // pa rin na gawin to.

    // create coverpage --
    $prDate = date('Ymd');

    // add parameters so that it will not pick the wrong dm_no_acctg
    $batchNo = 1;

$sqlQry1 = "select distinct deduction_slip_print_details.deduction_slip_prints_id as id,
        deduction_slip_print_details.batch from deduction_slip_print_details
        where ltrim(rtrim(deduction_slip_print_details.dm_ctrl_no)) in ('{$where}') ";
    $sqlPrintChecker = mssql_query($sqlQry1);

    if(mssql_num_rows($sqlPrintChecker))
    {
        $sqlCheckerId = (int) mssql_result($sqlPrintChecker, 0, 'id');
        $batchNo = (int) mssql_result($sqlPrintChecker, 0, 'batch');
    }
    else
        $sqlCheckerId = 0;

    // kapag existing na sa database wala ng gagawin kundi ang i-add ung other detail
    // kaya merong batch na field para ma-differentiate ang new at original

    // added the validation for branch
    // [code] if( ! $sqlCheckerId and $pbranch ) [/code]
    // [original_code] if( ! $sqlCheckerId ) [/original_code]
    // just revert it back if something goes wrong.
   
 
    if( ! $sqlCheckerId )
    {
        $dpssql = " select top 1 id from deduction_slip_prints
            where  branch in('{$pbranch}') and printed_date='{$prDate}' and print_by='{$lcuser}' order by id desc ";

        $dpsrst = mssql_query($dpssql);

  

        if($dpsrstid = @mssql_result($dpsrst, 0, 'id'))
        {
            $sqlCheckerId = $dpsrstid;

            $getBatch = "select max(batch)+1 as batch from deduction_slip_print_details where
                deduction_slip_prints_id='{$sqlCheckerId}'";
            $getBatchRst = mssql_query($getBatch);
            $maxId = mssql_result($getBatchRst, 0, 'batch');
            $batchNo = ( ! is_null($maxId) ) ? $maxId: $batchNo;
        }


        else
        {

            
         /*echo*/ $sqlInsert = " insert into deduction_slip_prints(branch, printed_date, print_by, created_at, reprint_at, deduction_slip_count, remarks, is_printed)
                values('{$pbranch}', '{$prDate}', '{$lcuser}', getdate(), NULL, ".count($ctrlno).", NULL, 0)"; 

        
                    $sqlPrintChecker = mssql_query($sqlInsert);

           $sqlqry = "select top 1 id from deduction_slip_prints
                where branch in ('{$pbranch}') and printed_date='{$prDate}' and print_by='{$lcuser}' order by id desc ";

             $sqlresid = mssql_query($sqlqry);   
            $sqlCheckerId = mssql_result($sqlresid, 0, 'id');

        }
    }


    // get the details of printing
    
    $sqlQry = " select * from deduction_slip_print_details where deduction_slip_prints_id='{$sqlCheckerId}' and batch='{$batchNo}' ";
    $sqlRst = mssql_query($sqlQry);
    $datePrintContent = array();
    
    while($srst = mssql_fetch_object($sqlRst))
    {
        ${trim($srst->dm_ctrl_no)} = $srst;
        $datePrintContent[] = $srst->dm_ctrl_no;
    }

    $where1 = implode('\', \'', $datePrintContent);

    $batch = 'batch-'.$batchNo;
    //var_dump($)
    /**f
     * DM-Details
     */
       $sql = 'select a.*, b.branch_name, b.branch_prefix,
            convert(char(8), a.dm_date, 112) as dm_date_int,
            convert(char(10), a.encoded_date, 101) as  fencoded_date,
            convert(char(15), a.review_date, 121) as  freview_date,
            c.category_name, b.branch_code
            from deduction_master a
            left join ref_branch b on a.branch_code=b.branch_code
            left join ref_category c on a.category_code=c.category_code
            where a.paymentid = 2 and a.dm_no in (\''.$where.'\') :where1';

        // only $for _GET
        if(htmlspecialchars(strip_tags($_GET['ctrlno']), ENT_QUOTES))
            $sql = str_replace(':where1',' OR ltrim(rtrim(a.dm_no)) in ( \'' . $where1 . '\' ) ', $sql);

        $sql = str_replace(':where1', '', $sql.' order by a.dm_no_acctg,a.branch_code asc');

        // the dmno details

        $rst = mssql_query($sql);
        $rmDetails = array();
        $rmDmAcctgNo = array();

        $cBranch = '';
        
        while($rs = mssql_fetch_object($rst))
        {
            // force the generation of deduction slip number
            $modified_rs = &$rs;

            if($modified_rs->dm_no_acctg === ' ')
            {
                $mBranchMain = $modified_rs->branch_code;
                $mBranchPref = $modified_rs->branch_prefix;

                $mQry = " select branch_code, branch_prefix from ref_branch where branch_code in ( select top 1 main_site from conspo_pivot where dm_no='{$rs->dm_no}' ) ";
                $mRs  = mssql_query($mQry);

                while($mRsData = mssql_fetch_object($mRs))
                {
                    $mBranchMain = $mRsData->branch_code;
                    $mBranchPref = $mRsData->branch_prefix;
                }

                // $fmaxId = funcGenerateDsNumber($modified_rs->branch_code,
                //    $modified_rs->branch_prefix);
                $fmaxId = funcGenerateDsNumber($mBranchMain,
                   $mBranchPref);

               /*echo*/ $qry = "update deduction_master set dm_no_acctg='".$fmaxId."' where dm_no='".$rs->dm_no."'";
                 $rs2 = mssql_query($qry);

                $modified_rs->dm_no_acctg = $fmaxId;
            }

            // kapag orig, iinsert nya sa record, kapag hindi, check nya kng existing
            // kapag hindi insert nya,,,
            if(${$rs->dm_no}->dm_ctrl_no !== $rs->dm_no)
            {
              $control = trim($modified_rs->dm_no);
             $sqls = " insert into deduction_slip_print_details( deduction_slip_prints_id, dm_ctrl_no, batch, created_at,
                    reprint_at, is_active, is_printed ) values ('{$sqlCheckerId}', '{$control}', {$batchNo}, getdate(), NULL, 0, 0) ";  
             $sqls .= "delete from deduction_slip_print_details where dm_ctrl_no = ''";  
                mssql_query($sqls);
                error_reporting(0);   
                 //die();
            }

            // we need to know the original content
            $ssql = "select aptid from arms.dbo.supplier where aptid='{$modified_rs->vendorcode}' OR vendorcode='{$modified_rs->vendorcode}' ";
            $srs = mssql_query($ssql);

            $modified_rs->mAptid = @mssql_result($srs, 0, 'aptid');

            // assigned dm_no_acctg to be queried later.
            $rmDmAcctgNo[] = $modified_rs->dm_no;

            $cBranch = $modified_rs->branch_name;

            $rmDetails[$batch][] = $modified_rs;
        }
        // die();

        $sql = " select dsp.remarks, dsp.printed_date,
            dsp.deduction_slip_count, dspd.* from deduction_slip_prints dsp
            left join deduction_slip_print_details dspd on dsp.id=dspd.deduction_slip_prints_id where
            ltrim(rtrim(dspd.dm_ctrl_no)) in ( '".implode('\', \'', $rmDmAcctgNo)."' )";
        $sql .= ($transType) ? " and batch='{$batchNo}' ": '';

        $rmpd = mssql_query($sql);
        $printDetails = array();
        while($rpd = mssql_fetch_object($rmpd))
        {
            $printDetails[$rpd->dm_ctrl_no] = $rpd;
            $sqlCheckerPrintedDate = $rpd->printed_date;
            $lastReprintDate = $rpd->created_at;
        }

        // trigger for displaying the pageCover
        // kapag reprint set this to zero
      $pageCover = $transType;



    /**
     * EndOfDMDetails
     */

    // include 'fpdf.php';
    // class PDF extends FPDF
    // {
    // // Load data
    // function LoadData($file)
    // {
    //     // Read file lines
    //     $lines = file($file);
    //     $data = array();
    //     foreach($lines as $line)
    //         $data[] = explode(';',trim($line));
    //     return $data;
    // }

    // // Simple table
    // function BasicTable($header, $data)
    // {
    //     // Header
    //     foreach($header as $col)
    //         $this->Cell(40,7,$col,1);
    //     $this->Ln();
    //     // Data
    //     foreach($data as $row)
    //     {
    //         foreach($row as $col)
    //             $this->Cell(40,6,$col,1);
    //         $this->Ln();
    //     }
    // }

    // // Better table
    // function ImprovedTable($header, $data)
    // {
    //     // Column widths
    //     $w = array(40, 35, 40, 45);
    //     // Header
    //     for($i=0;$i<count($header);$i++)
    //         $this->Cell($w[$i],7,$header[$i],1,0,'C');
    //     $this->Ln();
    //     // Data
    //     foreach($data as $row)
    //     {
    //         $this->Cell($w[0],6,$row[0],'LR');
    //         $this->Cell($w[1],6,$row[1],'LR');
    //         $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
    //         $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
    //         $this->Ln();
    //     }
    //     // Closing line
    //     $this->Cell(array_sum($w),0,'','T');
    // }

    // // Colored table
    // function FancyTable($header, $data)
    // {
    //     // Colors, line width and bold font
    //     $this->SetFillColor(255,0,0);
    //     $this->SetTextColor(255);
    //     $this->SetDrawColor(128,0,0);
    //     $this->SetLineWidth(.3);
    //     $this->SetFont('','B');
    //     // Header
    //     $w = array(40, 35, 40, 45);
    //     for($i=0;$i<count($header);$i++)
    //         $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    //     $this->Ln();
    //     // Color and font restoration
    //     $this->SetFillColor(224,235,255);
    //     $this->SetTextColor(0);
    //     $this->SetFont('');
    //     // Data
    //     $fill = false;
    //     foreach($data as $row)
    //     {
    //         $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
    //         $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
    //         $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
    //         $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
    //         $this->Ln();
    //         $fill = !$fill;
    //     }
    //     // Closing line
    //     $this->Cell(array_sum($w),0,'','T');
    // }
    // }

    // $pdf = new PDF('P', 'mm', array(107.95, 139.7));
    // // Column headings
    // $header = array('Issued To', 'Date');
    // // Data loading
    // $data = $pdf->LoadData('countries.txt');
    // $pdf->SetFont('Arial','',14);
    // $pdf->AddPage();
    // $pdf->BasicTable($header,$data);
    // $pdf->AddPage();
    // $pdf->ImprovedTable($header,$data);
    // $pdf->AddPage();
    // $pdf->FancyTable($header,$data);
    // $pdf->Output();
    // die();

    // load the view
    // this will just render the view on background.
    //

  /**
  * Consolidate PO
  */




  // Check from the conspo pivot

$consPoSql = ' select a.* from conspo_pivot a 
        left join deduction_master b on a.dm_no = b.dm_no
        where a.dm_no in (\''.$where.'\') :where1 ';
  $consPoSql = str_replace('deduction_master.', '', $consPoSql);
  $consPoSql = str_replace(':where1',(htmlspecialchars(strip_tags($_GET['ctrlno']), ENT_QUOTES)) ? ' OR ltrim(rtrim(b.dm_no)) in ( \'' . $where1 . '\' ) ': '', $consPoSql);

  $consPoRS = mssql_query($consPoSql);
  $consPoRowCount = array();

  // checker for every dm_no on pivot table
  while($consData = mssql_fetch_object($consPoRS))
    $consPoRowCount[trim($consData->dm_no)] = 1;

  $lQry = " select a.*, b.branch_name,b.branch_code from everlyl_conspo.consolidatepo.dbo.sitegroup a
    left join ref_branch b on a.main_site=b.branch_code order by a.main_site ";
  $lRs  = mssql_query($lQry);
  $mainSitesList   = array();
  $mainSitesList2  = array();
  $uniqueMainSites = array();
  $subSitesList    = array();

  while($cpRs = mssql_fetch_object($lRs))
  {
    $mainSitesList[trim($cpRs->sub_site)] = array(
      'code' => trim($cpRs->main_site),
      'name' => trim($cpRs->main_name),
      'bname' => trim($cpRs->branch_name),
    );
    $subSitesList[trim($cpRs->main_site)][] = trim($cpRs->sub_site);
    $uniqueMainSites[] = trim($cpRs->main_site);

    $mainSitesList2[trim($cpRs->sub_site)] = array(
      'code' => trim($cpRs->main_site),
      'name' => trim($cpRs->main_name),
      'bcode' => trim($cpRs->branch_code),
    );
    $subSitesList[trim($cpRs->main_site)][] = trim($cpRs->sub_site);
    $uniqueMainSites[] = trim($cpRs->main_site);
  }

  ob_start();
  include('dm_print_purch_view_win7.php');

  $buffer = ob_get_contents();
  ob_end_clean();

    // show the rendered view or do some other stuff or even
  echo $buffer;
    // you can do whatever comes here...
  die();
