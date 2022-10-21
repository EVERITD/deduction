<?php
// echo '<pre>';
// var_dump($sheet);
// die();
?>

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
          <a class='btn' href="index.php">
            <i class="icon-arrow-left"></i> Back
          </a>
          <a class='btn' href="/hrisdev/uploader2/s301_deductions_201107.xls">
            <i class="icon-download-alt"></i> Download Template
          </a>
          <a class='btn' href="#">
            Hi! <?php echo $lcusername; ?>
            <em>Log-Out</em>
          </a>
        </div>
        <span style='font-size: 20px;'>
          Deductions [ <em>Uploader</em> ]</em>
        </span>
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
        <h5 style="float:left; margin-right: 5px;">
          <?php echo $file['name']; ?>
        </h5>
        <em style="float:left;">
          contains the following details. <?php if (!$viewOnly) : ?>Please
          take a little time to review. If everything seem so right,
          just hit the <strong style="color: red;">"Upload"</strong>
          button.<?php endif; ?>
        </em>
      </div>
    </div>

    <hr style="margin-top: 0;">

    <?php if (!$viewOnly) : ?>

      <div class="row-fluid">
        <div class="span12" style="text-align: right;">
          <strong>Legend:</strong> <span class="ui-icon ui-icon-close" style="display: inline-block;"></span> - <em>Failed to meet the required fields.</em>
          <span class="ui-icon ui-icon-check" style="display: inline-block;"></span> - <em>Good.</em>
        </div>
      </div>

    <?php else : ?>

      <div class="row-fluid">
        <div class="span12" style="font-size: 11px;">
          <strong>Upload Date:</strong> <span style="margin-right: 20px;"><?php echo $file['upload_date']; ?></span>
          <strong>Upload By:</strong> <span style="margin-right: 20px;"><?php echo $file['upload_by']; ?></span>
          <span style="display: block"><strong>Remarks:</strong> <span style="margin-right: 20px;"><em><?php echo $file['remarks']; ?></em></span></span>
        </div>
      </div>
      <!-- Review and Continue -->
    <?php endif; ?>

    <div class="row-fluid">
      <div class="span12">

        <hr style="margin-top: 0;">

        <table class="table table-striped table-condensed" style="margin-bottom: 0;">
          <thead>
            <tr>
              <th width="3%">&nbsp;</th>
              <th width="5%">Branch</th>
              <th width="5%">Division</th>
              <th width="6%">Department</th>
              <th width="6%">Category</th>
              <th width="6%">Sub Category</th>
              <th width="6%">Promo</th>
              <th width="12%">DisplayA</th>
              <th width="4%">Buyer</th>
              <th width="10%">Item Dept</th>
              <th width="7%">SupplierCode</th>
              <th width="9%">Period</th>
              <th width="7%" style="text-align: center;">Amount</th>
              <th width="5%">PaymentID</th>
              <th width="17%">Remarks</th>
              <th width="5%" style="text-align: center;">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $dataIndex = 0;
            $totalErrCount = 0;
            $total = 0;
            // foreach($spreadSheet->sheets as $k => $data):
            //     if($k === 8):
            // foreach($data['cells'] as $row):
            $xi = 0;
            if (!$viewOnly and count($sheet) > 0) :
              foreach ($sheet as $row) :
                if ($dataIndex !== 0 and ($row['A']
                  or $row['B']
                  or $row['C']
                  or $row['D']
                  or $row['E']
                  or $row['F']
                  //OR $row['F']
                  or $row['G']
                  or $row['H']
                  or $row['I']
                  or $row['J']
                  or $row['K']
                  or $row['L']
                )) :
                  $xi++;

                  // validate all entries
                  $errCount = validateEntries($row);
                  $totalErrCount += $errCount;
            ?>
                  <tr>
                    <td style="text-align: center;"><?php echo $dataIndex; ?></td>
                    <td><?php echo $row['A']; ?></td>
                    <td><?php echo $row['B']; ?></td>
                    <td><?php echo $row['C']; ?></td>
                    <td><?php echo $row['D']; ?></td>
                    <td><?php echo $row['E']; ?></td>
                    <td><?php echo $row['F']; ?></td>
                    <td><?php echo $row['G']; ?></td>
                    <td style="text-align: center;"><?php echo $row['H']; ?></td>
                    <td><?php echo $row['I']; ?></td>
                    <td><?php echo $row['J']; ?></td>
                    <td><?php echo $row['L']; ?></td>
                    <td style="text-align: right;"><?php echo number_format($row['L'], 2); ?></td>
                    <td style="text-align: center;"><?php echo $row['M']; ?></td>
                    <td><?php echo $row['N']; ?></td>
                    <td id="status" data="<?php echo $dataIndex; ?>" style="text-align: center;">
                      <span class="ui-icon <?php echo ($errCount) ? 'ui-icon-close' : 'ui-icon-check'; ?>"></span>
                    </td>
                  </tr>
                <?php
                  $total += $row['I'];
                endif;
                $dataIndex++;
              endforeach;
            else :
              while ($dm = mssql_fetch_object($dmDetails)) :
                $dataIndex++;
                ?>
                <tr>
                  <td style="text-align: center;"><?php echo $dataIndex; ?></td>
                  <td><?php echo $dm->branch_code; ?></td>
                  <td><?php echo $dm->division_code; ?></td>
                  <td><?php echo $dm->dept_code; ?></td>
                  <td><?php echo $dm->category_code; ?></td>
                  <td><?php echo $dm->subcat_code; ?></td>
                  <td><?php echo $dm->disparea; ?></td>
                  <td style="text-align: center;"><?php echo $dm->buyerid; ?></td>
                  <td><?php echo $dm->department; ?></td>
                  <td><?php echo $dm->vendorcode; ?></td>
                  <td><?php echo $dm->period; ?></td>
                  <td style="text-align: right;"><?php echo number_format($dm->amount, 2); ?></td>
                  <td style="text-align: center;"><?php echo $dm->paymentid; ?></td>
                  <td><?php echo $dm->remarks1; ?></td>
                  <td id="status" data="<?php echo $dataIndex; ?>" style="text-align: center;">
                    <span class="ui-icon <?php echo ($errCount) ? 'ui-icon-close' : 'ui-icon-check'; ?>"></span>
                  </td>
                </tr>
            <?php
                $total += $dm->amount;
              endwhile;
            endif;
            // break;
            // endif;
            // endforeach;
            ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="10"><strong>Total</strong></td>
              <td style="text-align: right;"><strong><?php echo number_format($total, 2); ?></strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="14"><strong>File Status: </strong> <span style="color:red;text-decoration:underline;"><em><?php echo ($totalErrCount) ? 'Can\'t be processed, due to empty required fields.' : 'Good.'; ?></em></span></td>
            </tr>
          </tfoot>

        </table>

      </div>
    </div>

    <?php if (!$viewOnly and !$totalErrCount) : ?>

      <div class="row-fluid">
        <div class="span12">
          <div class="form-actions" style="padding: 5px;">
            <button type="button" id="cmdsavecontent" data-file="<?php echo $file['name']; ?>" data-remarks="<?php echo $remarks; ?>" class="btn btn-small btn-primary" data-loading-text="Loading..."><strong>Upload</strong></button>
          </div>
        </div>
      </div>

    <?php endif; ?>

  </div>

  <script type="text/javascript" src="../js/jquery1.7.js"></script>
  <script type="text/javascript" src="../js/underscorejs.js"></script>
  <script type="text/javascript" src="../js/handlebars.js"></script>
  <script type="text/javascript" src="../js/jquery-ui.js"></script>
  <script type="text/javascript" src="../js/bootstrap.js"></script>

  <script type="text/javascript">
    (function() {
      $('#cmdsavecontent').on('click', function() {
        var ufile = $(this).data('file'),
          remarks = $(this).data('remarks'),
          result = $.post('process_xls_ajax.php', {
            file: ufile,
            rm: remarks
          });
        console.log(result)
        result.success(function(data) {
          if (data.status === 'success') {
            setTimeout('window.location.href = "index.php"');
          }
        });
      });
    })();
  </script>

</body>

</html>