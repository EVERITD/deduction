<?php
 require('fpdf.php');
 include('.../deductsite.php');
   $pdf = new FPDF('P','mm',array(215.9,160));  
   $brarrayName = array('S324','S302','S802'); 


  //var_dump($rmDetails);
foreach($rmDetails['batch-'.$batchNo] as $k => $v):
    //for lbr
    error_reporting(E_ALL ^ E_NOTICE);
$myServer = '192.168.16.63';
$myUser = 'sa';
$myPass = 'donterase';
$myDB = "DEDUCTION_testdata";

$s = @mssql_connect($myServer, $myUser, $myPass)
or die("Couldn't connect to SQL Server on $myServer");

$d = @mssql_select_db($myDB, $s)
or die("Couldn't open database $myDB");
 $lbrsql = "select lbr_number from deduction_master where dm_no_acctg in ('".$v->dm_no_acctg."')";
$lbrqryex = mssql_query($lbrsql);
 $lbrqry1 = mssql_fetch_object($lbrqryex);
 $lbrqry = $lbrqry1->lbr_number;
  if(in_array($v->dm_no, $datePrintContent)):
     $xc++;
    $height = $xc % 3 ;
   /*header1*/         
   $consopo = new DeductSite();
   $isconso = $consopo->conspo_vendor($v->branch_code,$v->vendorcode);
           if ($isconso == 1) {
                $supplier = (@$consPoRowCount[trim($v->dm_no)]) ? explode('-', $mainSitesList[trim($v->branch_code) == 'S399' ? 'S306':trim($v->branch_code)]['bname']):
                explode('-', $v->branch_name);
                $brname=$supplier[0];
                $brcode=trim($supplier[1]);
            } else {
                $supplier = explode('-', $v->branch_name);
                $brname=$supplier[0];
                $brcode=trim($supplier[1]);
            }//1
    $pdf->AddPage('L');
    if(!in_array(trim($v->branch_code), $brarrayName)){
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,17,'',0,1);  
    $pdf->Cell(55,4,$brname,0,0);  
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0,4,$v->dm_no_acctg,0,1);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,4,$brcode,0,1); 
    }else{
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,17,'',0,1);  
    $pdf->Cell(0,4,$brname,0,1);  
    $pdf->Cell(55,4,$brcode,0,0); 
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0,4,$v->dm_no_acctg,0,1);
    $pdf->SetFont('Arial','B',11);
    }
    /*header2*/
    $pdf->Cell(0,11,'',0,1);  
    $pdf->Cell(55,4,$v->vendorcode,0,0);  
    $dmdate=date('m/d/Y', strtotime($v->dm_date_int));
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0,4,$dmdate,0,1);
    $supname = str_replace('#', '', str_replace('$', '', $v->SupplierName));
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,4,$supname,0,1);  
   
    /*Body old value 25*/
    $pdf->Cell(0,15,'',0,1);  
    $namepr= $v->category_name.' '.$v->period;
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0,6,$namepr,0,1);
    //$nb=$pdf->WordWrap($text,120);
    $remarks22 = substr(trim($v->remarks1),0,29);
    $remarks23 = substr(trim($v->remarks1),29,40);
    $remarks24 = substr(trim($v->remarks1),52,100);
    $itmdept = '('.trim($v->deptname).')';
    $pdf->Cell(0,4,$remarks22,0,1); 
    $pdf->Cell(0,4,$remarks23,0,1); 
    $pdf->Cell(0,4,$remarks24,0,1); 
    $pdf->Cell(0,4,$itmdept,0,1); 
    $lbrno =  is_null($lbrqry) ? '' :$pdf->Cell(0,4,'LBR No.#'.$lbrqry,0,1); 
    $pdf->Cell(0,4,'',0,1);   
    $pdf->SetFont('Arial','BU',12);
    $pdf->Cell(42,6,'',0,0); 
    $pdf->Cell(0,20,'PHP '.number_format($v->amount, 2),0,1);  
    $pdf->Cell(42,5,'',0,1); 
    $encoder=(($ttrans) ? 'Printed By: ':'Re-Printed By: ').$lcusername;
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0,4,$encoder,0,1);  
    $pdf->Cell(0,4,date('m/d/Y'),0,1);  
  /*  $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,4,'Note: from '.trim($v->branch_code),0,1);   
*/
    if(isset($mainSitesList[trim($v->branch_code)]['code']) AND $mainSitesList[trim($v->branch_code)]['code'] !== trim($v->branch_code) and $isconso == 1):
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,4,'Note: from '.trim($v->branch_code),0,1);    
    endif; 


 endif;
endforeach;
$pdf->Output('','I');
    ?> 
