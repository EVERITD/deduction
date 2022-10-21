<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
      <!--   <title>Print Deduction Slip</title> -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="deduction-modules">
        <meta name="author" content="ever-itd">

        <style type="text/css">
         /*   body * {
                font-family: Arial;
            }*/
            @media print {
                .no-print, .print-button { display: none; }
                span, th {font-size:13px;}
                td {font-family: Arial, Helvetica, sans-serif; font-size: 13px !important;}
                .amt{font-size:13px !important;text-align: justify;}

                .print-small .container-fluid {
                    width: 450px !important;
                }
            
            }
            table{width:100%;}
            th,td {font-size:13px;}

/*            .small-size th, .small-size td {
                border-left:solid 1px #000;
                border-right:solid 1px #000;
                border-bottom:solid 1px #000;
            }*/

            .no-display { display: none; }
            
            /*@page { size: 8.5in 6.5in; }*/

            @media screen
            {
                small-size.test {
                font-family:"Times New Roman",Georgia;
                font-size:9px;

                }
            }

            @media print
            {
                small-size.test {
                    font-family:Verdana, Arial;
                    font-size:10px;

                }
            }


            .font
              {                
              font-family:"myFirstFont",Times,serif;
              font-size:10px;                  
              color: block;
              }


            .font2
              {                
              font-family:"myFirstFont",Times,serif;
              font-size:9%;                  
              color: block;
              }

            @page{
                margin-left: 0px;
                margin-right: 0px;
                margin-top: 0px;
                margin-bottom: 0px;
            } 

        </style>

<!-- /*        <script type="text/javascript">
            var w = window,
            d = document,
            e = d.documentElement,
            g = d.getElementsByTagName('body')[0],
            x = w.innerWidth || e.clientWidth || g.clientWidth,
            y = w.innerHeight|| e.clientHeight|| g.clientHeight;
            alert(x + ' × ' + y);
        </script>*/ -->

    </head>
    <body>
        <?php
            include('deductsite.php');
        ?>
        
        </script>
        <div class="container-fluid no-print">
            <div class="row-fluid">
                <div class="span12">
                    <div class="alert alert-info">
                        <div class="pull-right">
