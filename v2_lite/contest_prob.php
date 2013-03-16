<?php
  include_once('conn.php');
  $cpid = convert_str($_GET['cpid']);
  if ($cpid==0&&isset($_GET['cid'])) {
      $cid=convert_str($_GET['cid']);
      $query = "select contest_problem.cpid from contest_problem where contest_problem.cid='$cid' order by contest_problem.lable asc limit 0,1";
      list($cpid) = mysql_fetch_array(mysql_query($query));
  }

  $query = "select contest_problem.cid,contest_problem.pid,contest_problem.lable,contest.isprivate from contest_problem,contest where contest_problem.cpid='$cpid' and contest.cid=contest_problem.cid";
  $result = mysql_query($query);
  $row=@mysql_fetch_row($result);
  $cid=$row[0];
  $pid=$row[1];
  $label=$row[2];
  $isvirtual=$row[3];

  if (mysql_num_rows($result)==0||!db_contest_started($cid)||($isvirtual==true&&(!db_user_in_contest($cid,$nowuser)||!db_user_match($nowuser, $nowpass)))) {
?>
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <p>
            <div class="error"><b>Problem Unavailable! Or it's a private contest!</b></div>
          </p>
        </div>
        <div id="one_content_base"></div>
      </div>

<?php
  }else {
    echo "      <div id=\"sidebar_container\">";
    include("contest_sidebar.php");
    echo "      </div>";
    $query="select title,description,input,output,sample_in,sample_out,hint,source,time_limit,case_time_limit,memory_limit,special_judge_status,hide,vname,vid,ignore_noc from problem where pid='$pid'";
    $result = mysql_query($query);
    list($ptitle,$desc,$inp,$oup,$sin,$sout,$hint,$source,$tl,$ctl,$ml,$spj,$hide,$vname,$vid,$ignoc)=@mysql_fetch_row($result);

?>
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <!-- insert the page content here -->
            Current Server Time: <span id="servertime"><?php echo date("Y-m-d H:i:s"); ?></span>
<?php
    if (mysql_num_rows($result)!=1) {
?>
          <p>
            <div class="error"><b>Problem Unavailable!</b></div>
          </p>
<?php
    } else {
?>
          <div id="showproblem">
            <div class="center" id="probpagi">
<?php
      $cha = "SELECT lable,cpid FROM contest_problem WHERE cid = '$cid' order by lable asc";
      $que = mysql_query($cha);
      while (  $go = mysql_fetch_array($que) ) {
?>
              <a href="javascript:void(0)" name="<?php echo $go['cpid'] ?>" class="button"><?php echo $go['lable'] ?></a>
<?php
      }
?>
            </div>

            <h1 class="center pagetitle"><?php echo $label.". ".$ptitle; ?></h1>
            <div id="conditions">
              <?php if($ignoc=="0") { ?>
              <div style="float:left; width:33%"><label>Time Limit: </label><?php echo $tl; ?>ms</div>
              <div style="float:left; width:33%"><label>Case Time Limit: </label><?php echo $ctl; ?>ms</div>
              <div style="float:left; width:33%"><label>Memory Limit: </label><?php echo $ml; ?>KB</div>
              <?php } else { ?>
              <div style="float:left; width:50%"><label>Case Time Limit: </label><?php echo $ctl; ?>ms</div>
              <div style="float:left; width:50%"><label>Memory Limit: </label><?php echo $ml; ?>KB</div>
              <?php } ?>
<?php
      if ($spj) {
?>
              <div id="spjinfo">Special Judge</div>
<?php
      }
?>
              <div style="clear:both"></div>
            </div>
            <div class="functions center">
<?php
      if (db_contest_type($cid)!=99) {
?>
              <a href="#" class="submitprob button">Submit</a>
              <a href="#" name="<?php echo $label?>" class="showstatus button">Status</a>
<?php
      }
?>
<?php
      if (db_contest_passed($cid)) {
?>
              <a href="problem_show.php?pid=<?php echo $pid; ?>" class="goprob button">PID: <?php echo $pid; ?></a>
<?php
      }
?>
<?php
      if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
              <a href="admin_index.php?pid=<?php echo $pid;?>#problemtab" class="button">Edit</a>
<?php
      }
?>
            </div>
<?php
      if ($desc!="") {
?>
            <h2> Description </h2>
            <div class="content-wrapper ui-corner-all">
<?php
        echo latex_content($desc)."\n";
?>
            </div>
<?php
      }
?>
<?php
      if ($inp!="") {
?>
            <h2> Input </h2>
            <div class="content-wrapper ui-corner-all">
<?php
        echo latex_content($inp)."\n";
?>
            </div>
<?php
      }
?>
<?php
      if ($oup!="") {
?>
            <h2> Output </h2>
            <div class="content-wrapper ui-corner-all">
<?php
        echo latex_content($oup)."\n";
?>
            </div>
<?php
      }
?>
<?php
      if ($sin!="") {
?>
            <h2> Sample Input </h2>
            <div class="content-wrapper ui-corner-all">
<?php
    if (stristr($sin,'<br')==null&&stristr($sin,'<pre')==null) {
?>
              <pre>
<?php
    }
?>
<?php
      echo $sin;
?>
<?php
    if (stristr($sin,'<br')==null&&stristr($sin,'<pre')==null) {
?>
</pre>
<?php
    }
?>
            </div>
<?php
    }
?>
<?php
    if ($sout!="") {
?>
            <h2> Sample Output </h2>
            <div class="content-wrapper ui-corner-all">
<?php
    if (stristr($sout,'<br')==null&&stristr($sin,'<pre')==null) {
?>
              <pre>
<?php
    }
?>
<?php            
      echo $sout;
?>
<?php
    if (stristr($sout,'<br')==null&&stristr($sin,'<pre')==null) {
?>
</pre>
<?php
    }
?>
            </div>
<?php
      }
?>
<?php
      if (trim(strip_tags($hint))!=""||strlen($hint)>50) {
?>
            <h2> Hint </h2>
            <div class="content-wrapper ui-corner-all">
<?php
        echo latex_content($hint)."\n";
?>
            </div>
<?php
      }
?>
            <div class="functions center" style="margin-bottom:0">
<?php
      if (db_contest_type($cid)!=99) {
?>
              <a href="#" class="submitprob button">Submit</a>
              <a href="#" name="<?php echo $label?>" class="showstatus button">Status</a>
<?php
      }
?>
<?php
      if (db_contest_passed($cid)) {
?>
              <a href="problem_show.php?pid=<?php echo $pid; ?>" class="goprob button">PID: <?php echo $pid; ?></a>
<?php
      }
?>
<?php
      if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
              <a href="admin_index.php?pid=<?php echo $pid;?>#problemtab" class="button">Edit</a>
<?php
      }
