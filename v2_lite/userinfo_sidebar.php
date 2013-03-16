<?php
    include_once("conn.php");
    list($nac)=mysql_fetch_array(mysql_query("select count(*) from status where username='$name' and result='Accepted'"));
    list($nce)=mysql_fetch_array(mysql_query("select count(*) from status where username='$name' and result='Compile Error'"));
    list($nwa)=mysql_fetch_array(mysql_query("select count(*) from status where username='$name' and result='Wrong Answer'"));
    list($npe)=mysql_fetch_array(mysql_query("select count(*) from status where username='$name' and result='Presentation Error'"));
    list($nre)=mysql_fetch_array(mysql_query("select count(*) from status where username='$name' and result='Runtime Error'"));
    list($ntle)=mysql_fetch_array(mysql_query("select count(*) from status where username='$name' and result='Time Limit Exceed'"));
    list($nmle)=mysql_fetch_array(mysql_query("select count(*) from status where username='$name' and result='Memory Limit Exceed'"));
    list($nole)=mysql_fetch_array(mysql_query("select count(*) from status where username='$name' and result='Output Limit Exceed'"));
    list($nrf)=mysql_fetch_array(mysql_query("select count(*) from status where username='$name' and result='Restricted Function'"));
    list($ntot)=mysql_fetch_array(mysql_query("select count(*) from status where username='$name'"));

    $noth=$ntot-$nac-$nce-$nwa-$npe-$nre-$ntle-$nmle-$nole-$nrf;

?>
        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <div id="userpie" class="highcharts-container" style="height:200px; width:100% margin: 0; clear:both">
            </div>
            <table id="userstat" class="pieside">
              <tbody>
                <tr>
                  <th width="70px">Total</th>
                  <td><?php echo "<a href='status.php?showname=$name'>".$ntot."</a>"; ?></td>
                </tr>
                <tr>
                  <th>AC</th>
                  <td><?php echo "<a href='status.php?showname=$name&showres=Accepted'>".$nac."</a>"; ?></td>
                </tr>
                <tr>
                  <th>CE</th>
                  <td><?php echo "<a href='status.php?showname=$name&showres=Compile+Error'>".$nce."</a>"; ?></td>
                </tr>
                <tr>
                  <th>WA</th>
                  <td><?php echo "<a href='status.php?showname=$name&showres=Wrong+Answer'>".$nwa."</a>"; ?></td>
                </tr>
                <tr>
                  <th>PE</th>
                  <td><?php echo "<a href='status.php?showname=$name&showres=Presentation+Error'>".$npe."</a>"; ?></td>
                </tr>
                <tr>
                  <th>RE</th>
                  <td><?php echo "<a href='status.php?showname=$name&showres=Runtime+Error'>".$nre."</a>"; ?></td>
                </tr>
                <tr>
                  <th>TLE</th>
                  <td><?php echo "<a href='status.php?showname=$name&showres=Time+Limit+Exceed'>".$ntle."</a>"; ?></td>
                </tr>
                <tr>
                  <th>MLE</th>
                  <td><?php echo "<a href='status.php?showname=$name&showres=Memory+Limit+Exceed'>".$nmle."</a>"; ?></td>
                </tr>
                <tr>
                  <th>OLE</th>
                  <td><?php echo "<a href='status.php?showname=$name&showres=Output+Limit+Exceed'>".$nole."</a>"; ?></td>
                </tr>
                <tr>
                  <th>RF</th>
                  <td><?php echo "<a href='status.php?showname=$name&showres=Restricted+Function'>".$nrf."</a>"; ?></td>
                </tr>
                <tr>
                  <th>Other</th>
                  <td><?php echo $noth; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="sidebar_base"></div>
        </div>
<?php
  include("common_sidebar.php");
?>

