<?php
include("header.php");
?>
<center>
<?php
mysql_query("update problem set vtotalnum=total_submit, vacnum=total_ac where vname='BNU'");
?>
</center>
<br>
<?php
include("footer.php");
?>