?>
            </div>
          </div>
<?php
    }
?>
        </div>
        <div id="content_base"></div>
      </div>
<?php
  }
?>

    <div id="submitdialog" class="topdialog" title="Submit <?php echo $label ?>: " name="<?php echo $vname ?>" style="display:none">
      <form action="#" method="post" id="cprobsubmit">
        <table width="100%">
          <tr>
            <th style="width:120px">Username: </th>
            <td style="text-align:left;"><?php echo $nowuser ?><input name="user_id" value="<?php echo $nowuser ?>" readonly="readonly" style="display:none"></td>
          </tr>
          <tr style="display:none">
            <th>Problem: </th>
            <td><?php echo $label ?><input name="lable" value="<?php echo $label ?>" readonly="readonly" style="display:none"></td>
          </tr>
          <tr style="display:none">
            <th>Contest: </th>
            <td><?php echo $cid ?><input name="contest_id" value="<?php echo $cid ?>" readonly="readonly" style="display:none"></td>
          </tr>
          <tr>
            <th>Language: </th>
            <td style="text-align:left;">
              <select name="language" id="lang" accesskey="l">
                <option value="1" selected='selected'>GNU C++</option>
                <option value="2">GNU C</option>
                <option value="3">Oracle Java</option>
                <option value="4">Free Pascal</option>
              </select>
            </td>
          </tr>
          <tr>
            <th>Share Code?</th>
            <td style="text-align:left;">
              <input name="isshare" type="radio" style="width:16px" value="1" />Yes &nbsp;&nbsp;&nbsp;&nbsp; <input name="isshare" value="0" type="radio" style="width:16px" />No
            </td>
          </tr>
          <tr>
            <th colspan="2" style="width:120px">Source Code: </th>
          </tr>
          <tr>
            <td colspan="2"><textarea rows="16" name="source" style="width:450px" accesskey="c" onKeyUp="if(this.value.length > 327680) this.value=this.value.substr(0,327680)"></textarea></td>
          </tr>
        </table>
        <div class="center">
          <input name='submit' type='submit' value='Submit' accesskey="s" />
          <span id="submitmsgbox" style="display:none; z-index:600;"></span>
          <input name='reset' type='reset' value='Reset' accesskey="r" />
        </div>
      </form>
    </div>

