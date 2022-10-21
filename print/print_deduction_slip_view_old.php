<?php
 require('fpdf.php');
 include('.../deductsite.php');
   $pdf = new FPDF('P','mm',array(215.9,160));  
   $brarrayName = array('S324','S302','S802'); 



foreach($rmDetails['batch-'.$batchNo] as $k => $v):
      
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
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,17,'',0,1);  
    $pdf->Cell(55,4,$brname,0,0);  
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,4,$v->dm_no_acctg,0,1);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,4,$brcode,0,1); 
    }else{
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,17,'',0,1);  
    $pdf->Cell(0,4,$brname,0,1);  
    $pdf->Cell(55,4,$brcode,0,0); 
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,4,$v->dm_no_acctg,0,1);
    $pdf->SetFont('Arial','B',11);
    }
    /*header2*/
    $pdf->Cell(0,11,'',0,1);  
    $pdf->Cell(55,4,$v->vendorcode,0,0);  
    $dmdate=date('m/d/Y', strtotime($v->dm_date_int));
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,4,$dmdate,0,1);
    $supname = str_replace('#', '', str_replace('$', '', $v->SupplierName));
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,4,$supname,0,1);  
   
    /*Body*/
    $pdf->Cell(0,25,'',0,1);  
    $namepr= $v->category_name.' '.$v->period;
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,6,$namepr,0,1);
    //$nb=$pdf->WordWrap($text,120);
    $remarks22 = substr(trim($v->remarks1),0,51);
    $remarks23 = substr(trim($v->remarks1),51,100);
    $pdf->Cell(0,4,$remarks22,0,1); 
    $pdf->Cell(0,4,$remarks23,0,1); 
    $pdf->Cell(0,4,'',0,1);   
    $pdf->SetFont('Arial','BU',11);
    $pdf->Cell(42,6,'',0,0); 
    $pdf->Cell(0,20,'PHP '.number_format($v->amount, 2),0,1);  
    $pdf->Cell(42,10,'',0,1); 
    $encoder=(($ttrans) ? 'Printed By: ':'Re-Printed By: ').$lcusername;
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,4,$encoder,0,1);  
    $pdf->Cell(0,4,date('m/d/Y'),0,1);  
   

    if(isset($mainSitesList[trim($v->branch_code)]['code']) AND $mainSitesList[trim($v->branch_code)]['code'] !== trim($v->branch_code) and $isconso == 1):
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,4,'Note: from '.trim($v->branch_code),0,1);    
    endif; 


 endif;
endforeach;
$pdf->Output('','I');
    ?> 
