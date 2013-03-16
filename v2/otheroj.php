<?php
  $pagetitle="Recent Contests";
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
          <h1>Recent Contests</h1>
          <h4>Collected from <a href="http://acmicpc.info/archives/224" target="_blank">acmicpc.info</a></h4>
          <h6>JSON mirror: <a href="external/contests.json">http://www.bnuoj.com/bnuoj/external/contests.json</a> (updated every 10 minutes)</h6>
          <table class="display" id="otheroj">
            <thead>
              <tr>
                <th width="100px">OJ</th>
                <th>Title</th>
                <th width="150px">Start Time</th>
                <th width="70px">DOW</th>
                <th width="80px">Type</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="5"><img src="style/ajax-loader.gif" />Loading...</td></tr>
            </tbody>
          </table>
        </div>
        <div id="content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<script type="text/javascript">
$("#otheroj").dataTable({
    "bJQueryUI": true,
    "bProcessing": true,
    "sAjaxSource": "otheroj_data.php",
    "sDom": '<"H"p>rt<"F"i>',
    "sPaginationType": "full_numbers" ,
    "oLanguage": {
        "sEmptyTable": "No contests found.",
        "sZeroRecords": "No contests found.",
        "sInfoEmpty": "No entries to show"
    },
    "aaSorting": [ [2,'asc'] ],
    "iDisplayLength":25,
    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        $(nRow).children().each(function(){$(this).addClass('gradeC');});
        return nRow;
    },
    "fnDrawCallback": function(){
        $('#otheroj .sorting_1').each(function(){$(this).removeClass('gradeC').addClass('gradeCC');});
    }
});
$("#more").addClass("tab_selected");
</script>
<?php
    include("end.php");
?>
