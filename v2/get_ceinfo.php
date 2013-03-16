<?php
	include("conn.php");
    $runid=convert_str($_GET["runid"]);
    if ($runid=="") {
        echo "<br />";die();
    }
	$query="select ce_info from status where runid='$runid'";
	$result = mysql_query($query);
	list($ceinfo)=mysql_fetch_row($result);
    $ceinfo=preg_replace('/\<br(\s*)?\/?\>/i', "\n",$ceinfo); 
	$ceinfo=htmlspecialchars($ceinfo);
    echo "<pre>".$ceinfo,"</pre>";
?>
