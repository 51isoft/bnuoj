<?php
  include("header.php");
  include("menu.php");
?>
    <div id="site_content">
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <h1>Latest News</h1>
            <h4>New Website Launched</h4>
            <p>We've redesigned our website. Take a look around and let us know what you think.</p>
          </div>
          <div class="sidebar_base"></div>
        </div>
      </div> 
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <!-- insert the page content here -->
          <table class="display" id="rank">
            <thead>
              <tr>
                <th width='10%'> Rank </th>
                <th width='20%'> Username </th>
                <th width='50%'> Nickname </th>
                <th width='10%'> AC </th>
                <th width='10%'> All </th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
        <div id="content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<style type="text/css" title="currentStyle">
@import "media/css/demo_table_jui.css";
@import "media/css/demo_page.css";
.datatablerowhighlight {
background-color: #ECFFB3 !important;
}
</style>
<script type="text/javascript">
function striptags(a) {
    return a.replace(/(<([^>]+)>)/ig,"");
}
    var oTable = $('#rank').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bJQueryUI": true,
        "sAjaxSource": "ranklist_data.php",
        "sDom": '<"H"pf>rt<"F"ilp>',
        "sPaginationType": "input" ,
        "aoColumnDefs": [ 
            { "bSortable": false, "aTargets": [ 0,1,2,3,4 ] }
        ],
        "aaSorting": [ [0,'desc'] ],
        "iDisplayLength": 50,
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (striptags(aData[1])==$.cookie("username")) $(nRow).children().each(function(){$(this).addClass('gradeA gradeCC');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#rank td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#rank td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
            $('#rank .sorting_1').each(function(){$(this).removeClass('gradeC');$(this).toggleClass('gradeCC');});
            $('marquee').marquee();
        },
        "iDisplayStart": 0,
        "oSearch": {"sSearch": ""}
    } );
</script>
<script type="text/javascript">
$("#ranklist").addClass("tab_selected");
</script>

<?php
    include("end.php");
?>
