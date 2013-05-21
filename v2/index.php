<?php
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
<?php
  include("index_sidebar.php");
?>
      </div>
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <!-- insert the page content here -->
          <p>
            Current Server Time: <span id="servertime"><?php echo date("Y-m-d H:i:s"); ?></span>
          </p>
          <b style="color:red;font-size:16px;"> Arrange YOUR OWN contest <a href="contest.php?open=1">HERE</a>! </b>

<?php
    $sql="select cid,title,end_time,has_cha,challenge_end_time from contest where start_time<now() and (end_time>now() or (has_cha=1 and challenge_end_time>now()) ) and isvirtual=0 order by start_time limit 0,5";
    $res=mysql_query($sql);
    $none=true;
    while (list($cid,$title,$edtime,$has_cha,$cedtime)=mysql_fetch_array($res)) {
        if ($none) {
?>
          <h1>Running Contests</h1>
          <p>
<?php
        }
        $none=false;
?>
             <a href='contest_show.php?cid=<?php echo $cid; ?>'><?php echo $title; ?></a> ends at <?php if ($has_cha==0) echo $edtime; else echo $cedtime ?><br />
<?php
    }
    if (!$none) {
?>
          </p>
<?php
    }
?>

<?php
    $sql="select cid,title,end_time from contest where start_time<now() and end_time>now() and isvirtual=1 order by start_time desc limit 0,10";
    $res=mysql_query($sql);
    $none=true;
    while (list($cid,$title,$edtime)=mysql_fetch_array($res)) {
        if ($none) {
?>
          <h1>Running Virtual Contests</h1>
          <p>
<?php
        }
        $none=false;
?>
             <a href='contest_show.php?cid=<?php echo $cid; ?>'><?php echo $title; ?></a> ends at <?php echo $edtime; ?><br />
<?php
    }
    if (!$none) {
?>
          </p>
<?php
    }
?>

<?php
    $sql="select cid,title,start_time,type from contest where start_time>now() and isvirtual=0 order by start_time limit 0,5";
    $res=mysql_query($sql);
    $none=true;
    while (list($cid,$title,$sttime,$type)=mysql_fetch_array($res)) {
        if ($type==1) $title.=" [CF]";
        if ($none) {
?>
          <h1>Upcoming Contests</h1>
          <p>
<?php
        }
        $none=false;
?>
             <a href='contest_show.php?cid=<?php echo $cid; ?>'><?php echo $title; ?></a> at <?php echo $sttime; ?><br />
<?php
    }
    if (!$none) {
?>
          </p>
<?php
    }
?>

<?php
    $sql="select cid,title,start_time from contest where start_time>now() and isvirtual=1 order by start_time limit 0,5";
    $res=mysql_query($sql);
    $none=true;
    while (list($cid,$title,$sttime)=mysql_fetch_array($res)) {
        if ($none) {
?>
          <h1>Upcoming Virtual Contests</h1>
          <p>
<?php
        }
        $none=false;
?>
             <a href='contest_show.php?cid=<?php echo $cid; ?>'><?php echo $title; ?></a> at <?php echo $sttime; ?><br />
<?php
    }
    if (!$none) {
?>
          </p>
<?php
    }
?>
          <h1 style="padding:0">Greetings!</h1>
          <div class="content-wrapper ui-corner-all">
            Welcome to BNU Online Judge 2.0! <br />
            If you don't like it, <a href='../contest' target='_blank'>click here</a> for the original BNUOJ. <br />
            IE 9+, Opera 9.5+, Safari 4.0+, Firefox 8+ and Chrome 12+ are <span style='color:red;font-weight:bold'>STRONGLY RECOMMENDED!</span> <br />
            I wish all bugs are gone....<br />
            Bug report or feature requests: <a href='mailto:yichao#mail.bnu.edu.cn'>click here</a>.<br />
            Source code: <a href='http://code.google.com/p/bnuoj' target="_blank">go to Google Code</a>.
          </div>
          <h1 style="margin-top:20px">Currently Supported OJ</h1>
          <div class="content-wrapper ui-corner-all">
            <a href="http://poj.org" target="_blank">PKU</a>&nbsp;
            <a href="http://acm.hdu.edu.cn" target="_blank">HDU</a>&nbsp;
            <a href="http://livearchive.onlinejudge.org" target="_blank">UVALive</a>&nbsp;
            <a href="http://www.codeforces.com" target="_blank">Codeforces</a>&nbsp;
            <a href="http://acm.sgu.ru" target="_blank">SGU</a>&nbsp;
            <a href="http://www.lightoj.com" target="_blank">LightOJ</a>&nbsp;
            <a href="http://acm.timus.ru" target="_blank">Ural</a>&nbsp;
            <a href="http://acm.zju.edu.cn" target="_blank">ZJU</a>&nbsp;
            <a href="http://uva.onlinejudge.org" target="_blank">UVA</a>&nbsp;
            <a href="http://www.spoj.pl" target="_blank">SPOJ</a>&nbsp;
            <a href="http://acm.uestc.edu.cn" target="_blank">UESTC</a>&nbsp;
            <a href="http://acm.fzu.edu.cn" target="_blank">FZU</a>&nbsp;
            <a href="http://acm.nbut.cn:8081" target="_blank">NBUT</a>&nbsp;
            <a href="http://acm.whu.edu.cn/land" target="_blank">WHU</a>&nbsp;
            <a href="http://soj.me" target="_blank">SYSU</a>&nbsp;
            <a href="http://poj.openjudge.cn" target="_blank">OpenJudge</a>&nbsp;
            <a href="http://cstest.scu.edu.cn/soj/" target="_blank">SCU</a>&nbsp;
            <a href="http://acm.hust.edu.cn/" target="_blank">HUST</a>
          </div>
          <h2 style="margin-top:20px">Todo List</h2>
          <div class="content-wrapper ui-corner-all">
            <ol>
              <li>Virtual Judge on many other OJs</li>
              <li>Class/Interactive Module</li>
              <li>AI Battle Module</li>
              <li>SNS link</li>
            </ol>
            Any suggestion is welcome!
          </div>
          <h2 style="margin-top:20px">Spin-off projects</h2>
          <div class="content-wrapper ui-corner-all">
            <ol>
              <li>acmicpc.info Hackathon Platform: <a href="http://www.bnuoj.com/hackathon/" target="_blank">click here</a></li>
              <li>BNUOJ lite version (for onsite contests) </li>
            </ol>
          </div>
        </div>
        <div id="content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<script type="text/javascript">
$("#home").addClass("tab_selected");
var currenttime = '<?php print date("l, F j, Y H:i:s",time()); ?>' //PHP method of getting server date

var serverdate=new Date(currenttime);

function padlength(what){
    var output=(what.toString().length==1)? "0"+what : what;
    return output;
}

function displaytime(){
    serverdate.setSeconds(serverdate.getSeconds()+1);
    var datestring=serverdate.getFullYear()+"-"+padlength(serverdate.getMonth()+1)+"-"+padlength(serverdate.getDate());
    var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
    $("#servertime").text(datestring+" "+timestring);
}
window.onload=function(){
    setInterval("displaytime()", 1000);
}

</script>

<?php
    include("end.php");
?>
