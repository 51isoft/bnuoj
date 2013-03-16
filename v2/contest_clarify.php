<?php
  include_once('conn.php');
  $cid=convert_str($_GET["cid"]);
  if (!db_contest_started($cid)||(db_contest_private($cid)&&(!db_user_in_contest($cid,$nowuser)||!db_user_match($nowuser, $nowpass)))) {
?>
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <p>
            <div class="error"><b>Contest not started or you're not in this contest.</b></div>
          </p>
        </div>
        <div id="one_content_base"></div>
      </div>

<?php
  }else {

?>
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
<?php
  include("contest_sidebar.php");
?>
      </div>
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <h1 class="pagetitle" style="display:none">Clarification For Contest <?php echo $cid; ?></h1>
          <!-- insert the page content here -->
            Current Server Time: <span id="servertime"><?php echo date("Y-m-d H:i:s"); ?></span>
<?php 
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) $query="select question,reply,ispublic,ccid,username from contest_clarify where cid='$cid' order by ccid desc";
    else $query="select question,reply,ispublic,ccid from contest_clarify where cid='$cid' and (username='$nowuser' or ispublic=1) order by ccid desc";
    $result=mysql_query($query);
    while ($row=@mysql_fetch_row($result)) {
?>
          <div class="content-wrapper ui-corner-all" style="margin-top:10px">
            <h4><?php if ($row[2]=='0') echo "Private Message"; else  echo "Public Message"; ?></h4>
            <pre><?php echo "Q: ".htmlspecialchars($row[0]); ?></pre>
<?php
        if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
            <pre align='right'>By <?php echo $row[4]; ?></pre>
            Answer: <br />
            <form class="clarform" method="post" action="#">
              <textarea name="answer" style="width:540px;height:150px;padding:0.7em"><?php echo htmlspecialchars($row[1]); ?></textarea><br />
              Public? <input name="<?php echo "ispublic".$row[3]; ?>" type="radio" value="1" <?php if ($row[2]=='1') echo "checked='checked'"; ?> /> Yes 
              <input name="<?php echo "ispublic".$row[3]; ?>" type="radio" value="0" <?php if ($row[2]=='0') echo "checked='checked'"; ?> /> No 
              <br />
              <input type="submit" value="Answer" />
              <input name="ccid" type="hidden" value="<?php echo $row[3]; ?>" />
            </form>
<?php
        }
        else {
?>
            <pre><?php echo "A: ".htmlspecialchars($row[1]); ?></pre>
<?php
        }
?>
	      </div>
<?php
    }
?>
          <div class="center" style="margin-bottom:0"><button id="newquestion">Post New Question</button></div>
        </div>
        <div id="content_base"></div>
      </div>
<?php
  }
?>

    <div id="questiondialog" class="topdialog" title="Post a new question" style="display:none">
      <form id='questionform' method='post' action='#'>
        <div class="center">
          <textarea name="question" style="width:400px; height:200px"></textarea>
          <input name='submit' type='submit' value='Submit' accesskey="s" />
          <span id="questionmsgbox" style="display:none; z-index:600;"></span>
        </div>
      </form>
    </div>

