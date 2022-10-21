<!DOCTYPE html>
<html>
<head>
    <marquee><title>Deduction Printing</title></marquee>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
</head>
<body>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span4"></div>
    <div class="span4">
    <fieldset>
        <legend>Print Menu</legend>
        <form class=''>
         <button class="btn btn-large btn-block btn-primary" id='btnpds' type="button">Print Deduction Slip</button>  
         <button class="btn btn-large btn-block btn-primary" id='pdsfa'  type="button">Print Deduction Summary for Accounting</button>
         <button class="btn btn-large btn-block btn-primary" id='pdsfpm' type="button">Print Deduction Summary for Purchasing/Marketing</button>
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