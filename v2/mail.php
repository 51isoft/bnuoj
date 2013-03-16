<?php
  include_once("conn.php");
  if ($nowuser!="") $pagetitle="Mailbox of ".$nowuser;
  else $pagetitle="Mailbox Unavailable";
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
<?php
  if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)){
?>
          <div class="error"><b>Please Login!!</b></div>
<?php
  } else {
?>
          <h1>Mailbox of <?php echo $nowuser; ?> </h1>
          <div>
            <button id="showinbox" style="display:none">Show Inbox</button>
            <button id="showoutbox">Show Outbox</button>
            <button id="sendmail">New Mail</button>
          </div>
          <table class="display" style="margin-bottom:0" id="maillist">
            <thead>
              <tr>
                <th>Mail ID</th>
                <th width="120px">Sender</th>
                <th width="120px">Reciever</th>
                <th>Title</th>
                <th width="160px">Time</th>
              </tr>
            </thead>
            <tfoot></tfoot>
            <tbody></tbody>
          </table>
<?php
  }
?>
        </div>
        <div id="content_base"></div>
      </div>
    </div>
    <div class="topdialog" id="mailwindow"></div>
    <div class="topdialog" id="newmailwindow" title="New Mail" style="display:none">
      <form action="#" method="post" id="mailsend">
        <table width="100%">
          <tr>
            <th style="width:120px">Reciever: </th>
            <td style="text-align:left;"><input name="reciever" id="reciever" value="" /></td>
          </tr>
          <tr>
            <th style="width:120px">Title: </th>
            <td style="text-align:left;"><input name="title" id="mailtitle" value="" style="width:300px" /></td>
          </tr>
          <tr>
            <th colspan="2" style="width:120px">Content: </th>
          </tr>
          <tr>
            <td colspan="2"><textarea rows="16" name="content" id="newmailcontent" style="width:450px" accesskey="c" onKeyUp="if(this.value.length > 32768) this.value=this.value.substr(0,32768)"></textarea></td>
          </tr>
        </table>
        <div class="center">
          <input name='submit' type='submit' value='Send' accesskey="s" />
          <span id="sendmailmsgbox" style="display:none; z-index:600;"></span>
          <input name='reset' type='reset' value='Reset' accesskey="r" />
        </div>
      </form>
    </div>
<?php
    include("footer.php");
?>
<script type="text/javascript">
var mailperpage=<?php echo $mailperpage ?>;
</script>
<script type="text/javascript" src="pagejs/mail.js?<?php echo filemtime("pagejs/mail.js"); ?>"></script>
<script type="text/javascript" src="js/sendmail.js"></script>
<?php
    include("end.php");
?>
