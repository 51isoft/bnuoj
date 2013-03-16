<?php
    if (!isset($_COOKIE["username"])||!isset($_COOKIE["password"])) {
        setcookie("username","");
        setcookie("password","");
        $nowuser="";
        $nowpass="";
    }
    else if ($_COOKIE["username"]==""||$_COOKIE["password"]=="") {
        setcookie("username","");
        setcookie("password","");
        $nowuser="";
        $nowpass="";
    }
    else {
        $nowuser=addslashes($_COOKIE["username"]);
        $nowpass=addslashes($_COOKIE["password"]);
    }
?>
