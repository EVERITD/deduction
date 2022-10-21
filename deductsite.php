<?php

class DeductSite {

	public function __construct(){
		$db= new config();
	}
	
	public function conspo_vendor($subsite,$vendor){

		$site = trim($subsite) == 'S399' ? 'S306' : $subsite;
		$vend = trim($vendor);
		$sqlqry = "select * from sitegroup_vendors where sub_site = '{$site}' and vendor_code = '{$vend}' and is_active = 1 ";
		$rsqry = mssql_query($sqlqry);
		$nmrow = mssql_num_rows($rsqry);
		if ($nmrow > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

define("host","EVERAPPSVR");
define("user","sa");
define("pass","Masterkey2");
define("db","consolidatePO");

class config{
		public function __construct(){
			$con=mssql_connect(host,user,pass) or die("Cannot connect to database server!");
			mssql_select_db(db,$con);
		}
}