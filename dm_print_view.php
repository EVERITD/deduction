<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Print Deduction Slip</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="deduction-modules">
        <meta name="author" content="ever-itd">

        <style type="text/css">
            body * {
                font-family: Arial;
            }
            @media print {
                .no-print, .print-button { display: none; }
                span, th {font-size:13px;}
                td {font-family: Arial, Helvetica, sans-serif; font-size: 13px !important;}
                .amt{font-size:13px !important;text-align: justify;}

                .print-small .container-fluid {
                    width: 450px !important;
                }
                /** @page { size: 8.5in 5.7in; } **/
            }
            table{width:100%;}
            th,td {font-size:13px;}

/*            .small-size th, .small-size td {
                border-left:solid 1px #000;
                border-right:solid 1px #000;
                border-bottom:solid 1px #000;
            }
*/
            .no-display { display: none; }
        </style>
    </head>
    <body>
        <div class="container-fluid no-print">
            <div class="row-fluid">
                <div class="span12">
                    <div class="alert alert-info">
                        <div class="pull-right">
<!--                             <label class="checkbox"><input type="checkbox" name="chkpage" id="chkidpage"> Print Page Cover Only</label> -->
                            <a href="deductionslip.php?ispr=<?php echo htmlspecialchars(strip_tags($_GET['rprn']), ENT_QUOTES); ?>" class="btn btn-small"><strong>Back</strong></a>
                            <button class="btn btn-small" type="button" id="cmdprndetails"><strong><?php echo ( ! $pageCover ) ? 'Print Summary': 'Print Deduction Slip'; ?></strong></button>
                        </div>
                        <em>Press Enter key to print. </em>
                    </div>
                </div>
            </div>
        </div>
    <?php if(count($rmDetails) > 0): ?>
        <div class="container-fluid page-cover <?php echo ( ! $pageCover ) ? 'no-display no-print': ''; ?>" style="height: 530px; margin-bottom: 180px;">
            <div class="row-fluid">
                <div class="span12">
                    <div style="text-align: center;">
                        <span style="display:block;"><?php echo trim($cBranch); ?></span>
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
                        <tbody>
                            <?php
                                $x      = 0;
                                $gtotal = 0;
                                $rCount = 0;
                                $rCount1= 0;
                                foreach($rmDetails['batch-'.$batchNo] as $m => $mm):
                                    $x++;
                                    $gtotal += $mm->amount;

                                    $isCancelled = (int) $mm->vposted === 2 ? '<span style="text-decoration: line-through;">:content</span>': ':content';

                                    if($rCount === 45):
                                        $rCount1++;
                                    endif;
                            ?>
                            <tr>
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
                                    if($rCount === 45)
                                        $rCount = 0;

                                    $rCount++;
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
                                <td colspan="2" style="text-align: right;padding-top:15px;">
                                    <?php echo (($ttrans) ? 'Printed By: ':'Re-Printed By: ').$lcusername; ?>
                                    <span style="display: block;"><?php echo date('m/d/Y'); ?></span>
                                </td>
                            </tr>
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
    ?>
    <div class="small-size <?php echo ( $pageCover ) ? 'no-display no-print': ''; ?>" style="margin-left:0;padding-left:0;width:320px;<?php if($xc !== count($ctrlno)): ?> page-break-after: always; <?php endif; ?>height: 440px;">
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="2" style="height: 45px; color: #fff !important; visibility: hidden;">
                        DEDUCTION SLIP
                    </th>
                </tr>
                <tr>
                    <th width="70%" style="text-align:left;" style="border-right:none;padding-bottom: 10px;">
                        <span style="display: block;padding:10px;padding-bottom:3px;">
                            <?php
                                $supplier = (@$consPoRowCount[trim($v->dm_no)]) ? explode('-', $mainSitesList[trim($v->branch_code)]['bname']):
                                    explode('-', $v->branch_name);
                                echo @$supplier[0];
                            ?>
                        </span>
                        <span style="padding-left: 10px;">
                            <?php echo (count($supplier) > 1) ? end($supplier): ''; ?>
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
                    <td colspan="2" style="border: none;height:10px;"></td>
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
                        <span style="display: block;"><?php echo date('m/d/Y'); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
            endif;
        endforeach;
    ?>
    </body>
    <script src="js/jquery1.7.js"></script>
    <script src="js/underscorejs.js"></script>
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