<!--                             <label class="checkbox"><input type="checkbox" name="chkpage" id="chkidpage"> Print Page Cover Only</label> -->
                            
                            
                            <a href="<?php echo 'deductionslip_reprint.php?ispr=yes' ?>" class="btn btn-small"><strong>Back</strong></a>
                           
                            <button class="btn btn-small" type="button" id='cmdprndetails2'>Print Deduction Summary for Accounting</button>
                  
                        </div>
                        <em>Press Enter key to print. </em>
                    </div>
                </div>
            </div>
        </div>
    <?php if(count($rmDetails) > 0): ?>

    <?php
        $brh = '';
        $z = 0;

        foreach($rmDetails['batch-'.$batchNo] as $k => $mn):
                  
    ?>
        
        <?php 
            $supplier = (@$consPoRowCount[trim($mn->dm_no)] ) ? explode('-', $mainSitesList[trim($mn->branch_code)]['bname']):
                                    explode('-', $mn->branch_name);
                                  
        /*echo 'gene';*/
        /*var_dump($supplier);*/

        if ($brh != @$supplier[0].@$supplier[1]):
                 $brh = @$supplier[0].@$supplier[1]?>

     <!--   <div class="container-fluid page-cover big-size <?php// echo ( ! $pageCover ) ? //'no-display no-print': ''; ?>" id='page2' style="height: 850px;   margin-bottom: 180px;  " > <?php// 85%; pagka moredone 60 records?>-->
           <div class="container-fluid page-cover big-size <?php echo ( ! $pageCover ) ? 'no-display no-print': ''; ?>" id='page2' style="height: 850px;  margin-bottom: 180px; " >
            <div class="row-fluid">
                <div class="span12">
                    
                    <div style="text-align: center;">
                    
                        <span style="display:block;"> 
                              <?php 
                                  echo @$supplier[0];
                            ?>

                        </span>
                        <span style="display:block;">Summary of Deduction Slips</span>
                        <span style="display:block;"><?php echo date('F d, Y', strtotime($sqlCheckerPrintedDate)).' ['.$batchNo.']'; ?></span>
                    </div>
                     
                    <div style="margin-top: 20px; font-size: 11px;">
                        <strong>From: </strong>
                        <em style="padding-left: 10px; padding-right: 50px; border-bottom: solid 1px #000;"><?php echo strtoupper($lcdeptname); ?></em>
                    </div>
                   
                    <table class="table table-striped table-bordered table-condensed" style="margin-top: 15px;">
                        <thead>
                            <tr>
                                <th width="14%">DS #</th>
                                <th width="8%">Vcode</th>
                                <th width="43%">Vendor</th>
                                <th width="10%" style="text-align: center;">Amount</th>
                                <th width="25%">Remarks</th>
                            </tr>
                        </thead>
                        <tbody >
                            <?php
                            $x  = 0;
                            $gtotal = 0;
                            $rCount = 0;
                            $rCount1= 0;
                            $xc = 0;

                            foreach($rmDetails['batch-'.$batchNo] as $k => $mm):
                                $supplier2 = (@$consPoRowCount[trim($mm->dm_no)]) ? explode('-', $mainSitesList[trim($mm->branch_code)]['bname']):
                                    explode('-', $mm->branch_name);

                            if (@$supplier[0]==@$supplier2[0] && @$supplier[1]==@$supplier2[1]):
                            $x = $x + 1;
                            $gtotal += $mm->amount; 
                                if(in_array($mm->dm_no, $ctrlno)):
                                $xc = $xc + 1;

                                    $isCancelled = (int) $mm->vposted === 2 ? '<span style="text-decoration: line-through;">:content</span>': ':content';
                                    if($rCount == 100):
                                        $c = $rCount1 + 1;
                                    endif;
                            ?>
                            <tr style="">
                                <td><span class="no-print" style="margin-right: 5px;"><?php echo $x; ?>.</span><?php echo str_replace(':content', $mm->dm_no_acctg, $isCancelled); ?></td>
                                <td><?php echo str_replace(':content', $mm->mAptid, $isCancelled); ?></td>
                                <td><?php echo str_replace(':content', $mm->vendorcode.' - '.$mm->SupplierName, $isCancelled); ?></td>
                                <td style="text-align: right; padding-right: 20px"><?php echo str_replace(':content', number_format($mm->amount, 2), $isCancelled); ?></td>
                                <td style="border-bottom: solid 1px #000;">
                                    <?php if((int) $mm->vposted === 2): ?>
                                    <?php echo $mm->cancel_remarks; ?>
                                    <?php endif; ?>
                                    &nbsp;
                                </td>
                            </tr>
                            <?php
                                    if($rCount == 100)
                                        $rCount = 0;

                                        $rCount = $rCount + 1;
                                else:
                                    return false;
                                endif;
                            endif;
                            endforeach;
                            ?>
                            <!--
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="text-align: right; padding-right: 50px;"><strong>Total</strong></td>
                                <td style="text-align: right; padding-right: 20px; border-top: solid 1px #000; border-bottom: double 3px #000;"><strong><?php echo number_format($gtotal, 2); ?></strong></td>
                                <td>&nbsp;</td>
                            </tr>
                            -->

                            <?php
                                if($x < 11):
                                    for($i=$x, $j=14; $i <= $j; $i++):
                            ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php
                                    endfor;
                                endif;
                            ?>
                            <tr>
                                <td colspan="3">Received By: <span style="padding-right: 250px; border-bottom: solid 1px #000;">&nbsp;</span></td>
                                <td colspan="2" style="text-align: right;padding-top:200px;">
                                    <?php echo (($ttrans) ? 'Printed By: ':'Re-Printed By: ').$lcusername; ?>
                                    <span style="display: block;"><?php echo date('m/d/Y'); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                   
                                </td>
                            </tr>
                        </tbody>
                    </table>
                     
                </div>
            </div> 
        </div>
        <div style="height 50px;  page-break-after: always; "></div>
      
      
       <?php  endif; endforeach;  ?>




    <div class="container-fluid page-cover " id = 'page3' style="font-size: 5% height: 530px; margin-bottom: -180px;">
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
                               $x      = 0;
                                $gtotal = 0;
                                $rCount = 0;
                                $rCount2= 0;
                                $xc = 0;
                                foreach($rmDetails['batch-'.$batchNo] as $k => $nw):
                                     $x ++;
                                    if(in_array($nw->dm_no, $ctrlno)):
                                    $xc++; 
                                    
                                   $isCancelled = (int) $mm->vposted === 2 ? '<span style="text-decoration: line-through;">:content</span>': ':content';
                       
                                     if($rCount === 45):
                                        $rCount2++;
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



    <?php
        endif;
        $xc = 0;
            
        foreach($rmDetails['batch-'.$batchNo] as $k => $v):
            if(in_array($v->dm_no, $ctrlno)):
            $xc++;  
            $height = $xc % 3 ;
    ?>

    <div class="small-size <?php echo ( $pageCover ) ? 'no-display no-print': ''; ?>" style="margin-left:0;padding-left:18px;width:305px;height:526px;padding-bottom:68px;padding-top:<?php echo $height; ?>px;">
        <table cellpadding="0" cellspacing="0" border="0">
            <thead>
                <tr>
                    <th colspan="2" style="height: 20px; color: #fff !important; visibility: hidden;">
                        DEDUCTION SLIP
                    </th>
                </tr>
                <tr>
                    <th width="70%" style="text-align:left;" style="border-right:none;padding-bottom: 25px;">
                        <span style="display: block;padding:10px;padding-bottom:3px;">
                            <?php
                                $consopo = new DeductSite();
                                $isconso = $consopo->conspo_vendor($v->branch_code,$v->vendorcode);
                                if ($isconso == 1) {
                                    $supplier = (@$consPoRowCount[trim($v->dm_no)]) ? explode('-', $mainSitesList[trim($v->branch_code) == 'S399' ? 'S306':trim($v->branch_code)]['bname']):
                                    explode('-', $v->branch_name);
                                    /*var_dump($mainSitesList);*/

                                    echo @$supplier[0];
                                    echo @$supplier[1];
                                } else {
                                    $supplier = explode('-', $v->branch_name);
                                    echo @$supplier[0];
                                    echo @$supplier[1];
                                }

                                
                                // $supplier2 = explode('-', $v->branch_name);
                                 //echo $mainSitesList[trim($v->branch_code)]['bname'];
                                 //var_dump($mainSitesList);
                                 //var_dump($v);
                            
                            ?>
                        </span>
                    </th>
                    <th width="30%" style="border-left: none;font-size:13px;vertical-align: top;padding-top:10px;padding-right:5px;"><?php echo $v->dm_no_acctg; ?></th>
                </tr>
                <tr>
                    <th width="80%" style="text-align: left; padding: 10px;padding-top: 0;border-right:none; vertical-align: top;">
                        <span style="display:block;visibility:hidden;padding-bottom: 10px; color: #fff;">Issued To:</span>
                        <span style="display:block"><?php echo $v->vendorcode; ?></span>
                    </th>
                    <th width="20%" style="border-left: none;padding-top:0px;vertical-align: top;font-size:11px;">
                        <span style="display:block;visibility:hidden;padding-bottom: 10px; color: #fff;">Date:</span>
                        <?php echo date('m/d/Y', strtotime($v->dm_date_int)); ?>
                    </th>
                </tr>
                <tr>
                    <th colspan="2" style="padding:0;padding-left:10px;text-align: left;">
                        <?php echo str_replace('#', '', str_replace('$', '', $v->SupplierName)); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="border: none;height:35px;"></td>
                </tr>
                <tr>
                    <td colspan="2" class="amt" style="vertical-align: top; height:60px;padding-top:3px;padding-left:3px;font-size: 12px;border-bottom:none;">
                        <span style="display:block;visibility:hidden;padding-left:10px;margin-bottom: 10px;">We have debited your account in payment for:</span>
                        <span style="padding-left: 40px;"><?php echo $v->category_name.' '.$v->period; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="amt" style="text-align: left; vertical-align: top; border-top: none;border-bottom:none;padding-left: 43px; height: 93px;font-size: 12px;"><?php echo $v->remarks1; ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="amt" style="border-top: none;border-bottom:none;text-align: right; padding-right: 20px; padding-bottom: 20px;font-size: 12px;">PHP <span style="padding-left: 40px;border-bottom: double 6px #000;"><span style="padding-right: 10px;font-size: 14px;font-weight:700;font-family: Tahoma;"><?php echo number_format($v->amount, 2); ?></span></span></td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 50px;padding-left:10px;">
                        <?php echo (($ttrans) ? 'Printed By: ':'Re-Printed By: ').$lcusername; ?>
                        <span style="display: block;"><?php echo date('m/d/Y'); ?>
                        <?php if(isset($mainSitesList[trim($v->branch_code)]['code']) AND $mainSitesList[trim($v->branch_code)]['code'] !== trim($v->branch_code) and $isconso == 1):
                                echo '<em style="font-size: 10px;">Note: from '.trim($v->branch_code).'</em>';
                            endif;  
                        ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 10px;padding-left:10px;">
                       &nbsp;
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

