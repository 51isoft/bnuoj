<?php
  include_once('conn.php');
  $pid = convert_str($_GET['pid']);
  if ($pid=="") $pid="0";
  $querypage="select count(*) from problem where pid<'$pid' and hide=0";
  list($ppage)=mysql_fetch_array(mysql_query($querypage));
  $ppage=intval($ppage/$problemperpage)+1;
  $query="select title,description,input,output,sample_in,sample_out,hint,source,time_limit,case_time_limit,memory_limit,total_submit,total_ac,special_judge_status,hide,vid,vname,ignore_noc,author from problem where pid='$pid'";
  $result = mysql_query($query);
  list($ptitle,$desc,$inp,$oup,$sin,$sout,$hint,$source,$tl,$ctl,$ml,$ts,$tac,$spj,$hide,$vid,$vname,$ignoc,$author)=@mysql_fetch_row($result);
  if ($ml=="0") $ml="Unknown ";
  if ($tl=="0") $tl="Unknown ";
  if ($ctl=="0") $ctl="Unknown ";
  if (mysql_num_rows($result)>0 && !$hide) $pagetitle="BNUOJ ".$pid." - ".$ptitle;
  else $pagetitle="Problem Unavailable";
  $lastlang=$_COOKIE["lastlang"];
  if ($lastlang==null) $lastlang=1;
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
            Current Server Time: <span id="servertime"><?php echo date("Y-m-d H:i:s"); ?></span>
