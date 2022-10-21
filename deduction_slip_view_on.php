<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Printing Deduction Slip</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="deduction-modules">
        <meta name="author" content="ever-itd">
        <link rel="stylesheet" href="css/style.min.css" media="screen">
        <style type="text/css">
            th,td {font-size:11px;}
            .h-btn{border:none;}
            .no-show{display: none;}
        </style>
    </head>
    <body style="padding-top: 10px;">
        <div class="container-fluid">
            <div class="row-fluid" style="border-bottom:solid 1px #ddd; padding-bottom: 10px; margin-bottom: 20px;">
                <div class="span12">
                    <a href="logout.php" class="btn btn-small h-btn" style="float: right; margin-top: 5px;">
                        <strong>Hi! <?php echo $lcusername; ?></strong>
                        <em> Log-Out</em>
                    </a>
                    <a href="<?php echo ( ! $isReprint ) ? 'deductionmain.php': 'deductionslip_on.php?branch=%'; ?>" class="btn btn-small h-btn" style="float: right; margin-top: 5px;margin-right: 5px;"><strong>&larr; Back</strong></a>
                    <h4 style="padding-top: 10px;">DEDUCTION SLIP <?php echo ( ! $isReprint ) ? 'PRINTING': 'RE-PRINTING'; ?></h4>
                </div>
            </div>
            <form action="dm_print_on.php?rprn=<?php echo $isReprint; ?>" method="POST" id="frmload">
            <input type="hidden" name="txttranstype" id="txttranstypeid" value="<?php echo $isReprint; ?>">
            <?php if(date('Y-m-d') === '2013-01-19' AND !$isReprint): ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="alert alert-info" style="font-family: 'Trebuchet MS'; font-size: 11px;">
                        <span style="display: block;">NOTE: <strong>no effect on re-printing.</strong></span>
                        Due to unforseen issue on printing individually we'll remove the print icon ( <i class="icon-print"></i> ) on this page only. Thanks!
                        <span style="display: block;">by: raffy</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="row-fluid">
                <div class="span3">
                    <div class="control-group">
                        <label for="cmbbranch" class="control-label" style="font-size: 11px;"><strong>Branches</strong></label>
                        <div class="control-input">
                            <?php echo $select; ?>
                            <?php echo $selectOBranch; ?>
                        </div>
                        <!-- Remove due to changes in printing. -->
                        <!--
                        <div class="control-group">
                            <label class="checkbox"><input type="checkbox" name="chkconspo" id="chkconspo">Show Ordering Branch Only</label>
                        </div>
                        -->
                    </div>
                </div>
                <!-- kapag reprinting lang ito dapat mag-appear -->
                <?php if( $isReprint ): ?>
                <div class="span2">
                    <div class="control-group">
                        <label for="cmbbranch" class="control-label" style="font-size: 11px;"><strong>Specify printed date to re-print</strong></label>
                        <div class="control-input">
                            <input type="text" id="xfrdate" name="sdate" value="<?php echo date('m/d/Y'); ?>" style="width: 90%; float: left; margin-left: -220xp; text-align: center;" readOnly="readOnly">
                        </div>
                    </div>
                </div>
                <div class="span2">
                    <div class="control-group">
                        <label for="cmbbranch" class="control-label" style="font-size: 11px;"><strong>Batch - PrintBy</strong></label>
                        <div class="control-input batch-selector">
                            <?php echo $bselect; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- end ng date -->
                <div class="<?php echo ( ! $isReprint ) ? 'span6': 'span4'; ?>">
                    <label for="#" class="control-label">&nbsp;</label>
                    <div class="control-input" style="padding-left: 20px;">
                        <div class="btn-group">
                        <button class="btn btn-small span2" type="submit" id="cmdreload"><strong>Load Data</strong></button>
                        <button class="btn btn-small span2" type="submit" id="cmdprint"><strong>Print</strong></button>
                        <?php if( ! $isReprint ): ?>
                        <button class="btn btn-small span2" type="button" id="cmdreprint"><strong>Re-Print</strong></button>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr style="margin-top: 0; margin-bottom: 0;">
            <div class="row-fluid">
                <div class="span12">
                    <?php if( ! $isReprint ): ?>
                    <h5 style="margin-bottom: 5px;">Select from the list of approved deduction. </h5>
                    <?php endif; ?>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th width="3%" style="text-align: center;"><input type="checkbox" value="0" name="checkmain"></th>
                                <?php if( ! $isReprint ): ?>
                                <th width="7%">Ctrl No. </th>
                                <?php else: ?>
                                <th width="7%">Ctrl No. </th>
                                <th width="7%">DS No. </th>
                                <?php endif; ?>
                                <th width="<?php ( ! $isReprint ) ? '27%': '20%'; ?>">Vendor</th>
                                <th width="8%" style="text-align: center;">Amount</th>
                                <th width="1%">&nbsp;</th>
                                <th width="<?php ( ! $isReprint ) ? '35%': '28%'; ?>">Remarks</th>
                                <th width="7%">Encoded</th>
                                <th width="7%">Approved</th>
                                <th width="5%">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody id="tblcontent">
                            <!-- start ng table content -->
                            <?php

                                if( ! $isReprint):
                                while($rst = mssql_fetch_object($rs)):
                                    $isCancelled = $rst->vposted === 2 ? '<span style="text-decoration: line-through;">:content</span="style: ">': ':content';
                                
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" name="chk[]" value="<?php echo $rst->dm_no; ?>" data-date="<?php echo $rst->fdm_date; ?>">
                                </td>
                                <td id="vnumber<?php echo $rst->dm_no; ?>"><?php echo str_replace(':content', $rst->dm_no, $isCancelled); ?></td>
                                <td><?php echo $rst->vendorcode; ?> - <?php echo str_replace(':content', $rst->SupplierName, $isCancelled); ?></td>
                                <td style="text-align: right;"><?php echo str_replace(':content', $rst->amount, $isCancelled); ?></td>
                                <td>&nbsp;</td>
                                <td><?php echo str_replace(':content', trim($rst->remarks1).trim($rst->LBR_Number), $isCancelled); ?></td>
                                <td>
                                    <span style="display: block;"><?php echo $rst->encoded_by; ?></span>
                                    <span style="display: block;"><?php echo date('Y-m-d', strtotime($rst->fenc_date)); ?></span>
                                </td>
                                <td>
                                    <span style="display: block;"><?php echo $rst->review_by; ?></span>
                                    <span style="display: block;"><?php echo date('Y-m-d', strtotime($rst->freview_date)); ?></span>
                                </td>
                                <td style="text-align: center;"><!-- <a href="dm_print.php?ctrlno={{ctrlno}}&{{#if isprint}}rprn=0{{/if}}"><i class="icon-print"></i></a> --></td>
                            </tr>
                            <?php
                                    endwhile;
                                endif;
                                $xnum = @mssql_num_rows($rs);
                                if( ! $xnum):
                            ?>
                            <tr>
                                <td colspan="100%" style="text-align: center; font-size: 18px; color: red; padding-top: 20px;  padding-bottom: 20px">No Record Found!</td>
                            </tr>
                            <?php
                                endif;
                            ?>
                            <!-- end ng table content -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="100%">&nbsp;</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
            </div>
        </div>
        <script type="text/x-handlebars-template" id="template-content">
            {{#each content}}
            <tr>
                <td style="text-align: center;">
                    {{#if isprint}}
                        <i class="icon-ok-circle"></i>
                        <input type="hidden" name="chk[]" class="hh-chk" value="{{ctrlno}}" data-date="{{fdmdate}}" />
                    {{else}}
                        <input type="checkbox" name="chk[]" value="{{ctrlno}}" {{#if isprint}} readOnly="readOnly" checked="checked" {{/if}} data-date="{{fdmdate}}">
                    {{/if}}
                </td>
                {{#if vposted}}

                <?php if( ! $isReprint): ?>
                    <td>{{ctrlno}}</td>
                <?php else: ?>
                    <td id="dsctrlno}}">{{ctrlno}}</td>
                    <td id="dsnumber{{ctrlno}}">{{dm_no_acctg}}</td>
                <?php endif; ?>

                <td>{{vendorcode}} - {{suppliername}}</td>
                <td style="text-align: right;">{{amount}}</td>
                <td>&nbsp;</td>
                <td>{{remarks1}} {{lbrno}}</span></td>
                <td>
                    <span style="display: block;">{{encoded_by}}</span>
                    <span style="display: block;">{{encoded}}</span>
                </td>
                <td>
                    <span style="display: block;">{{reviewed_by}}</span>
                    <span style="display: block;">{{reviewed}}</span>
                </td>

                {{else}}

                <?php if( ! $isReprint): ?>
                    <td>{{ctrlno}} / {{vposted}}</td>
                <?php else: ?>
                    <td id="dsctrlno}}"><span style="text-decoration: line-through;">{{ctrlno}}</span></td>
                    <td id="dsnumber{{ctrlno}}"><span style="text-decoration: line-through;">{{dm_no_acctg}}</td>
                <?php endif; ?>

                <td><span style="text-decoration: line-through;">{{vendorcode}} - {{suppliername}}</span></td>
                <td style="text-align: right;"><span style="text-decoration: line-through;">{{amount}}</span></td>
                <td>&nbsp;</td>
                <td><span style="text-decoration: line-through;">{{remarks1}} {{lbrno}}</span></td>
                <td>
                    <span style="display: block;"><span style="text-decoration: line-through;">{{encoded_by}}</span></span>
                    <span style="display: block;">{{encoded}}</span>
                </td>
                <td>
                    <span style="display: block;"><span style="text-decoration: line-through;">{{reviewed_by}}</span></span>
                    <span style="display: block;">{{reviewed}}</span>
                </td>
                {{/if}}
                <td style="text-align: center;"><?php if($isReprint): ?><a href="dm_print.php?ctrlno={{ctrlno}}&{{#if isprint}}rprn=no{{/if}}"><i class="icon-print"></i></a> <?php endif; ?></td>
            </tr>
            {{/each}}
        </script>
        <script type="text/x-handlebars-template" id="template-combo">
        {{#each content}}
        <option value="{{id}}-{{mainid}}">{{id}} - {{printby}}</option>
        {{/each}}
        </script>
        <script src="js/jquery1.7.js"></script>
        <script src="js/underscorejs.js"></script>
        <script src="js/handlebars.js"></script>
        <script src="js/jquery-ui.js"></script>
        <script src="js/jquery.pnotify.min.js"></script>
        <script type="text/javascript">
            ;(function(){
                var $frm = $('#frmload')
                    , $cmd = $('#cmdreload')
                    , template = $('#template-content')
                    , $tbl = $('#tblcontent');

                $('#xfrdate').datepicker({
                    defaultDate: '+1w'
                });

                $cmd.on('click', function(e){
                    data = $frm.serialize();
                    var rs = $.post('deduction_slip_ajax_on.php', data);
                    rs.success(function(data){
                        $tbl.empty();
                        if(data.status === 1)
                        {
                            var _html = Handlebars.compile(template.html());
                            _xhtml = _html(data);
                            $tbl.append(_xhtml);
                        }
                        else
                        {
                            $tbl.append('<tr><td colspan="11" style="text-align: center; font-size: 18px; color: red; padding-top: 20px;  padding-bottom: 20px">No Record Found!</td></tr>')
                        }
                    });
                    e.preventDefault();
                });

                $('#cmdprint').on('click', function(e){

                    var $sel = $('input[name*="chk"]:checked')
                        , _selcnt = $sel.length + $('.hh-chk').length
                        , oids = ''
                        , xdate = [];

                    if (_selcnt <= 0) {
                        $.pnotify({
                            title: 'Oppsss...',
                            text: 'No deposit slip to print!',
                            type: 'error'
                        });

                        e.preventDefault();
                        return false;
                    }

                });

                $('input[name="checkmain"]').on('click', function(){
                    if($(this).is(':checked'))
                    {
                        $('input[name*="chk"]').attr('checked', true);
                    }
                    else $('input[name*="chk"]').attr('checked', false);
                });

                function _process(data)
                {
                    result = $.post('ajax/generate_ds.php', { oids: data });

                    result.done(function(data){
                        $.each(data, function(a, b){
                            $('#dsnumber'+b.id).empty().text(b.dm_no_acctg);
                        });
                    });
                }
                <?php if($isReprint): ?>
                $('#xfrdate, #cmdbranchid').on('change', function(e){
                    var isCons = $('#chkconspo').is(':checked');
                    var rs = $.post('deduction_slip_ajax_batch_on.php', { branch: $('select[name="cmbbranch"]').val(), odate: $('#xfrdate').val(), isconspo: isCons });
                    rs.done(function(data){
                        var $batch = $('#cmbbatchid');
                        $batch.children().remove();

                        if(data.content.length === 0)
                        {
                            $batch.append('<option value="0">no-batch-found</option>');
                            return false;
                        }

                         // $('.batch-selector').empty().append(data.content);
                        var _html = Handlebars.compile($('#template-combo').html());
                        _xhtml = _html(data);
                        $batch.append(_xhtml);
                    });
                });
                <?php else: ?>
                $('#xfrdate, #cmdbranchid').on('change', function(e){
                    $tbl.empty();
                });
                <?php endif; ?>

                $('.showme').live('click', function(e){
                    e.preventDefault();

                    var data = $(this).data('id');
                    _process(data);

                    return false;
                });

                $('#cmdreprint').on('click', function(){
                    window.location.href = "deductionslip_on.php?ispr=yes";
                });

                $('#chkconspo').on('click', function(){
                    if($(this).is(':checked'))
                    {
                        $('#cmdobranchid').css('display', 'inline');
                        $('#cmdbranchid').css('display', 'none');
                        $('#cmdobranchid').attr('name', 'cmbbranch');
                        $('#cmdbranchid').attr('name', 'cmbbranch-1');
                    }
                    else
                    {
                        $('#cmdobranchid').css('display', 'none');
                        $('#cmdbranchid').css('display', 'inline');
                        $('#cmdbranchid').attr('name', 'cmbbranch');
                        $('#cmdobranchid').attr('name', 'cmbbranch-1');
                    }
                });
            })();
        </script>
    </body>
</html>
