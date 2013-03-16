<?php
    include_once("conn.php");
    list($nac)=mysql_fetch_array(mysql_query("select count(*) from status where pid='$pid' and result='Accepted'"));
    list($nce)=mysql_fetch_array(mysql_query("select count(*) from status where pid='$pid' and result='Compile Error'"));
    list($nwa)=mysql_fetch_array(mysql_query("select count(*) from status where pid='$pid' and result='Wrong Answer'"));
    list($npe)=mysql_fetch_array(mysql_query("select count(*) from status where pid='$pid' and result='Presentation Error'"));
    list($nre)=mysql_fetch_array(mysql_query("select count(*) from status where pid='$pid' and result='Runtime Error'"));
    list($ntle)=mysql_fetch_array(mysql_query("select count(*) from status where pid='$pid' and result='Time Limit Exceed'"));
    list($nmle)=mysql_fetch_array(mysql_query("select count(*) from status where pid='$pid' and result='Memory Limit Exceed'"));
    list($nole)=mysql_fetch_array(mysql_query("select count(*) from status where pid='$pid' and result='Output Limit Exceed'"));
    list($nrf)=mysql_fetch_array(mysql_query("select count(*) from status where pid='$pid' and result='Restricted Function'"));
    list($ntot)=mysql_fetch_array(mysql_query("select count(*) from status where pid='$pid'"));
    $noth=$ntot-$nac-$nce-$nwa-$npe-$nre-$ntle-$nmle-$nole-$nrf;

?>
        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <div id="probpie" class="highcharts-container" style="height:200px; width:100% margin: 0; clear:both">
            </div>
            <table id="probstat" class="pieside">
              <tbody>
                <tr>
                  <th width="70px">Total</th>
                  <td><?php echo "<a href='status.php?showpid=$pid'>".$ntot."</a>"; ?></td>
                </tr>
                <tr>
                  <th>AC</th>
                  <td><?php echo "<a href='status.php?showpid=$pid&showres=Accepted'>".$nac."</a>"; ?></td>
                </tr>
                <tr>
                  <th>CE</th>
                  <td><?php echo "<a href='status.php?showpid=$pid&showres=Compile+Error'>".$nce."</a>"; ?></td>
                </tr>
                <tr>
                  <th>WA</th>
                  <td><?php echo "<a href='status.php?showpid=$pid&showres=Wrong+Answer'>".$nwa."</a>"; ?></td>
                </tr>
                <tr>
                  <th>PE</th>
                  <td><?php echo "<a href='status.php?showpid=$pid&showres=Presentation+Error'>".$npe."</a>"; ?></td>
                </tr>
                <tr>
                  <th>RE</th>
                  <td><?php echo "<a href='status.php?showpid=$pid&showres=Runtime+Error'>".$nre."</a>"; ?></td>
                </tr>
                <tr>
                  <th>TLE</th>
                  <td><?php echo "<a href='status.php?showpid=$pid&showres=Time+Limit+Exceed'>".$ntle."</a>"; ?></td>
                </tr>
                <tr>
                  <th>MLE</th>
                  <td><?php echo "<a href='status.php?showpid=$pid&showres=Memory+Limit+Exceed'>".$nmle."</a>"; ?></td>
                </tr>
                <tr>
                  <th>OLE</th>
                  <td><?php echo "<a href='status.php?showpid=$pid&showres=Output+Limit+Exceed'>".$nole."</a>"; ?></td>
                </tr>
                <tr>
                  <th>RF</th>
                  <td><?php echo "<a href='status.php?showpid=$pid&showres=Restricted+Function'>".$nrf."</a>"; ?></td>
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

