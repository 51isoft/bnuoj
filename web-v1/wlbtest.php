<?php
include("../conn.php");
//$sql="select count(*) from status where username like \"lonely\" and result='Accepted'";
$sql="select * from status where pid=1052 and result='Accepted'";
$que=mysql_query($sql);
while ($row=mysql_fetch_array($que)) {
    if ($row[9]==1) $ext=".cpp";
    else if ($row[9]==2) $ext=".c";
    else if ($row[9]==3) $ext=".java";
    else if ($row[9]==4) $ext=".pas";
    $fp=fopen($row[0].$ext,"w");
    fwrite($fp,$row[8]);
    fclose($fp);
}
?>