<?php
  if (mysql_num_rows($result)!=1||($hide&&!db_user_isroot($nowuser))) {
?>
          <p>
            <div class="error"><b>Problem Unavailable!</b></div>
          </p>
<?php
  } else {
?>
          <div id="showproblem">
            <h1 class="center"><?php echo $ptitle; ?></h1>
            <div id="conditions">
              <?php if($ignoc=="0") { ?>
                  <?php if($tl==$ctl) { ?>
              <div style="float:left; width:50%"><label>Time Limit: </label><?php echo $tl; ?>ms</div>
              <div style="float:left; width:50%"><label>Memory Limit: </label><?php echo $ml; ?>KB</div>
                  <?php } else { ?>
              <div style="float:left; width:33%"><label>Time Limit: </label><?php echo $tl; ?>ms</div>
              <div style="float:left; width:33%"><label>Case Time Limit: </label><?php echo $ctl; ?>ms</div>
              <div style="float:left; width:33%"><label>Memory Limit: </label><?php echo $ml; ?>KB</div>
                  <?php } ?>
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
            <div class="extrainfo ui-state-highlight">
<?php
    if (db_problem_isvirtual($pid)) {
?>
              This problem will be judged on <?php echo $vname; ?>. Original ID: <?php
      if ($vname=="PKU")  echo "<a href='http://acm.pku.edu.cn/JudgeOnline/problem?id=$vid' target='_blank'>$vid</a>";
      if ($vname=="OpenJudge")  echo "<a href='http://poj.openjudge.cn/practice/$vid' target='_blank'>$vid</a>";
      if ($vname=="CodeForces")  {
          $ov=$vid;
          $v1=$vid[strlen($vid)-1];
          $tv=$vid;
          $tv[strlen($vid)-1]='/';
          echo "<a href='http://codeforces.com/problemset/problem/$tv$v1' target='_blank'>$ov</a>";
      }
      if ($vname=="HDU")  echo "<a href='http://acm.hdu.edu.cn/showproblem.php?pid=$vid' target='_blank'>$vid</a>";
      if ($vname=="SGU")  echo "<a href='http://acm.sgu.ru/problem.php?contest=0&problem=$vid' target='_blank'>$vid</a>";
      if ($vname=="LightOJ")  echo "<a href='http://www.lightoj.com/volume_showproblem.php?problem=$vid' target='_blank'>$vid</a>";
      if ($vname=="Ural")  echo "<a href='http://acm.timus.ru/problem.aspx?num=$vid' target='_blank'>$vid</a>";
      if ($vname=="ZJU")  echo "<a href='http://acm.zju.edu.cn/onlinejudge/showProblem.do?problemCode=$vid' target='_blank'>$vid</a>";
      if ($vname=="SPOJ")  echo "<a href='http://www.spoj.pl/problems/$vid/' target='_blank'>$vid</a>";
      if ($vname=="UESTC")  echo "<a href='http://acm.uestc.edu.cn/problem.php?pid=$vid' target='_blank'>$vid</a>";
      if ($vname=="FZU")  echo "<a href='http://acm.fzu.edu.cn/problem.php?pid=$vid' target='_blank'>$vid</a>";
      if ($vname=="NBUT")  echo "<a href='http://cdn.ac.nbutoj.com/Problem/view.xhtml?id=$vid' target='_blank'>$vid</a>";
      if ($vname=="WHU")  echo "<a href='http://acm.whu.edu.cn/land/problem/detail?problem_id=$vid' target='_blank'>$vid</a>";
      if ($vname=="SYSU")  echo "<a href='http://soj.me/$vid' target='_blank'>$vid</a>";
      if ($vname=="SCU")  echo "<a href='http://cstest.scu.edu.cn/soj/problem.action?id=$vid' target='_blank'>$vid</a>";
      if ($vname=="HUST")  echo "<a href='http://acm.hust.edu.cn/problem.php?id=$vid' target='_blank'>$vid</a>";
      if ($vname=="UVALive")  {
          if (intval($vid)>5722) $svid=intval($vid)+10;
          else $svid=$vid;
          echo "<a href='http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&page=show_problem&problem=".(intval($svid)-1999)."' target='_blank'>$vid</a>";
      }
      if ($vname=="UVA")  {
          list($url)=mysql_fetch_array(mysql_query("select url from vurl where voj='UVA' and vid='$vid'"));
          echo "<a href='$url' target='_blank'>$vid</a>";
      }
      echo "<br />\n";
    }
    $ojrow=mysql_fetch_array(mysql_query("select * from ojinfo where name='$vname'"));
    echo "64-bit integer IO format: <b style='color:black'>".htmlspecialchars($ojrow['int64io'])."</b> &nbsp;&nbsp;&nbsp;&nbsp; Java class name: <b style='color:black'>".htmlspecialchars($ojrow['javaclass'])."</b>";
?>
            </div>
<?php
    if ($hide) {
?>
        <div class="extrainfo ui-state-highlight">This problem is hidden.</div>
<?php
    }
?>
            <div class="functions center">
<?php
    if (db_problem_exist(intval($pid)-1)) {
?>
              <a href="problem_show.php?pid=<?php echo intval($pid)-1;?>">Prev</a>
<?php
    }
?>
              <a href="#" class="submitprob">Submit</a>
              <a href="status.php?showpid=<?php echo $pid;?>">Status</a>
              <a href="problem_stat.php?pid=<?php echo $pid;?>">Statistics</a>
              <a href="discuss.php?pid=<?php echo $pid;?>">Discuss</a>
<?php
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
              <a href="admin_index.php?pid=<?php echo $pid;?>#problemtab">Edit</a>
<?php
    }
?>
<?php
    if (db_problem_exist(intval($pid)+1)) {
?>
              <a href="problem_show.php?pid=<?php echo intval($pid)+1;?>">Next</a>
<?php
    }
?>
            </div>
            <div class="functions center">
                <b>Font Size:</b> <button id="font-plus">+</button> <button id="font-minus">-</button>
            </div>
<?php
    if (db_user_match($nowuser,$nowpass)) {
        if (db_user_isroot($nowuser)) $num=1;
        else $num=@mysql_num_rows(mysql_query("select runid from status where username='$nowuser' and pid='$pid' and result='Accepted' limit 0,1"));
    }
    else $num=0;
    if ($num>0) {
?>
            <div class="functions center">
              <form method="post" id="tagform">
                <input type="hidden" name="tagpid" value="<?php echo $pid; ?>" />
                Type: <select name='utags' id="utags">
<option value="0">None</option>
<?php
        function show_category($row,$depth) {
            echo "<option value='".$row['id']."'>";
            echo str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;",$depth);
            echo $row['name']."</option>\n";
            $res=mysql_query("select * from category where parent='".$row['id']."'");
            while ($row=mysql_fetch_array($res)) show_category($row,$depth+1);
        }
        
        $res=mysql_query("select * from category where parent='-1'");
        while ($row=mysql_fetch_array($res)) {
            show_category($row,0);
        }
?>
                </select>
<?php
        if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
                Weight: <input type="text" style="padding:0.48em 0 0.47em 0.45em;width:50px" name="weight" value="10" />
                Force?: <input type="checkbox" name="force" value="1" />
<?php
        }
?>
                <button type="submit">Tag it!</button>
              </form>
            </div>
<?php
    }
?>
<?php
    if ($desc!="") {
?>
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
                <div style="clear:both"></div>
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
                <div style="clear:both"></div>
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
    if (stristr($sin,'<br')==null&&stristr($sin,'<pre')==null&&stristr($sin,'<p>')==null) {
?>
              <pre>
<?php
    }
?>
<?php
      echo $sin;
?>
<?php
    if (stristr($sin,'<br')==null&&stristr($sin,'<pre')==null&&stristr($sin,'<p>')==null) {
?>
</pre>
<?php
    }
?>
                <div style="clear:both"></div>
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
    if (stristr($sout,'<br')==null&&stristr($sin,'<pre')==null&&stristr($sout,'<p>')==null) {
?>
              <pre>
<?php
    }
?>
<?php            
      echo $sout;
?>
<?php
    if (stristr($sout,'<br')==null&&stristr($sin,'<pre')==null&&stristr($sout,'<p>')==null) {
?>
</pre>
<?php
    }
?>
                <div style="clear:both"></div>
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
                <div style="clear:both"></div>
            </div>
<?php
    }
