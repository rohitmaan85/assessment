<?php 
date_default_timezone_set("Asia/Kolkata");
class DbConn {

    private static $instance;
    private $dbConn;
	private $configObj;
    private function __construct() {}

    /**
     *
     * @return DbConn
     */
    private static function getInstance(){
        if (self::$instance == null){
            $className = __CLASS__;
            self::$instance = new $className;
        }

        return self::$instance;
    }

    /**
     *
     * @return DbConn
     */
    private static function initConnection(){
        $db = self::getInstance();
        $connConf = parse_ini_file('config.ini.php');;
        $db->dbConn = new mysqli($connConf['db_host'],$connConf['db_login'],$connConf['db_pass'],$connConf['db_name']);
        $db->dbConn->set_charset('utf8');
        return $db;
    }

    /**
     * @return mysqli
     */
    public static function getDbConn() {
        try {
            $db = self::initConnection();
            return $db->dbConn;
        } catch (Exception $ex) {
        	log_event( LOG_DATABASE, "ERROR #" .$ex );
            //echo "I was unable to open a connection to the database. " . $ex->getMessage();
            return null;
        }
    }
}
?>
