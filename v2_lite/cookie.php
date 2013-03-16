<?php
    if (!isset($_COOKIE["username"])||!isset($_COOKIE["password"])) {
        $nowuser="";
        $nowpass="";
    }
    else if ($_COOKIE["username"]==""||$_COOKIE["password"]=="") {
        $nowuser="";
        $nowpass="";
    }
    else {
        $nowuser=$_COOKIE["username"];
        $nowpass=$_COOKIE["password"];
    }
?>
