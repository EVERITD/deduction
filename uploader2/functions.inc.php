<?php

    if( ! function_exists('funcGetNewDm') )
    {
        function funcGetNewDm($branch)
        {
            $aa = trim($branch);
            include('../sqlconn.php');
            $qry = "execute get_dmno '{$aa}'";
            $rs = mssql_query($qry);
        
            
            return mssql_result($rs, 0, 'dmno');
        }
    }

    if( !function_exists('funcGetSupplierName') )
    {
        function funcGetSupplierName($vcode)
        {
            $qry = "select suppliername from arms.dbo.supplier where
                ltrim(rtrim(vendorcode))='{$vcode}' OR ltrim(rtrim(aptid))='{$vcode}'
                union
                select vendorname as suppliername from arms.dbo.supplier_new where 
                ltrim(rtrim(vendorcode))='{$vcode}' ";
            $rs = mssql_query($qry);
            return mssql_result($rs, 0, 'suppliername');
        }
    }

    if( ! function_exists('funcGetSubCat'))
    {
        function funcGetSubCat($code)
        {

            $qry = "select subcat_name as scat_name,subcat_longname as scat_lname from ref_subcategory where subcat_code = '".preg_replace('/[^A-Za-z0-9\-]/', '', trim($code))."'";
            $rs = mssql_query($qry);
            return array(
                'shortcode' => mssql_result($rs, 0, 'scat_name'),
                'longcode' => mssql_result($rs, 0, 'scat_lname'),
            );
        }
    }

    if ( ! function_exists('funcGetConsoPO'))
    {
        function funcGetConsoPO($branch,$vcode)
        {   
            include('../sqlconn_soPO.php');
            $cons_br = str_replace('S399', 'S306', $branch);
            $qry = "select a.main_site as branch 
                    from sitegroup a 
                    left join sitegroup_vendors b on a.sub_site = b.sub_site
                    where a.sub_site = '{$cons_br}' and b.vendor_code = '{$vcode}' 
                    and a.main_site <> '{$cons_br}' and is_active = 1 ";
            $rs = mssql_query($qry);
            if (mssql_num_rows($rs) == 0) 
            {
                return '';
            } else {
                return mssql_result($rs, 0, 'branch');
            }
            
        }
    }
