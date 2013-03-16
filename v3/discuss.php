<?php
include_once('functions/global.php');
include_once('functions/sidebars.php');

$proid = convert_str($_GET['pid']);
$page = intval(convert_str($_GET['page']));
if ($page<1) $page=1;
$pagetitle="Discuss";
if ($proid!="") $pagetitle=$pagetitle." For Problem ".$proid;
include("header.php");
?>
        <div class="span9">
          <div id='dcontent'>
            <div class="tcenter"><img src="img/ajax-loader.gif" />Loading...</div>
          </div>
          <div class="dcontrol tcenter">
            <div class="btn-group">
              <a href='discuss.php?page=1&pid=<?=$proid?>' class="btn" id='disfirst'>First</a>
              <a href='discuss.php?page=<?=$page-1?>&pid=<?=$proid?>' class="btn" id='disprev'>Prev</a>
              <a href='#' class="btn btn-primary" id='disnew'>New Topic</a>
              <a href='discuss.php?page=<?=$page+1?>&pid=<?=$proid?>' class="btn" id='disnext'>Next</a>
            </div>
          </div>
        </div>
        <div class="span3">
<?=sidebar_common()?>
        </div>


    <div id="newtopic" class="modal hide fade">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>New Topic</h3>
      </div>
      <form id="newtopicform" action="ajax/topic_new.php?pid=<?=$proid?>" method="post" class="ajform">
        <div class="modal-body">
          <input type="text" name="title" placeholder="Topic Title" class="input-block-level" />
          <textarea name="content" placeholder="Enter your content here" rows="8" class="input-block-level"></textarea>
        </div>
        <div class="modal-footer">
          <span id="msgbox" style="display:none"></span>
          <input class="btn btn-primary" type="submit" name="name" value="Post" />
        </div>
      </form>
    </div>

    <div id="showtopic" class="modal hide fade">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="ttitle">Topic Title</h3>
      </div>
      <div class="modal-body">
        <div>
          <span id="ttime"></span> by <span id="tuser"></span> At <span id="tproblem"></span>
        </div>
        <pre id="tcontent"></pre>
        <div id="tdetail"></div>
        <form id="replybox" action="#" method="post" class="ajform">
          <input type="text" name="title" placeholder="Reply Title" class="input-block-level" />
          <textarea name="content" placeholder="Enter your reply here" rows="4" class="input-block-level"></textarea>
          <div class="pull-right">
            <span id="msgbox" style="display:none"></span>
            <input class="btn btn-primary" type="submit" name="name" value="Post" />
          </div>
        </form>
      </div>
    </div>



<script type="text/javascript">
var ppid='<?= $proid ?>';
var curr_page='<?= $page ?>';
</script>
<script type="text/javascript" src="js/discuss.js?<?=filemtime("js/discuss.js") ?>"></script>
<?php
include ("footer.php");
?>
