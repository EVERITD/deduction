 <!doctype html>
 <html>
 <head>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="deduction-modules">
        <meta name="author" content="ever-itd">
        <link href="css2/multiple-select.css" rel="stylesheet"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="deduction-modules">
        <meta name="author" content="ever-itd">
        <link rel="stylesheet" href="css2/style.min.css" media="screen">
        <link rel="stylesheet" href="css2/bootstrap iE7.css" type="text/css"/>
        <script type="text/javascript" src="js2/jquery.min.js"></script>
        <script type="text/javascript" src="js2/bootstrap.min.js"></script>
        <!--<script type="text/javascript" src="js/jquery.js"></script>-->
        <link rel="stylesheet" href="css2/waitMe.css">
        <link rel="stylesheet" href="css2/style.css">
        <link rel="stylesheet" href="css2/prism.css">
        <link rel="stylesheet" href="css2/chosen2.css">
        <style type="text/css" media="all">
        .chosen-rtl .chosen-drop { left: -200000px; }
        </style>
        <link rel="stylesheet" type="text/css" media="screen" href="css2/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="css2/bootstrap-multiselect.css" type="text/css">
 </head>

<script type="text/javascript">
 /* var chk = "<?php echo $ctrlno;?>";
  var cmbbranch = "<?php echo $cmbbranch;?>";
  //reprint - dm_print_on.php?rprn=yes
 
  //var chk = "<?php echo $check;?>";
  if($.trim(chk) === "")
  {
  
  }
  else
  {
    $.trim(cmbbranch);
    $.post('dm_print_purch.php', {chk:chk, cmbbranch:cmbbranch},
      function(data)
      {
       
       window.location = "dm_print_purch.php?ctrlno="+chk+"&branch="+cmbbranch+"&need=yes";
      }

    );
          
  }*/
  // example $("#otherDevText").attr("disabled","disabled");


/*$(function(){
 
// none, bounce, rotateplane, stretch, orbit, 
// roundBounce, win8, win8_linear or ios
var current_effect = 'bounce'; // 
 
$('#demo').click(function(){
run_waitMe(current_effect);
});
 
function run_waitMe(effect){
$('#SELECTOR').waitMe({
effect: 'bounce',
text: '',
bg: 'rgba(255,255,255,0.7)',
color: '#000',
sizeW: '',
sizeH: ''
});
}
     */

</script>

 
<style type="text/css">
.font2
  {                
  font-family:"myFirstFont",Times,serif;
  font-size:75%;                  
  color: block;
  }
.background
  {

  min-height:20px;padding:19px;margin-bottom:20px;background-color:#F8F8F8;border:1px 
  solid #e3e3e3;-webkit-border-radius:4px;-moz-border-radius:4px;
  border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.05);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.05);
  box-shadow:inset 0 1px 1px rgba(0,0,0,0.05)
  }  

