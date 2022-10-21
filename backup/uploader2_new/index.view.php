<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Deduction Uploader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="deduction-modules">
    <meta name="author" content="ever-itd">
    <link type="text/css" rel="stylesheet" href="../css/bootstrap.css" media="screen">
    <link type="text/css" rel="stylesheet" href="../css/bootstrap-responsive.css" media="screen">
    <link type="text/css" rel="stylesheet" href="../css/ui-lightness/jquery-ui.css" media="screen">
    <style type="text/css">
        th,
        td {
            font-size: 11px;
        }

        .h-btn {
            border: none;
        }

        .accordion-toggle {
            font-size: 12px;
        }

        .accordion-toggle:hover {
            text-decoration: none;
        }
    </style>
</head>

<body style="padding-top: 10px;">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class='btn-group pull-right'>
                    <a class='btn' href="index.php"><i class="icon-refresh"></i> Refresh</a>
                    <a class='btn' href="../deductionmain.php"><i class="icon-arrow-left"></i> Back</a>
                    <a class='btn' href="uploads/s301_deductions_201207.xls"><i class="icon-download-alt"></i> Download Template</a>
                    <a class='btn' href="../logout.php">Hi! <?php echo $lcusername; ?> <em><strong>Log-Out</strong></em></a>
                </div>
                <span style='font-size: 20px;'>Deductions [ <em>Uploader</em> ]</em></span>
            </div>
        </div>

        <hr style="margin-top: 0;">

        <?php if (count($fileErrors)) : ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="alert alert-error">
                        <?php echo implode('<br>', $fileErrors); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row-fluid">
            <div class="span12">
                <form action="" method="POST" enctype="multipart/form-data">

                    <div class="control-group">
                        <span class="control-label">Select file to upload (<em>csv & xls</em>):</span>
                        <div class="controls">
                            <input type="file" name="uplname" class="btn btn-small span5">
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="taremarks" class="control-label">Remarks (<em> additional notes </em>)</label>
                        <div class="controls">
                            <textarea name="taremarks" id="" cols="30" rows="5" class="span12"></textarea>
                        </div>
                    </div>

                    <div class="form-actions" style="padding: 5px;">
                        <input type="submit" name="cmbsubmit" class="btn btn-primary" value="Review & Continue">
                    </div>

                </form>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <?php
                $a = 0;
                foreach ($groupDate as $k => $v) :
                    $a++;
                ?>
                    <div class="accordion" id="accordion<?php echo $a; ?>" style="margin-bottom: 2px;">
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion<?php echo $a; ?>" href="#collapse<?php echo $a; ?>">
                                    <i class="icon-plus"></i> <?php echo date('F d, Y', strtotime($k)); ?> &mdash; Uploaded Files
                                </a>
                            </div>
                            <div id="collapse<?php echo $a; ?>" class="accordion-body collapse">
                                <div class="accordion-inner">

                                    <table class="table table-striped table-condensed" style="margin-bottom: 0;">
                                        <thead>
                                            <tr>
                                                <th width="7%">Upload Time</th>
                                                <th width="43%">Filename</th>
                                                <th width="20%">Remarks</th>
                                                <th width="20%">Logs</th>
                                                <th width="10%">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (count($v['details'])) :
                                                foreach ($v['details'] as $details) :
                                            ?>
                                                    <tr>
                                                        <td><?php echo date('h:i A', strtotime($details->upltime)); ?></td>
                                                        <td>
                                                            <strong><a href="fpreview.php?oid=<?php echo $details->id; ?>"><?php echo $details->filename; ?></a></strong>
                                                            <span style="display: block;"><em>By: <?php echo $details->upload_by; ?></em></span>
                                                        </td>
                                                        <td><?php echo $details->remarks; ?></td>
                                                        <td><?php echo $details->log_file; ?></td>
                                                        <td>
                                                            <?php
                                                            switch ($details->status_id) {
                                                                case 2:
                                                                    echo '<span class="label label-info">Processing</span>';
                                                                    break;
                                                                case 4:
                                                                    echo '<span class="label label-important">Deleted</span>';
                                                                    break;
                                                                case 5:
                                                                    echo '<span class="label label-info">Cancelled</span>';
                                                                    break;
                                                                default:
                                                                    echo '<span class="label label-success">Uploaded</span>';
                                                                    break;
                                                            }

                                                            ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <script type="text/javascript" src="../js/jquery1.7.js"></script>
    <script type="text/javascript" src="../js/underscorejs.js"></script>
    <script type="text/javascript" src="../js/handlebars.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>

</body>

</html>