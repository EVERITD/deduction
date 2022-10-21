<?php
    if(!defined('FCPATH')) { exit('No Direct Script Allowed!'); }
?>
    <html>
    <head>
        <title>User Account - </title>
        <link href="css/modal.css" rel="stylesheet" type="text/css" />
        <link href="css/styles.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jstime.js"></script>
        <link href="css/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css" />
        <script language="javascript" src="js/jquery-min.js"></script>
        <script language="javascript" src="js/jquery-ui.js"></script>
        <script type="text/javascript" language="javascript">
            $(function(){
                $('#divcontent').css({
                    opacity: .3
                }) 
                $('#cmdidlogin').button();
                $('#cmdidgoback').button({
                    icons: {
                        primary: 'ui-icon-refresh'
                    }
                });
                $('#cmdidgoback').click(function(){
					<?php
						//if ($_SESSION['cmbSystem'] == 1 ) {
					?>
		                    document.location.href = 'deductionmain.php';
					<?php	
						//} else {
					?>
							//document.location.href = 'billcounting.php';
					<?php
						//}
					?>
                })
            });
            function validateme()
            {
                if(jQuery.trim($('#txtidoldpass').val()) == '')
                {
                    alert('Empty/Invalid Old Password!');
                    $('#txtidoldpass').focus();
                    return false;
                }
                if($('#txtidpassword1').val()=='')
                {
                    alert('Empty/Invalid passwords!');
                    $('#txtidpassword1').focus();
                    return false;
                }
                if($('#txtidpassword1').val() != $('#txtidpassword2').val())
                {
                    alert('Password not match!');
                    $('#txtidpassword2').focus();
                    return false;
                }
            }
        </script>
    </head>
    <body  id="minwidth-body" background="images/img1.gif">
    <form name="frmuupdate" id="frmuupadateid" method="post" action="register.php?/edit/<?php echo $method[1]; ?>" onSubmit="return validateme()">
        <div class="" id="divcontent" style="background-color: #fff; position: absolute; top: 33%; left: 33%; height: 243px; width: 349px; padding: 5px;">
        </div>
        <div class="" style="position: absolute; top: 33%; left: 33%; height: 165px; width: 350px; padding: 5px;">
            <table width="100%" cellpadding="0" cellspacing="1" style="background-color: #fff;">            
                <tr>
                    <td colspan="100%" style="padding-top: 2px; padding-left: 1px; padding-right: 1px;">
                        <p class="ui-state-default ui-corner-all ui-helper-clearfix" style="padding:4px;">
                        <span class="ui-icon ui-icon-person" style="float:left; margin:-2px 5px 0 0;"></span>
                            <?php echo 'CHANGE PASSWORD'; ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="100%">
                        <div style="background-color: #fff; padding: 20px; height: 203px; overflow: auto;">
                            <table width="100%" cellpadding="0" cellspacing="1">
                                <?php
                                    if(isset($msg)){
                                ?>
                                <tr>
                                    <td colspan="100%" style="padding-top: 2px; padding-left: 1px; padding-right: 1px;">
                                        <p class="ui-state-<?php echo ($errorbox=='error') ? 'error': 'highlight'; ?> ui-corner-all ui-helper-clearfix" style="padding:4px;">
                                        <span class="ui-icon ui-icon-<?php echo ($errorbox=='error') ? 'alert': 'notice'; ?>" style="float:left; margin:-2px 5px 0 0;"></span>
                                            <?php echo $msg; ?>
                                        </p>
                                    </td>
                                </tr>
                                <?php
                                    } 
                                    if($update === FALSE)
                                    {
                                ?>
                                <tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">Old Password</td>
                                    <td width="60%"><input type="password" name="txtoldpass" id="txtidoldpass" tabindex="1" class="inputtext" /></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">New Password</td>
                                    <td width="60%"><input type="password" name="txtpassword1" id="txtidpassword1" tabindex="2" class="inputtext" /></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">Confirm Password</td>
                                    <td width="60%"><input type="password" name="txtpassword2" id="txtidpassword2" tabindex="3" class="inputtext" /></td>
                                </tr>
                                <tr>
                                    <td colspan="100%">&nbsp;</td>
                                </tr>
                                <?php
                                    }
                                ?>
                                <tr>
                                    <td colspan="100%" style="padding-top: 2px; padding-left: 1px; padding-right: 1px;">
                                        <div style="text-align: center;">
                                            <button type="button" name="cmdgoback" id="cmdidgoback" tabindex="5">Go Back</button>                                        
                                            <?php
                                                if($update != TRUE)
                                                {
                                            ?>
                                            <button type="submit" name="cmdlogin" id="cmdidlogin" tabindex="4">Update</button>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </form>
    </body>
    </html>