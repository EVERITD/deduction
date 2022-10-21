<?php
session_start();
$x = strlen($_SESSION['user']);
if ($x > 0 ) {
  include('sqlconn_local.php');
	$lcuser  = $_SESSION['user'] ;
	$xbranch = $_SESSION['branch_code'] ;
	$glbranchcode = $_SESSION['branch_code'] ;
	$lcusername = $_SESSION['username'] ;
	$lcdeptcode = $_SESSION['dept_code'] ;
	$lcdivision = $_SESSION['divcode'];
	$lcaccrights = $_SESSION['type'];
	date_default_timezone_set('Asia/Manila');

	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
	define('FCPATH', str_replace(SELF, '', __FILE__));
	define('EXT', '.php');
}
else
{
  header('Location: login.php');
}

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

	$enc =& load_class('Ever_Encrypt');

  include('sqlconnx.php');

  if (strlen($_GET['vendor'])>0) {
    $vendor = trim($_GET['vendor']);
  } else {
    if (strlen($_POST['vendor'])>0) {
      $vendor = trim($_POST['vendor']);
    } else {
      //if (strlen($vendor)==0) {
        $vendor = "";
      //}
    }
  }


  $lQry = " select * from everlyl_conspo.consolidatepo.dbo.sitegroup order by main_site ";
  $lRs  = mssql_query($lQry);
  $mainSitesList   = array();
  $uniqueMainSites = array();
  $subSitesList    = array();

  while($cpRs = mssql_fetch_object($lRs))
  {
    $mainSitesList[trim($cpRs->sub_site)] = array(
      'code' => trim($cpRs->main_site),
      'name' => trim($cpRs->main_name),
    );
    $subSitesList[trim($cpRs->main_site)][] = trim($cpRs->sub_site);
    $uniqueMainSites[] = trim($cpRs->main_site);
  }

  ob_start();
  include 'dm_new_save.php';
  $buffer = ob_get_contents();
  ob_get_clean();

  echo $buffer;

  die();