<!--    
 <div class="small-size <?php //echo ( $pageCover ) ? 'no-display no-print': ''; ?>" style="margin-left:0px;padding-left:10px;width:400px;<?php //if($xc !== count($ctrlno)): ?> page-break-after: always; <?php //endif; ?>height: 200px;">

     <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="2" style="height: 100px; color: #fff !important; visibility: hidden;">
                        DEDUCTION SLIP
                    </th>
                </tr>
                <tr>
                    <th colspan="1" width="200%" style="text-align:left;" style="border-right:none;">
                        <span style="display: block;padding:10px;padding-bottom:1px; font-size: 12px;" >
                            <?php
                                //$supplier = (@$consPoRowCount[trim($v->dm_no)]) ? explode('-', $mainSitesList[trim($v->branch_code)]['bname']):
                                //    explode('-', $v->branch_name);
                                //echo @$supplier[0];
                                //echo (count($supplier) > 1) ? end($supplier): ''; 
                            ?>
                        </span>
                        <span style="padding-left: 10px;font-size: 12px;">
                            
                        </span>
                    </th>
                    <th colspan="1" width="30%" style="border-left: none;vertical-align: top;padding-top:10px;padding-right:5px;"><span style='font-size: 12px;'><?php echo $v->dm_no_acctg; ?></span></th>
                </tr>
                <tr>
                    <th colspan="1" width="80%" style="border-left: none;padding-top:-250px;vertical-align: top;">
                        
                        <span style="display:block;font-size: 12px; padding-right:183px;"><?php //echo $v->vendorcode; ?></span>
                        
                    </th>
                    <th colspan="1" width="20%" style="border-left: none;padding-top:-250px;vertical-align: top;padding-rigth 10px; ">
                     
                        <span style='font-size: 12px;padding-right: 10.5px;'  ><?php //echo date('m/d/Y', strtotime($v->dm_date_int)); ?></span>

                    </th>
                </tr>
                <tr>
                    <th colspan="2" style="padding:0;padding-left:10px;text-align: left;padding-bottom: 1px;">
                       <span style='font-size: 12px;'><?php // echo str_replace('#', '', str_replace('$', '', $v->SupplierName)); ?></span>
                    </th>
                </tr> 
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="border: none;height:10px;"></td>
                </tr>
                <tr>
                    <td colspan="2" class="amt" style="vertical-align: top; height:30px;padding-top:3px;padding-left:3px;border-bottom:none;">
                        <span style="display:block;visibility:hidden;padding-left:10px;margin-bottom: 10px;font-size: 11px;">We have debited your account in payment for:</span>
                        <span style="padding-left: 40px;font:11px Tahoma, sans-serif;"><?php //echo $v->category_name.' '.$v->period; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="amt" style="text-align: left; vertical-align: top; border-top: none;border-bottom:none;padding-left: 43px; height: 35px;"><span style='font:11px Tahoma, sans-serif;'><?php echo $v->remarks1; ?></span></td>
                </tr>
              <tr>
                    <td colspan="2" class="amt" style="border-top: none;border-bottom:none;text-align: right; padding-right: 30px; padding-bottom: 20px;"><span style='font-size: 11px;'>PHP</span> <span style="padding-left: 40px;border-bottom: double 4px #000;font-size: 11px;"><span style="padding-right: 10px;font-size: 11px;font-weight:700;font-family: Tahoma;"><?php echo number_format($v->amount, 2); ?></span></span></td>
                </tr>
                <tr>
                      <td colspan="3" style="padding-top: 10px;padding-left:10px; ">
                        <span style='font-size: 11px;'><?php //echo (($ttrans) ? 'Printed By: ':'Re-Printed By: ').$lcusername; ?></span>
                        <span style="display: block;font-size: 11px;">
                            <em style="margin-right: 20%; font-size: 11px;"><?php //echo date('m/d/Y'); ?></em>
                            <?php //if(isset($mainSitesList[trim($v->branch_code)]['code']) AND $mainSitesList[trim($v->branch_code)]['code'] !== $v->branch):
                                //echo '<em style="font-size: 10px;">Note: from '.trim($v->branch_code).'</em>';
                            //endif; ?>
                        </span>
                    </td>
                </tr>
            </tbody> 
        </table>
    </div> -->
    <?php
            endif;
        endforeach;
    ?> 
    </body>
    <script src="js/jquery1.7.js"></script>
    <script src="js/underscorejs.js"></script>
    <script type="text/javascript">
    $('.small-size').show();
    $('#page3').hide();
    $('.big-size').hide();
   $('#cmdprndetails2').toggle(function()    
        {
         $('#cmdprndetails2').text('Print Deduction Summary for Purchasing/Marketing');
            $('.small-size').hide();
            $('#page3').hide();
            $('.big-size').show();
        },function() {
             $('#cmdprndetails2').text('Print Deduction Slips');
            $('#page3').show();
            $('.small-size').hide(); 
            $('.big-size').hide();

        },function() {
            $('#cmdprndetails2').text('Print Deduction Summary for Accounting');
            $('.big-size').hide();
            $('.small-size').show();
            $('#page3').hide();
        }

            )

    </script>
    <script type="text/javascript">
        (function(){
            $('#chkidpage').on('click', function(){
                if($(this).is(':checked'))
                {
                    $('.small-size').addClass('no-print');
                    $('.page-cover').removeClass('no-print');
                }
                else
                {
                    $('.small-size').removeClass('no-print');
                    $('.page-cover').addClass('no-print');
                }
            });
            $(document).on('keyup', function(e){
                if(e.which === 13)
                {
                    var pcover = ($('.small-size').hasClass('no-print')) ? 1: 0;
                    var result = $.post('ajax/dsprint.php', { oids: '<?php echo implode("-", $ctrlno); ?>', pcover: pcover });

                    window.print();
                }
            });
            $('.getrefnum').on('click', function(e){
                e.preventDefault();
                data = $(this).data('id');
                result = $.post('ajax/generate_ds.php', { oids: data });

                result.done(function(data){
                    $.each(data, function(a, b){
                        $('#dsnumber'+b.id).empty().text(b.dm_no_acctg);
                        $('#parent-'+b.id).removeClass('no-print');
                    });
                });
            });
            $('#cmdprndetails').on('click', function(){
                var $this = $(this);

                if($('.small-size').hasClass('no-print'))
                    $this.html('<strong>Print Summary</strong>');
                else
                    $this.html('<strong>Print Deduction Slip</strong>');

//                if($('#cmdtogglesmall').is(':checked'))
//                {
                    // $('body').removeClass('print-small');
//                    $('.page-cover').slideToggle(function(){
//                    $('.main-content').toggleClass('no-display no-print');

//                    });

//                }
//                else
//                {
                    // $('body').addClass('print-small');
                $('.page-cover').slideToggle(function(){
                    $('.small-size').toggleClass('no-display no-print');
                });
//                }
                $('body').focus();

            });
        })();
    </script>
</html>