?>
<?php
    if ($source!="") {
?>
            <h2> Source </h2>
            <div class="content-wrapper ui-corner-all">
<?php
      echo "<a href='problem.php?search=".urlencode($source)."'>$source</a>\n";
?>
            </div>
<?php
    }
?>
<?php
    if ($author!="") {
?>
            <h2> Author </h2>
            <div class="content-wrapper ui-corner-all">
<?php
      echo $author;
?>
            </div>
<?php
    }
?>

<?php
    $qres=mysql_query("select name,catid,weight from category, problem_category where pid='$pid' and category.id=problem_category.catid and weight>0");
    if (mysql_num_rows($qres)>0) {
?>
            <h2 id="ptags"> Tags ( Click to see ) </h2>
            <div id="ptagdetail" class="content-wrapper ui-corner-all" style="display:none;line-height:420%">
<?php
        while ($myrow=mysql_fetch_array($qres)) {

            echo "<span class='tags ui-corner-all' style='font-size:".(doubleval($myrow[2])*0.15+90)."%'><a href='problem_category_result.php?category=".$myrow[1]."' target='_blank'>".$myrow[0]."</a></span>\n";
        }
?>
            </div>
<?php
    }
?>
            <div class="functions center" style="margin-bottom:0">
<?php
    if (db_problem_exist(intval($pid)-1)) {
?>
              <a href="problem_show.php?pid=<?php echo intval($pid)-1;?>">Prev</a>
<?php
    }
?>
              <a href="#" class="submitprob">Submit</a>
              <a href="status.php?showpid=<?php echo $pid;?>">Status</a>
              <a href="problem_stat.php?pid=<?php echo $pid;?>">Statistics</a>
              <a href="discuss.php?pid=<?php echo $pid;?>">Discuss</a>
<?php
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
              <a href="admin_index.php?pid=<?php echo $pid;?>#problemtab">Edit</a>
<?php
    }
?>
<?php
    if (db_problem_exist(intval($pid)+1)) {
?>
              <a href="problem_show.php?pid=<?php echo intval($pid)+1;?>">Next</a>
<?php
    }
?>
            </div>
          </div>
<?php
  }
?>
        </div>
        <div id="one_content_base"></div>
      </div>
    </div>

    <div id="submitdialog" class="topdialog" title="Submit <?php echo $vname." ".$vid.": ".htmlspecialchars($title); ?>" style="display:none">
      <form action="#" method="post" id="probsubmit">
        <table width="100%">
          <tr>
            <th style="width:120px">Username: </th>
            <td style="text-align:left;"><?php echo $nowuser ?><input name="user_id" value="<?php echo $nowuser; ?>" readonly="readonly" style="display:none"></td>
          </tr>
          <tr style="display:none">
            <th>PID: </th>
            <td><?php echo $pid ?><input name="problem_id" value="<?php echo $pid; ?>" readonly="readonly" style="display:none"></td>
          </tr>
          <tr>
            <th>Language: </th>
            <td style="text-align:left;">
              <select name="language" id="lang" accesskey="l">
                <option value="1" <?php if ($lastlang==1) echo "selected='selected'"; ?>>GNU C++</option>
                <option value="2" <?php if ($lastlang==2) echo "selected='selected'"; ?>>GNU C</option>
                <option value="3" <?php if ($lastlang==3) echo "selected='selected'"; ?>>Oracle Java</option>
                <option value="4">Free Pascal</option>
                <option value="5">Python</option>
                <option value="6">C# (Mono)</option>
                <option value="7">Fortran</option>
                <option value="8">Perl</option>
                <option value="9">Ruby</option>
                <option value="10">Ada</option>
                <option value="11">SML</option>
                <option value="12">Visual C++</option>
                <option value="13">Visual C</option>
                <option value="14">CLang</option>
                <option value="15">CLang++</option>
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
<?php
    include("footer.php");
?>
<script type="text/javascript" src="js/adjlist.js?<?php echo filemtime("js/adjlist.js"); ?>" ></script>
<script type="text/javascript">
var ppid='<?php echo $pid; ?>';
var pstatperpage=<?php echo $pstatuserperpage; ?>;
var currenttime = '<?php print date("l, F j, Y H:i:s",time()); ?>' //PHP method of getting server date
var pvid="<?php echo $vid ?>";
var pvname="<?php echo $vname; ?>";
</script>
<script type="text/javascript" src="pagejs/problem_show.js?<?php echo filemtime("pagejs/problem_show.js"); ?>"></script>
<script type="text/javascript" src="js/normal_probsubmit.js"></script>
<?php
    include("end.php");
?>
