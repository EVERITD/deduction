<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="../css/bootstrap.min.css" media="screen">
</head>
<body>

    <div class="container-fluid page-cover "  style="font-size: 5% height: 530px; margin-bottom: -180px;">
              <div class="row-fluid">
                <div class="span12">
                    <div style="text-align: center;">
                        <!--<span style="display:block;"><?php echo trim($cBranch); ?></span>-->
                        <span style="display:block;"><strong>Deduction Summary</strong></span>
                        <!--<span style="display:block;">Date Printed: <?php echo date('F d, Y', strtotime($sqlCheckerPrintedDate)).' ['.$batchNo.']'; ?></span>-->
                    </div>


            <table class="table table-striped table-bordered table-condensed font2" style="margin-top: 20px;  font-size:5%;">
                <thead>
                    <tr>
                     
                        <th width="5%" style="text-align: left;font-size: 9px;">Mainsite</th>
                        <th width="5%" style="text-align: left;font-size: 9px;">Subsite</th>
                        <th style="text-align: left;font-size: 9px;">DS No.</th>
                        <th  width="8%" style="text-align: left;font-size: 9px;">Vendor Code</th>
                        <th style="text-align: left;font-size: 9px;">Vendor Name</th>
                        <th style="text-align: left;font-size: 9px;">Particular</th>
                        <th  width="5.5%"style="text-align: left;font-size: 9px;">Amount</th>
                        <th width="5.5%" style="text-align: left;font-size: 9px;">DS Date</th>
                        <th width="9%"style="text-align: left;font-size: 9px;">Encoded Date</th>
                        <th width="8%" style="text-align: left;font-size: 9px;">Printed Date</th>
                        <th style="text-align: left;font-size: 9px;">Printed By</th>
                        <th width="8%" style="text-align: left;font-size: 9px;">Batch No.</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                      <?php
                                $x  = 0;
                                $gtotal = 0;
                                $rCount = 0;
                                $rCount1= 0;
                                $xc = 0;
                                foreach($rmDetails['batch-'.$batchNo] as $k => $nw):
                                     $x ++;
                                    if(in_array($nw->dm_no, $datePrintContent)):
                                    $xc++; 
                                    
                                   $isCancelled = (int) $nw->vposted === 2 ? '<span style="text-decoration: line-through;">:content</span>': ':content';
                       
                                     if($rCount === 45):
                                        $rCount1++;
                                    endif;
                       ?>

                <tr>
                       <td> 

                        <span class="no-print" style="margin-right: 5px;"><?php echo $x; ?>.</span>
                        <span style="font-size: 8px;">
                        <?php $supplier = (@$consPoRowCount[trim($nw->dm_no)]) ? explode('-', $mainSitesList2[trim($nw->branch_code)]['bcode']):
                                    explode('-', $nw->branch_code);
                                  $branch = str_replace('S399','S306', @$supplier[0]);
                                 echo str_replace(':content', $branch , $isCancelled); 
                            ?>
                         </span>   
                        </td>
                       <td> 
                       <span style="font-size: 8px;">
                        <?php 
                            
                         $supplier = trim(implode('',@$supplier));
                         $char = '-';
                        if(isset($mainSitesList[trim($nw->branch_code)]['code']) AND $mainSitesList[trim($nw->branch_code)]['code'] !== $nw->branch):
                                          
                            if($supplier <> trim($nw->branch_code))
                                    {
                                        echo str_replace(':content',trim($nw->branch_code),$isCancelled); 
                                    }
                                    else
                                    {
                                        echo "<div style = 'text-align: left;'>".htmlspecialchars($char).'</div>';
                                    }

                        endif; ?>
                          </span> 
                        </td>

                        <td><span style="font-size: 8px;"><?php echo str_replace(':content',$nw->dm_no_acctg,$isCancelled); ?> </span> 
                        </td>
                       
                        <td><span style="font-size: 7px;"><?php echo  "<div style = 'margin-left: 5px;'>".str_replace(':content', $nw->vendorcode, $isCancelled).'</div>'; ?></td></span> 
                        <td><span style="font-size: 8px;"><?php $sup = str_replace('#', '', str_replace('$', '', $nw->SupplierName)); 
                                    echo str_replace(':content',$sup,$isCancelled);

                            ?></span> 
                        </td>
                      
                       <td><span style="font-size: 8px;"> <?php
                                    echo str_replace(':content', $nw->category_name.' '.$nw->period,$isCancelled);
                            ?></span> 
                        </td>
                      <td><span style="font-size: 8px;"><?php echo str_replace(':content',number_format($nw->amount, 2),$isCancelled); ?></span> </td>
                     
                      <td><span style="font-size: 8px;"><?php  
                                   $date = date('m/d/Y', strtotime($nw->dm_date_int)); 
                                   echo str_replace(':content',$date,$isCancelled);
                            ?></span> 
                       </td>
                       <td ><span style="font-size: 8px;"><?php 
                               
                                $petya = $nw->fencoded_date;
                                          
                      echo '<div style="margin-left: 10px; class = "font2">'.str_replace(':content',$petya,$isCancelled)."</div>"; 
                     ?></span> </td>

                      <td><span style="font-size: 8px;"> <?php $pd = date('m/d/Y', strtotime($sqlCheckerPrintedDate));
                             echo   str_replace(':content',$pd,$isCancelled);

                       ?></span> 
                       </td>
                  <td><span style="font-size: 7px;"><?php echo str_replace(':content', $lcusername, $isCancelled); ?></span> </td>
                     <td><span style="font-size: 8px;"><?php echo str_replace(':content',$batchNo,$isCancelled);?></span> </td>
                </tr>    

                  <?php
                                    if($rCount === 45)
                                        $rCount = 0;
                                    $rCount++;
                                    endif;
                                    endforeach;
                ?>
                 </tbody>
                </table>
                </div>    
            </div>    
        </div>
        <script type="text/javascript" src="../js/jquery1.7.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
</body>
</html>