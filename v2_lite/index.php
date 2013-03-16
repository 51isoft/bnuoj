<?php
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
<?php
  include("common_sidebar.php");
?>
      </div>
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <!-- insert the page content here -->
          <p>
            Current Server Time: <span id="servertime"><?php echo date("Y-m-d H:i:s"); ?></span>
          </p>

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
          <h1 style="padding:0">Greetings!</h1>
          <div class="content-wrapper ui-corner-all">
            Contest is on the way! <br />
            IDE、浏览器等软件下载请点<a href="rj" target='_blank'>这里</a> <br />
          </div>
          <h1 style="margin-top:10px">如何避免由于编译器差别带来的错误</h1>
          <div class="content-wrapper ui-corner-all">
<ol>
<li><p>提交Java语言时注意类名为Main。</p></li>
<li><p>判题系统使用的是G++编译器，和普通使用的TC，VC都有所不同，建议大家使用Code::Blocks作为IDE，或者用TC和VC写代码，提交前使用Code::Blocks编译，预防编译错误。</p>
<p>提交C语言代码最好使用G++，G++兼容C和C++。C的代码可以用GCC也可用G++提交，而C++的代码不能够用GCC提交，只能用G++。</p></li>
<li><p>G++包含库的时候不要使用iostream.h，应该使用&lt;iostream&gt;。</p>
<p>有些常用的函数所在的库会被VC自动包含，但是不会被G++包含。</p>
<p>例如memset，strlen，strstr等和字符串处理相关的函数在库&lt;cstring&gt;中；abs在&lt;cstdlib&gt;中；fabs，sin，sqrt等数学函数在&lt;cmath&gt;中。</p>
<p>为了避免CE，大家可以索性一次性把所有可能用到的库都给包含上。</p>
<p>C++注意要使用using namespace std;</p></li>
<li><p>关于整数，在G++下，long和int是完全一样的</p></li>
<li><p>浮点数：使用double以减小误差，格式控制字符串是"%lf"(不要使用float)。浮点数的相等不能直接用==来判断，需要使用实数判等。</p></li>
<li><p>标识符，G++中有一些在VC中没有的保留字，比如and，or，not等等，使用这些保留字作为标识符会产生CE。</p></li>
<li><p>对于输入输出，建议不要使用cin和cout，这种输入输出方式会比较慢，在数据量大的时候容易引起超时。</p></li>
<li><p>关于main函数，定义一定要是int型，并记得加上return 0。</p>
<p>int main(){... return 0; }</p></li>
<li><p>当使用类似于for (int i=0;i&lt;n;i++)这种形式对循环变量进行定义时，注意循环变量的作用域只在这个循环内。</p></li>
<li><p>输入法在敲代码和提交代码的时候一定要确保关闭，代码中(除了注释部分)有全角字符会引起CE，注释建议使用英文。</p></li>
<li><p>使用STL的同学请注意例如下面的声明是会引起CE的</p>
<p>vector&lt;vector&lt;int&gt;&gt; adj; 应该改为 vector&lt;vector&lt;int&gt; &gt; adj;</p>
<p>连续两个左右箭头间要一个空格。</p></li>
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
