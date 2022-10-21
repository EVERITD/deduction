<?php

    /** Deduction uploader
     *  August 22, 2011
     *  EVER-ITD
     */

    session_start();
    if (isset($_SESSION['user'])):
        $lcuser  = $_SESSION['user'] ;
        $lcusername = $_SESSION['username'] ;
        $branch =  $_SESSION['branch_code'] ;
        $xbranch =  $_SESSION['branch_code'] ;
        $glbranchname = $_SESSION['branch_name'];
        $dept_code = $_SESSION['dept_code'] ;
        $lcdeptname = $_SESSION['dept_name'];
        $division_code = $_SESSION['divcode'];
        $lcdivname = $_SESSION['divname'];
        $lcaccrights = $_SESSION['type'];
        $height = $xbranch == 'S399' ? 460 : 460;
        date_default_timezone_set('Asia/Manila');
    else:
        die('Could not connect. Please re-log-in!');
    endif;

    include '../sqlconn.php';
?>
<!DOCTYPE HTML>
<!--
/*
 * jQuery File Upload Plugin HTML Example 5.0.6
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://creativecommons.org/licenses/MIT/
 */
-->
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<title>Deduction-Uploader</title>
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="../css/ui-lightness/jquery-ui.css" id="theme">
<link rel="stylesheet" href="../css/jquery.fileupload-ui.css">
<link rel="stylesheet" href="../css/gh-buttons.css">
<script type='text/css'>
    h1 { color: #000 !important;}
</script>
</head>
<body bgcolor="#5D7AAD">
<div style='height: 50px; color: #f2f2f2;'>
    <span style='font-size: 20px;'>Deductions { Uploader }</span>
    <span class='button-group' style='float: right;'>
		<a class='button icon arrowleft' href="">Refresh</a>
        <a class='button icon arrowleft' href="/deduction/deductionmain.php">Back</a>
        <a class='button icon fork' href="/deduction/uploader/s301_deductions_201107.xls">Download Template</a>
        <a class='button icon user' href="#">Howdy! <?php echo $lcusername; ?> <em>Log-Out</em></a>
    </span>
</div>
<div class='container-fluid'>
    <div id="fileupload">
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="fileupload-buttonbar">
                <label class="fileinput-button">
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple />
                </label>
                <button type="submit" class="start">Start upload</button>
                <button type="reset" class="cancel">Cancel upload</button>
                <button type="button" class="delete">Delete files</button>
                <span class='help-inline'>Remarks: </span><span><input type='text' name='txtremarks' placeholder='Enter remarks before you upload files' style='width: 300px; height: 8px;'></span>
            </div>
        </form>
        <div class="fileupload-content">
            <table class="files"></table>
            <div class="fileupload-progressbar"></div>
        </div>
        <div class='clear' style='height: 10px;'>&nbsp;</div>
        <div class="fileupload-buttonbar">
            <div style='padding-top: 5px; padding-bottom: 5px;'>Uploaded Files</div>
        </div>
        <div class="fileupload-content">
            <div style='padding: 3px;'>
                <span class='filter'>Filter by Status: </span>&nbsp;&nbsp;&nbsp;
                <a href='/deduction/uploader/index.php?status=2'>&bull;&nbsp;Processing</a>
                <a href='/deduction/uploader/index.php?status=3'>&bull;&nbsp;Done</a>
                <a href='/deduction/uploader/index.php?status=4'>&bull;&nbsp;Errors</a>
                <a href='/deduction/uploader/index.php?status=5'>&bull;&nbsp;Deleted</a>
            </div>
            <table width='100%' class='zebra-striped'>
                <thead>
                    <tr>
                        <th class="blue" width='12%'>Date</th>
                        <th class="blue" width='12%'>Filename</th>
                        <th class="blue" width='18%'>Upload By</th>
                        <th class="blue" width='7%'>Status</th>
                        <th class="blue" width='16%'>Remarks</th>
                        <th class="blue" width='16%'>Logs</th>
                        <th class="blue" width='14%'>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $qry = " select * from deductions_upload where status_id in (2, 3, 4, 5) ".((isset($_GET['status'])) ? " and status_id='".$_GET['status']."'": '').' order by upload_date desc';
                    $res = mssql_query($qry);

                    while($mrec= mssql_fetch_object($res)):
                ?>
                <tr>
                    <td><?php echo date('m-d-Y h:m:A', strtotime($mrec->upload_date)); ?></td>
                    <td><?php echo $mrec->filename; ?></td>
                    <td><?php echo substr($mrec->upload_by,0,30); ?></td>
                    <td>
                        <?php
                            $_mdata = '';

                            if ($mrec->status_id === 2):
                                    $_mdata = 'Processing';
                            elseif ($mrec->status_id === 3):
                                    $_mdata = 'Done';
                            elseif ($mrec->status_id === 4):
                                    $_mdata = 'Error';
                            elseif ($mrec->status_id === 5):
                                    $_mdata = 'Deleted';
                            elseif ($mrec->status_id === 6):
                                    $_mdata = 'Archived';
                            endif;

                            echo $_mdata;
                        ?>
                    </td>
                    <td><?php echo $mrec->remarks; ?></td>
                    <td><a data-id='".$mrec->id."' id='eview' href='<?php echo dirname($_SERVER['PHP_SELF'])."/errors/".$mrec->log_file; ?>'><?php echo substr($mrec->log_file,0,15); ?></a></td>
                    <td>
                        <?php
                            if ($mrec->status_id === 3):        // done
                                echo "<a href='#' data-id='".$mrec->id."' id='aview'>View</a>";
                                echo " | ";
                                echo "<a href='#' data-id='".$mrec->id."' id='aarchive'>Archive</a>";
                            elseif ($mrec->status_id === 6):    // archive
                                echo "<a href='#' data-id='".$mrec->id."' id='aview'>View</a>";
                            endif;
                        ?>
                    </td>
                </tr>
                <?php
                    endwhile;
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="dialog-modal" title="File Contents">
        <div id='m-content'>

        </div>
    </div>
</div>
<script id="template-upload" type="text/x-jquery-tmpl">
    <tr class="template-upload{{if error}} ui-state-error{{/if}}">
        <td class="preview"></td>
        <td class="name">${name}</td>
        <td class="size">${sizef}</td>
        {{if error}}
            <td class="error" colspan="2">Error:
                {{if error === 'maxFileSize'}}File is too big
                {{else error === 'minFileSize'}}File is too small
                {{else error === 'acceptFileTypes'}}Filetype not allowed
                {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                {{else}}${error}
                {{/if}}
            </td>
        {{else}}
            <td class="progress"><div></div></td>
            <td class="start"><button>Start</button></td>
        {{/if}}
        <td class="cancel"><button>Cancel</button></td>
    </tr>
</script>
<script id="template-download" type="text/x-jquery-tmpl">
    <tr class="template-download{{if error}} ui-state-error{{/if}}">
        {{if error}}
            <td></td>
            <td class="name">${name}</td>
            <td class="size">${sizef}</td>
            <td class="error" colspan="2">Error:
                {{if error === 1}}File exceeds upload_max_filesize (php.ini directive)
                {{else error === 2}}File exceeds MAX_FILE_SIZE (HTML form directive)
                {{else error === 3}}File was only partially uploaded
                {{else error === 4}}No File was uploaded
                {{else error === 5}}Missing a temporary folder
                {{else error === 6}}Failed to write file to disk
                {{else error === 7}}File upload stopped by extension
                {{else error === 'maxFileSize'}}File is too big
                {{else error === 'minFileSize'}}File is too small
                {{else error === 'acceptFileTypes'}}Filetype not allowed
                {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                {{else error === 'uploadedBytes'}}Uploaded bytes exceed file size
                {{else error === 'emptyResult'}}Empty file upload result
				{{else error === 'file_exists'}}File upload already exists
                {{else}}${error}
                {{/if}}
            </td>
        {{else}}
            <td class="preview">
                {{if thumbnail_url}}
                    <a href="${url}" target="_blank"><img src="${thumbnail_url}"></a>
                {{/if}}
            </td>
            <td class="name">
                <a href="${url}"{{if thumbnail_url}} target="_blank"{{/if}}>${name}</a>
            </td>
            <td class="size">${sizef}</td>
            <td colspan="2"></td>
        {{/if}}
        <td class="delete">
            <button data-type="${delete_type}" data-url="${delete_url}" name='btndel' value='delete'>Delete</button>
        </td>
    </tr>
</script>
<script src="../js/jquery-1.6.2.min.js"></script>
<script src="../js/jquery-ui-1.8.16.custom.min.js"></script>
<script src="../js/jquery.tmpl.min.js"></script>
<script src="../js/jquery.iframe-transport.js"></script>
<script src="../js/jquery.fileupload.js"></script>
<script src="../js/jquery.fileupload-ui.js"></script>
<script src="../js/application.js"></script>
<script type='text/javascript'>
    $(document).ready(function(){

        $('a[id="aview"]').bind('click', function(e){
            e.preventDefault();
            $.post('file_details.php', { id: $(this).attr('data-id') }, function(data) {
                $('#m-content').html(data);
            });

            $( "#dialog-modal" ).dialog({
                height: 440,
                width: 900,
                modal: true
            });
        });

        $('a[id="eview"]').bind('click', function(e){
            e.preventDefault();
            $.post('file_details.php', { href: $(this).attr('href'), typo: 'error' }, function(data) {
                $('#m-content').html(data);
            });

            $( "#dialog-modal" ).dialog({
                height: 240,
                width: 400,
                modal: true
            });
        });

        $('a[id="aarchive"]').bind('click', function(e){
            e.preventDefault();
            alert('Sorry, not yet implemented!');
            return false;
        });

    });
</script>
</body>
</html>
