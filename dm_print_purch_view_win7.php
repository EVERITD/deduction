<!DOCTYPE html>
<html>
<head>
    <marquee><title>Deduction Printing</title></marquee>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
</head>
<body>
<div class="container-fluid">
<?php $array=array('MKT','PUR'); ?>
<div class="container-fluid">
  <div class="row-fluid" style="border-bottom:solid 1px #ddd; padding-bottom: 10px; margin-bottom: 20px;">
                <div class="span12">
                    <a href="logout.php" class="btn btn-small h-btn" style="float: right; margin-top: 5px;">
                        <strong>Hi! <?php echo $lcusername; ?></strong>
                        <em> Log-Out</em>
                    </a>
                    <a href="<?php echo ( ! $isReprint ) ? 'deductionmain.php': 'deductionslip.php'; ?>" class="btn btn-small h-btn" style="float: right; margin-top: 5px;margin-right: 5px;"><strong>&larr; Back</strong></a>
                    <h4 style="padding-top: 10px;">DEDUCTION SLIP <?php echo ( ! $isReprint ) ? 'PRINTING': 'RE-PRINTING'; ?></h4>
                </div>
    </div>
  <div class="row-fluid">
    <div class="span4"></div>
    <div class="span4">
    <fieldset>
        <legend>Print Menu</legend>
        <form class=''>
         <button class="btn btn-large btn-block btn-primary" id='btnpds' type="button">Print Deduction Slip</button>  
         <button class="btn btn-large btn-block btn-primary" id='pdsfa'  type="button">Print Deduction Summary for Accounting</button>
           <?php if(in_array($dept_code, $array)):?>
            <button class="btn btn-large btn-block btn-primary" id='pdsfpm' type="button">Print Deduction Summary for Purchasing/Marketing</button>
         <?php endif;?>
        </form>
    </fieldset>
    </div>
    <div class="span4"></div>
  </div>
</div>  

<script type="text/javascript" src="js/jquery1.7.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript">
var ctrlno2 = new Array(<?php echo $ctrlno2; ?>) ;
var ctrlno = "<?php echo $where; ?>" ;
var prt = "<?php echo $prt; ?>";
var branch = "<?php echo $brd; ?>";
var need = "<?php echo $need; ?>";
var transType = "<?php echo $transType; ?>";
var pbranch = "<?php echo $pbranch; ?>";
var ctrlll = "<?php echo $ctrlll; ?>";

$("#btnpds").click(function(){
   
   window.open("../deduction/print/dmprint.php?rprn="+transType+"&cmbranch="+pbranch+"&need="+need+"&branch="+branch+"&prt="+prt+"&chk[]="+ctrlno+"&ctrlno="+ctrlll+'&active=1');
});
$("#pdsfa").click(function(){
    window.open("../deduction/print/dmprint.php?rprn="+transType+"&cmbranch="+pbranch+"&need="+need+"&branch="+branch+"&prt="+prt+"&chk[]="+ctrlno+"&ctrlno="+ctrlll+'&active=2');
});
$("#pdsfpm").click(function(){
    window.open("../deduction/print/dmprint.php?rprn="+transType+"&cmbranch="+pbranch+"&need="+need+"&branch="+branch+"&prt="+prt+"&chk[]="+ctrlno+"&ctrlno="+ctrlll+'&active=3');

});
</script>
</body>
</html>