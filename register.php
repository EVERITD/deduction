<?php
/**
 * @author EVER-ITD
 * @copyright 2010
 */

 // TODO -p 4 -c Deposit Slip -o ITD: Deposit Slip of Security Bank & RCBC pickup & BPI (all deposit slip for test print)

    include('sqlconn.php');
    include('function.php');

    define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
    define('FCPATH', str_replace(SELF, '', __FILE__));
    define('EXT', '.php');
    
    error_reporting(0);
    
    class Ever_Core {

	    private static $instance;

	    public function Ever_Core(){
		    self::$instance =& $this;
	    }

	    public static function &get_instance()
	    {
		    return self::$instance;
	    }
    }

    function &get_instance()
    {
	    return Ever_Core::get_instance();
    }

    function &load_class($class)
    {
	    static $objects = array();

	    require('class.encrypt'.EXT);
        
	    $name = $class;
	    $objects[$class] =& instantiate_class(new $name());
	    return $objects[$class];
    }

    function &instantiate_class(&$class_object)
    {
	    return $class_object;
    }


    /**
    * Remove Invisible Characters
    *
    * This prevents sandwiching null characters
    * between ascii characters, like Java\0script.
    *
    * @access    public
    * @param    string
    * @return    string
    */
    function _remove_invisible_characters($str)
    {
        static $non_displayables;

        if ( ! isset($non_displayables))
        {
            // every control character except newline (dec 10), carriage return (dec 13), and horizontal tab (dec 09),
            $non_displayables = array(
                                        '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
                                        '/%1[0-9a-f]/',                // url encoded 16-31
                                        '/[\x00-\x08]/',            // 00-08
                                        '/\x0b/', '/\x0c/',            // 11, 12
                                        '/[\x0e-\x1f]/'                // 14-31
                                    );
        }

        do
        {
            $cleaned = $str;
            $str = preg_replace($non_displayables, '', $str);
        }
        while ($cleaned != $str);

        return $str;
    }

    /**
     * Escape String
     *
     * @access    public
     * @param    string
     * @return    string
     */
    function escape_str($str)
    {
        if (is_array($str))
        {
            foreach($str as $key => $val)
               {
                $str[$key] = $this->escape_str($val, $like);
               }
           
               return $str;
           }

        $str = str_replace("'", "''", _remove_invisible_characters($str));
        
        return $str;
    }
    
    if (is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '')
    {
        if(key($_GET)=='DBGSESSID')
        {
            $uri_string = '';
        }
        else
        {
            $uri_string = trim(key($_GET));
        }
    }

    // load the encryption class
    $enc =& load_class('Ever_Encrypt');
    
    // if $uri_string is empty, it means that the call is on index
    // format should be, /controller/action/id since i dont have controller, this will be the format, /action/id
    // remove the first occurence of slash('/') character

    if(trim($uri_string)=='')
    {
        $method[0] = 'new';
    } 
    else 
    {
        $uri_string = substr($uri_string, 1, strlen($uri_string));
        $method     = explode('/', substr($uri_string, 0, strlen($uri_string)));
        $method[1]  = substr($uri_string, strpos($uri_string, '/')+1, strlen($uri_string));
        
        // check the database if the entered data is/are correct.
        // if exist prompt the user the error, if not exist add to the database
        // pass the post data then clear cache
        $post = $_POST;
        
        // clear cache
        $_POST  = array();
        $_GET   = array();
        
        // check input entry for unqualified keys...
        $new_array = array();
        
        foreach($post as $key => $val)
        {
            $new_array[$key] = escape_str(trim($val));
        }
        
        $post   = $new_array;
        
        $qry    = "EXEC ENCRYPT_PASS '{$post['txtpassword1']}'";
        $rs     = mssql_fetch_array(mssql_query($qry));
        
        $qry    = "SELECT * FROM ref_users WHERE
                    user_name = '{$post['txtlogin']}'
                  ";
        $rowcount = mssql_num_rows(mssql_query($qry));

        if(trim($method[0]) === 'new')
        { 
            // rowcount is 0 means no record..
            if($rowcount == 0)
            {
                $qry =  "INSERT INTO ref_users(
                            user_name, password, branch_code, name,division_code, dept_code, access_right,isactive, audit_user, audit_date 
                         ) values (
                            '{$post['txtlogin']}',
                            '{$rs[0]}',
							'{$post['cmbbranch']}', 
                            '{$post['txtusername']}',
							'{$post['cmbdivision']}'
                            '{$post['cmbdept']}',
                            '{$post['cmbtype']}',
                            '".date("Y-m-d H:i:s")."',
                            'SYSTEM'
                         )";
                $rsA = mssql_query($qry);

                if(!$rsA)                
                {
                    $msg        = "Failed! SQL Error!";
                    $errorbox   = "error";
                }
                else
                {
                    // generate a no-reply email to the administrator
                    $msg        = "Account Successfully Created!";
                    $errorbox   = "success";
                }                    
                // require page then exit
                ob_start();
                
                include('v_register.php');
                
                $buffer = ob_get_contents();
                
                @ob_end_clean();
                
                echo $buffer;
                exit;
            } 
            else    // failed, so we return message
            {
                $msg        = "Failed! Account Already Exist!";
                $errorbox   = "error";
            }
        }
        if(trim($method[0]) === 'edit')
        {
            session_start();

            // redirect view to editing
            // catch if the user commit changes, update then, return
            // marker
           
            if(isset($_SESSION['user']))
            {
                $qry    = "select * from ref_users where user_name='{$_SESSION['user']}'";
                $rs     = mssql_query($qry);
                $rs     = mssql_fetch_object($rs);
                
                $qry    = "EXECUTE DECRYPT_PASS '{$rs->password}'";
                $rsA    = mssql_fetch_array(mssql_query($qry));

                $update = FALSE;
                if(isset($post['cmdlogin']))
                {
                    if($post['txtoldpass']!=$rsA[0])
                    {
                        $msg        = "Invalid Password";
                        $errorbox   = "error";
                    } else 
                    {
                        $qry    = "EXECUTE ENCRYPT_PASS '{$post['txtpassword1']}'";
                        $rsB    = mssql_fetch_array(mssql_query($qry));
                        
                        $qry        = "UPDATE ref_users set password='{$rsB[0]}' where user_name='{$_SESSION['user']}'";
                        $rs         = mssql_query($qry);

                        if(!$rs)                
                        {
                            $msg        = "Failed! SQL Error!";
                            $errorbox   = "error";
                        }
                        else
                        {
                            $msg        = "Successfuly Update";
                            $errorbox   = "success";
                            $update = TRUE;    
                        }                    
                    }
                }
                
                ob_start();
                
                include('v_register_edit.php');
                
                $buffer = ob_get_contents();
                
                @ob_end_clean();
                
                echo $buffer;
                exit;
            }
        }
    }
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
        <script language="javascript" type="text/javascript">
            $(function(){
                $('#cmdidsignup').button();
                $('#cmdidreturn').button({
                    icons: {
                        primary: 'ui-icon-refresh'
                    } 
                });
                $('#cmdidreturn').click(function(){
                    document.location.href = 'login.php';
                });
                $('#tabs').tabs();
                $('#divcontent').css({
                    opacity: .3
                })    
            });
            function validateme()
            {
                if($('#cmbidbranch').val() == 0)
                {
                    alert('Select branch from the list!');
                    $('#cmbidbranch').focus();
                    return false;
                }
                if($('#cmbiddept').val() == 0)
                {
                    alert('Select department from the list!');
                    $('#cmbiddept').focus();
                    return false;
                }
                if(jQuery.trim($('#txtidusername').val()) == '')
                {
                    alert('Empty User fullname!');
                    $('#txtidusername').focus();
                    return false;
                }
                if(jQuery.trim($('#txtidlogin').val())=='')
                {
                    alert('Empty Login ID!');
                    $('#txtidlogin').focus();
                    return false;
                }
                if($('#txtidpassword1').val()=='')
                {
                    alert('Empty passwords!');
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
        <form name="frmcreateaccount" id="frmcaid" action="<?php echo $_SERVER['PHP_SELF'].'?/'.$method[0]; ?>" method="POST" onSubmit="return validateme()">
        <div class="" id="divcontent" style="background-color: #fff; position: absolute; top: 33%; left: 33%; height: 333px; width: 399px; padding: 5px;">
        </div>
        <div class="" style="position: absolute; top: 33%; left: 33%; height: 300px; width: 400px; padding: 5px;">
            <table width="100%" cellpadding="0" cellspacing="1" style="background-color: #fff;">            
                <tr>
                    <td colspan="100%" style="padding-top: 2px; padding-left: 1px; padding-right: 1px;">
                        <p class="ui-state-default ui-corner-all ui-helper-clearfix" style="padding:4px;">
                        <span class="ui-icon ui-icon-person" style="float:left; margin:-2px 5px 0 0;"></span>
                            <?php echo (!$edit) ? 'CREATE USER ACCOUNT': 'EDIT ACCOUNT'; ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="100%">
                        <div style="background-color: #fff; padding: 20px; height: 293px; overflow: auto;">
                            <table width="100%">
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
                                ?>
                                <tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">Branch</td>
                                    <td width="60%">
                                        <select name="cmbbranch" id="cmbidbranch" tabindex="1" class="inputtext">
                                            <option value="0">- Select Branch -</option>
                                        <?php
                                            $qry    = "select * from ref_branch";
                                            $rs     = mssql_query($qry);
                                            
                                            while($rsA1 = mssql_fetch_object($rs))
                                            {
                                                echo "<option value='{$rsA1->branch_code}'>{$rsA1->branch_name}</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                                </tr>
								 <tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">Division</td>
                                    <td width="60%">
                                        <select name="cmbdivision" id="cmbiddiv" tabindex="2" class="inputtext">
                                            <option value="0">- Select Division -</option>
                                            <?php
                                                $qry    = "select * from ref_division ";
                                                $rs     = mssql_query($qry);
                                                
                                                while($rsA = mssql_fetch_object($rs)){
                                                    echo "<option value='".$rsA->dept_id."'>".$rsA->dept_name."</option>";
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">Department</td>
                                    <td width="60%">
                                        <select name="cmbdept" id="cmbiddept" tabindex="2" class="inputtext">
                                            <option value="0">- Select Department -</option>
                                            <?php
                                                $qry    = "select * from ref_department";
                                                $rs     = mssql_query($qry);
                                                
                                                while($rsA = mssql_fetch_object($rs)){
                                                    echo "<option value='".$rsA->dept_id."'>".$rsA->dept_name."</option>";
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
								<tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">User Type</td>
                                    <td width="60%">
                                        <select name="cmbtype" id="cmbidtype" tabindex="3" class="inputtext">
                                            <option value="0">- Select User Type -</option>
                                            <?php
												$qry    = "select * from ref_accessright";
                                                $rs     = mssql_query($qry);
                                                
                                                while($rsA = mssql_fetch_object($rs)){
                                                    echo "<option value='".$rsA->accessright."'>".$rsA->usertype."</option>";
                                                }
                                            ?>
										</select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">User Fullname</td>
                                    <td width="60%"><input type="text" name="txtusername" id="txtidusername" tabindex="4" class="inputtext" /></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">Login ID</td>
                                    <td width="60%"><input type="text" name="txtlogin" id="txtidlogin" tabindex="5" class="inputtext" /></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">Password</td>
                                    <td width="60%"><input type="password" name="txtpassword1" id="txtidpassword1" tabindex="6" class="inputtext" /></td>
                                </tr>
                                <tr>
                                    <td width="40%" class="label" style="vertical-align: middle;">Confirm Password</td>
                                    <td width="60%"><input type="password" name="txtpassword2" id="txtidpassword2" tabindex="7" class="inputtext" /></td>
                                </tr>
                                <tr>
                                    <td colspan="100%">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="100%" align="center">
                                        <button type="button" name="cmdreturn" id="cmdidreturn" title="Sign Up" tabindex="8" >Return</button>
                                        <input type="submit" name="cmdsignup" id="cmdidsignup" title="Sign Up" style="height: 28px;" value="Sign Up" tabindex="7" />
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