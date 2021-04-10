<?php
// required headers
use exceptions\SignException;
use models\DbManager;
use config\DbConfig;
use models\SignManager;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
/**
 * @param $class
 */
function autoloadFunction($class)
{
    require(preg_replace("/[\\ ]+/", "/", $class) . ".php");
}
spl_autoload_register("autoloadFunction");
/*
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('HTTP/1.0 401 Unauthorized');
    echo 'Enter authentic login data!';
    exit;
}
*/
try {
    DbManager::connect(DbConfig::$host, DbConfig::$username, DbConfig::$pass, DbConfig::$database);
} catch (PDOException $exception) {
    header('HTTP/1.0 500 Database error');
    echo 'Database error';
    exit;
}
/*
try {
    SignManager::SignIn($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
} catch (SignException $e) {
    header('HTTP/1.0 401 Unauthorized');
    echo 'Enter authentic login data!';
    exit;
}
*/
$data = json_decode(file_get_contents('php://input'));


echo $data;
