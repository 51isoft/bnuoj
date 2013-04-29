<?php
  include_once("header.php");
  include_once("menu.php");
  $cid=convert_str($_GET['cid']);
  $availchar="abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789";
  $length=strlen($availchar)-1;
  $pass="01234567";
  srand(time());
?>
    <div id="site_content">
<?php
  if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
      echo "<table><tr><th>Username</th><th>Nickname</th><th>Password</th></tr>";
      $handle=fopen("user.txt","r");
      if ($handle) {
          $i=0;
          while (($buffer = fgets($handle, 4096)) !== false) {
//              $buffer=trim(iconv("gbk","utf-8//translit",$buffer));
              $i++;
              if ($i<10) $num="0".$i;
              else $num=$i;
              $user="team".$num;
              for ($j=0;$j<8;$j++) $pass[$j]=$availchar[rand(0,$length)];
              $row[0]=$user;
              $row[1]=$pass;
              $row[2]=$buffer;
              echo "<tr><td>$user</td><td>$buffer</td><td>$pass</td>";
              if (!db_user_exist($user)) db_user_insert($row);
              //echo $buffer."-\n";
          }
          if (!feof($handle)) {
              echo "Error: unexpected fgets() fail\n";
          }
          fclose($handle);  
      }
      echo "</table>";
  }
  else {
?>
          <p>
            <div class="error"><b>Permission Denied!</b></div>
          </p>
<?php
  }
?>
    </div>

<?php
    include("footer.php");
?>
<?php
    include("end.php");
?>