</style>
<script type="text/javascript">
$(document).ready(function() {
$('.multiselect').multiselect();
});
function multiselect(source) {
  
  checkboxes = document.getElementsByName('chk[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
 </script>

<body style="padding-top: 10px;">
<input type="hidden" name="count" value="<?php echo $count; ?>" />
<div class="container-fluid" >
  <div class="row-fluid" style="border-bottom:solid 1px #ddd; padding-bottom: 5px; margin-bottom: 10px;">
    <div class="span12">
      <a href="logout.php" class="btn btn-small h-btn" style="float: right; margin-top: 5px;">
          <strong>Hi! <?php echo $lcusername; ?></strong>
            <em> Log-Out</em>
      </a>
        <a href="deductionmain.php" class="btn btn-small h-btn" style="float: right; margin-top: 5px;margin-right: 5px;"><strong>&larr; Back</strong></a>
          <h4 style="padding-top: 0.001%;"><strong>DEDUCTION SLIP <?php echo ( !$count) ? 'PRINTING': 'RE-PRINTING'; ?></strong></h4>
   </div>
 </div>
 </div>
<form class="form-inline "  action = "deduction_slip_purch.php" method = "POST" id='form1' style='margin-right'>
      <div class =  'font2'>                
      <div class="span2">
        <div class="control-group">
            <label class="control-label" style="font-size: 12px;"><strong>Site Code</strong></label> 
          <div class="control-input">
            <select id = "demo" class="span2 font2" name = "opsite[]" id='opsite' multiple="multiple">
                <option value = ''></option>
                <?PHP while ($row = mssql_fetch_array($querybr)):?>
                    <option  value = "<?php  $ok =  $row['branch_code']; echo $ok ?>">
                        <?php 
                             $rep =  str_replace('S399','S306', $row['branch_code']);
                            echo $rep;?>
                    </option>
                <?PHP endwhile?>
            </select>                             
          </div>
        </div>
      </div>
      <div class="span2">
        <div class="control-group">
            <label class="control-label" style="font-size: 12px;"><strong>Buyer Code:</strong></label> 
           <div class="control-input">
            <select class="span2" name = 'opbuyer[]' id='opbuyer' multiple="multiple">
                <option value = ''></option>
                <?php while ($row = mssql_fetch_array($queryer)):?>
                        <option  value = "<?php   echo $row['buyerid'];?>">
                            <?php   echo $row['buyerid'].'-'.$row['buyer_code'];?>
                        </option>
                <?php endwhile ?>        
            </select>
        </div>
      </div>
    </div>
    <div class="span2">
        <div class="control-group">
            <label class="control-label" style="font-size: 12px;"><strong>Category Code:</strong></label> 
            <div class="control-input">
            <select class="span4"  name = "opcategory[]" id='opcategory' multiple="multiple" style='width:245px;'>
                <option value =''></option>
                <?php while ($row = mssql_fetch_array($queryry)):?>
                            <option value =  "<?php echo $row['category_code'];?>">
                                <?php echo $row['category_name'];?>
                            </option>

                <?php  endwhile?>
            </select>
        </div>
      </div>
    </div>
      <script src="js2/jquery.multiple.select.js"></script>
 <script>
        $('select').multipleSelect();
 </script>
  <script type="text/javascript" src="js2/bootstrap-multiselect.js"></script>
  <script type="text/javascript" src="js2/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript" src="js2/bootstrap-datetimepicker.pt-BR.js"></script>

    
    <div class="span2"  style="margin-top: -1px; margin-left: 120px; text-align: left;">
      <div class="control-group">  
        <div id="datetimepicker4" class="input-append">
          <label class="control-label" style="font-size: 12px; width:90%;">
          <strong>Review Date From:</strong></label> 
          <div "control-input"> 
        <input placeholder = 'Review Date' data-format="MM/dd/yyyy" type="text" style="width:95px; height:18px;"name='from' value="<?php echo date('m/d/Y'); ?>" ></input>
        <span class="add-on">
        <i data-time-icon="icon-time" data-date-icon="icon-calendar">
        </i>
        </span>

          </div>
        </div>
      </div>
    </div>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker4').datetimepicker({
      pickTime: false
    });
  });
</script>
 <div class="span3" style="margin-top: -1px;" id='date2'>
      <div class="control-group">  
        <div id="datetimepicker3" class="input-append">
          <label class="control-label" style="font-size: 12px; width:70%; ">
          <strong>Review Date To:</strong></label> 
          <div "control-input"> 
           <input placeholder = 'Review Date' data-format="MM/dd/yyyy" type="text" style="width:95px; height:18px;" name ='to' value ="<?php echo date('m/d/Y'); ?>" ></input>
            <span class="add-on">
            <i data-time-icon="icon-time" data-date-icon="icon-calendar">
            </i>
            </span> 
          </div>
        </div>
      </div>
  </div><br><br><br><br>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker3').datetimepicker({
      pickTime: false
    });
  });
