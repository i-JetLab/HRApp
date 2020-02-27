<?php

class DB {

    private static $objInstance;

    /*
     * Class Constructor - Create a new database connection if one doesn't exist
     * Set to private so no-one can create a new instance via ' = new DB();'
     */
    private function __construct() {}

    /*
     * Like the constructor, we make __clone private so nobody can clone the instance
     */
    private function __clone() {}

    /*
     * Returns DB instance or create initial connection
     * @param
     * @return $objInstance;
     */
    public static function getInstance() {
        $connectstr_dbhost = '';
        $connectstr_dbname = '';
        $connectstr_dbusername = '';
        $connectstr_dbpassword = '';
        
        $value = getenv('SQLAZURECONNSTR_HRDBConnString');
        
        $connectstr_dbhost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value); 
        $connectstr_dbname = preg_replace("/^.*Initial Catalog=(.+?);.*$/", "\\1", $value);
        $connectstr_dbusername = preg_replace("/^.*User ID=(.+?);.*$/", "\\1", $value);
        $connectstr_dbpassword = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
   
        //define('DB_NAME', $connectstr_dbname);
        define('DB_USER', $connectstr_dbusername);
        define('DB_PASS', $connectstr_dbpassword);
        //define('DB_HOST', $connectstr_dbhost);

        if(!self::$objInstance){
            self::$objInstance = new PDO("sqlsrv:server = {$connectstr_dbhost}; Database = {$connectstr_dbname}", DB_USER, DB_PASS);
            self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$objInstance;

    } # end method

    /*
     * Passes on any static calls to this class onto the singleton PDO instance
     * @param $chrMethod, $arrArguments
     * @return $mix
     */
    final public static function __callStatic( $chrMethod, $arrArguments ) {

        $objInstance = self::getInstance();

        return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);

    } # end method

}

?>
