<?php
//basic databases
include_once(dirname(__FILE__)."/../config.php");
include_once(dirname(__FILE__)."/ezSQL/shared/ez_sql_core.php");
include_once(dirname(__FILE__)."/ezSQL/".$config["database"]["type"]."/ez_sql_".$config["database"]["type"].".php");

$db_class="ezSQL_".$config["database"]["type"];
$db=new $db_class($config["database"]["username"],$config["database"]["password"],$config["database"]["table"],$config["database"]["host"]);
if ($EZSQL_ERROR) die("Database error!!!");
$db->query('SET NAMES utf8');
$db->query('SET charset utf8');
if ($config["database_debug"]) $db->debug_all=true;
?>