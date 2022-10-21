<?php
    /**
     * @category Helper
     * @author everitd
     * @link http://everloyalty.ever.ph/eportal/deduction
     * @version 1.0.0
     * @copyright EVER-ITD 2012
     */

    if( ! function_exists('funcGenerateDsNumber'))
    {
        /**
         * function funcGenerateDsNumber
         * this function will generate ds number based on
         * required parameters
         * @access public
         * @param mixed    $branch_code
         * @param integer  $branch_prefix
         * @param string   $prefix (default: S)
         * @param int      $zeros (default: 4)
         * @return mixed $dsnumber
         */
        function funcGenerateDsNumber($branch_code, $branch_prefix, $prefix = 'S', $zeros = 4)
        {
            $noOfzeros = funcGetZeros($zeros);
            $qry       = "select max(isnull(ds_no,0))+1 as 'maxid' from dm_autoid where branch_code='".$branch_code."'";
            $resultset = mssql_query($qry);
            $maxId     = mssql_result($resultset, 0, 'maxid');
            $fmaxId    = $prefix.trim($branch_prefix).$noOfzeros[($zeros+1)-strlen($maxId)].$maxId;

            $qry = "update dm_autoid set ds_no='".$maxId."' where branch_code='".$branch_code."'";
            $rs1 = mssql_query($qry);

            return $fmaxId;

        }

        /**
         * function funcGetZeros
         * this function will generate list of zeros
         * required parameters
         * @access public
         * @param int      $n
         * @return array   $arr
         */
        if ( ! function_exists('funcGetZeros'))
        {
            function funcGetZeros($n)
            {
                $arr   = array();
                $zeros = array('', '0', '00', '000', '0000', '00000', '000000');
                for($i=0,$j=$n; $i<=$j; $i++)
                {
                    $arr[$i] = $zeros[$i];
                }
                return $arr;
            }
        }
    }
