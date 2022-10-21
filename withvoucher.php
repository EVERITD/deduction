<?php

class WithVoucher {

	public function __construct(){
		$db= new config();
	}
	
	public function verifycv($subsite,$dmno){
		$sqlqry = "select APTNO from accountspayable where sitecode = '{$subsite}' and APTNO = '{$dmno}' ";
		$rsqry = mssql_query($sqlqry);
		$nmrow = mssql_num_rows($rsqry);
		if ($nmrow > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	public function checklbr($param1)
	{
		$sql_qry = "select isnull(lbr_number,'') as lbr_number from DM_DEDUCTION.DBO.deduction_master where dm_no = '{$param1}'";
		$rs_qry = mssql_query($sql_qry);
		$nm_row = mssql_num_rows($rs_qry);

		if ($nm_row > 0)
		{
			while ($row_qry = mssql_fetch_object($rs_qry))
			{
				$data[] = $row_qry;
			}
			mssql_free_result($rs_qry);
			return $data;
		} else {
			return false;
		}

	}

}

define("host","192.168.16.63");
define("user","sa");
define("pass","donterase");
define("db","ARMS");

class config{
		public function __construct(){
			$con=mssql_connect(host,user,pass) or die("Cannot connect to database server!");
			mssql_select_db(db,$con);
		}
}