</script>
 <br/>
  <div class="span3"  style="margin-top: -60px;margin-right: 001px;"  >
    <div class="control-group" id='button'>                
        <label class="control-label" style="font-size: 12px; width:150%; text-align: left;">
        <strong>Vendor:</strong></label> 
        <div class="control-input">
          <select data-placeholder =  'Select Vendor Code & Name'name = "opvendor[]" size="5" multiple  class="chosen-select " style="width:288px;" tabindex="10">
            <option value = ''></option>
            <?php while($row = mssql_fetch_array($queryvn)): ?>
            <option  value = "<?php echo $row['vendorcode']; ?>"><?php echo $row['SupplierName'].'<strong> - </strong>'.$row['vendorcode'];?></option>
            <?php endwhile ?>
          </select>

        </div>
        
        </div>
      </div>
<script src="js2/chosen.jquery.js" type="text/javascript"></script>
<script src="js2/prism.js" type="text/javascript" charset="utf-8"></script>
 <script type="text/javascript">
     var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"200%"},
      '.chosen-select-height'     : {height:"30px"}

    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>
  <div class= 'span6 tago' style="margin-top: -60px;margin-left: 320px;" id = 'buttons'>
            <label for="#" class="control-label">&nbsp;</label>
                <div class="control-input" style="padding-left: 20px;">
                  <div class="btn-group">
                        <button class="btn btn-small span2"  name="sub" ><strong>Load Data</strong></button>
                        <button class="btn btn-small span2"  name="prt" ><strong>Print</strong></button>
                        <button class="btn btn-small span2"  name="rprt" ><strong>Re-Print</strong></button>             
                  </div>
                </div>
  </div>



 <!-- <div class="span4">
  <div class="control-group">
  <div class="nav_button"> 
 <button type="submit" class="btn btn-default" name="sub" ><strong>Search</strong></button>
 <button class="btn btn-primary" name="prt"  ><strong>Print</strong></button>
 <button class="btn btn-primary" name="rprt" onclick = "nextpage()"><strong>Re-Print</strong></button>

</div>
</div>class="<?php echo ( ! $isReprint ) ? 'span6': 'span4'; ?>"
</div>   -->

<!--Css font-->  
</div> <br/>
<!---->
 <hr style="margin-top: 0; margin-bottom: 0;">
<div class="row-fluid" style='margin-left: 2px;'>
    <div class='span12'>
<table class="table table-striped table-condensed table-hover table-bordered font2" >
     <thead>
    <tr >
        <th> 
            <div class="checkbox">
            <label>
              <input type="checkbox" onClick="multiselect(this)" name=''> 
            </label>
          </div>
        </th>   
        <th width="6%" >Control No.</th>
        <th>Site</th>
        <th>Vcode</th>
        <th width="18%" >Vname</th>
        <th>Buyer</th>
        <th width="14%">Department</th>
        <th width="14%">Category</th>
        <th>Period</th>
        <th>Remarks</th>
        <th>Amount</th>
    </tr>
    </thead>
     <tbody id="tblcontent">


<?php
$xnum = @mssql_num_rows($querysr);
        if( ! $xnum): 
?>
<tr>
          <td colspan="100%" style="text-align: center; font-size: 18px; color: red; padding-top: 20px;  padding-bottom: 20px">No Record Found!</td>
</tr>
<?php else:?>
<?php while ($row = mssql_fetch_object($querysr)):?>                
     <tr>
        <td> 
            <div class="checkbox">
            <label>
              <input type="checkbox"  name='chk[]' value="<?php echo trim($row->dm_no);?>" data-date="<?php echo $row->fdm_date; ?>"> 
            </label>
          </div>
        </td>  
        <td><?php echo trim($row->dm_no);?></td>
        <td>
          <?php
          $rep =  str_replace('S399','S306', $row->branch_code); 
          echo $rep;?>
        </td>
        <td><?php echo trim($row->vendorcode);?></td>
        <td><?php echo trim($row->SupplierName);?></td>
        <td><?php echo trim($row->buyer_code);?></td>
        <td><?php echo trim($row->department);?></td>
        <td><?php echo trim($row->category_name);?></td>
        <td><?php echo trim($row->period);?></td>
        <td><?php echo trim($row->remarks1);?></td>
        <td><?php echo trim($row->amount);?></td>        
    </tr>
    <?php endwhile;?>
    <?php endif;?>  
        
    </tbody>
    </table>

</form>

  </div>
</div>
 




</body>


</html>