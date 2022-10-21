<?php
 define('FPDF_FONTPATH','font');
 require('fpdf.php');
 include('writehtml.php');//if need to insert a html tags

 
 $pdf = new PDF_HTML('P','mm','Letter');
    $brh = '';
    $z = 0;
    
    foreach($rmDetails['batch-'.$batchNo] as $k => $mn):
           $supplier = (@$consPoRowCount[trim($mn->dm_no)]) ? explode('-', $mainSitesList[trim($mn->branch_code)]['bname']):
           explode('-', $mn->branch_name);
                                  
                                   
        if ($brh != @$supplier[0].@$supplier[1]):
                 $brh = @$supplier[0].@$supplier[1];

       $pdf->AddPage();
       $pdf->SetFont('Times','',12);
       $pdf->Cell(0,17,'',0,1);  
       $pdf->Cell(0,4,trim($brh),0,1,'C');  
       $pdf->Cell(0,4,'Summary of Deduction Slips',0,1,'C');  
       $dtnbt =  date('F d, Y', strtotime($sqlCheckerPrintedDate)).' ['.$batchNo.']';
       $pdf->Cell(0,4,$dtnbt,0,1,'C');  
       $pdf->Cell(0,8,'',0,1);  	       
       $pdf->SetFont('Times','',10);
       $pdf->Cell(10,4,'From:',0,0);  	       
	     $pdf->SetFont('Times','UI',10);
       $pdf->Cell(0,4,'  '.strtoupper($lcdeptname).'  ',0,1);
       $pdf->SetFont('Times','B',10);  	              
       $pdf->Cell(0,16,'',0,1); 
       $pdf->Cell(27,4,'DS #',0,0); 
       $pdf->Cell(48,4,'Vcode',0,0); 
       $pdf->Cell(60,4,'Vendor',0,0); 
       $pdf->Cell(25,4,'Amount',0,0); 
       $pdf->Cell(25,4,'Remarks',0,1);

       
      $x  = 0;
	    $gtotal = 0;
	    $rCount = 0;
	    $rCount1= 0;
	    $xc = 0;         
    foreach($rmDetails['batch-'.$batchNo] as $k => $mm):
        $supplier2 = ($consPoRowCount[trim($mm->dm_no)]) ? explode('-', $mainSitesList[trim($mm->branch_code)]['bname']): explode('-', $mm->branch_name);
                     
            if ($supplier[0]==$supplier2[0] && $supplier[1]==$supplier2[1]):
            $x++;
            $gtotal += $mm->amount; 
                if(in_array($mm->dm_no, $datePrintContent)):
                $xc++;
                    $isCancelled = (int) $mm->vposted === 2 ? '<span style="text-decoration: line-through;">:content</span>': ':content';
                    if($rCount === 1000):
                        $rCount1++;
                    endif;
                    /*$ds = $x.'.'.str_replace(':content', $mm->dm_no_acctg, $isCancelled);
                    $mAptid = str_replace(':content', $mm->mAptid, $isCancelled);
                    $ven = str_replace(':content', $mm->vendorcode.' - '.$mm->SupplierName, $isCancelled);
                    $amt = str_replace(':content', number_format($mm->amount, 2), $isCancelled);*/
                    $ds = $x.'.'. $mm->dm_no_acctg;
                    $mAptid = $mm->mAptid;
                    $ven = $mm->vendorcode.' - '.$mm->SupplierName;
                    $amt = number_format($mm->amount, 2);
                    $pdf->SetFont('Times','',10);
                    $pdf->Cell(27,6,$ds,0,0); 
             				$pdf->Cell(15,6,$mAptid,0,0); 
             				$pdf->Cell(93,6,$ven,0,0); 
             				$pdf->Cell(25,6,$amt,0,0); 
                    if((int) $mm->vposted === 2):
                    	$pdf->SetFont('Times','U',10);
             				  $pdf->Cell(25,6,$pdf->write(4,$mm->cancel_remarks).' Cancelled',0,1);
             				else:
             					$pdf->SetFont('Times','U',10);
                  		$pdf->Cell(25,6,'                   ',0,1);
                		endif;
          		
                                
	                    if($rCount == 100)
	                            $rCount = 0;

	                        $rCount++;
	                    else:
	                        return false;
	                    endif;

                    endif;
                endforeach;
                           
                        

               
                    if($x < 11):
                        for($i=$x, $j=14; $i <= $j; $i++):
                		$pdf->Cell(0,4,'',0,0);  	       
                    	$pdf->Cell(0,4,'',0,0);  	       
                    	$pdf->Cell(0,4,'',0,0);  	       
                    	$pdf->Cell(0,4,'',0,0);  	       
                    	$pdf->Cell(0,4,'',0,1);  	       
                        endfor;
                    endif;
                $pdf->Cell(0,20,'',0,1);          
                 $pdf->SetFont('Times','',10); 
                $pdf->Cell(19,4,'Received By:',0,0);        
              $pdf->SetFont('Times','U',10);          
          	$pdf->Cell(0,4,'                                                                                ',0,1);        
                $encoder=(($ttrans) ? 'Printed By: ':'Re-Printed By: ').$lcusername;
				    $pdf->SetFont('Arial','',9);
				    $pdf->Cell(0,4,$encoder,0,1,'R');  
				    $pdf->Cell(0,4,date('m/d/Y'),0,1,'R');  
				                              
                 
      
         endif; endforeach;  
$pdf->Output('','I');
?>

