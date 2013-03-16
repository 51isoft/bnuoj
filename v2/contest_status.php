<?php
    include_once("conn.php");
    $cid=$_GET['cid'];
?>
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
          <h1 class="pagetitle" style="display:none">Status of Contest <?php echo $cid ?></h1>
          <form id="filterform" method="">
            <table class="filter">
              <tr>
                <th>Filter:</th>
                <td><label>User:</label></td>
                <td><input type='text' name='showname' style="width:70px;padding:0;" value='<?php if (isset($_GET["showname"])) echo $_GET["showname"]; else echo $nowuser;?>' /></td>
                <td><label>ID:</label></td>
                <td>
                  <select size="1" name="showpid" id="showpid">
                    <option value=''>All</option>
<?php
	if (db_contest_started($cid)) {
	    $query="select lable,title from contest_problem,problem where cid='$cid' and contest_problem.pid=problem.pid order by lable";
	    $res=mysql_query($query);
	    while ($row=mysql_fetch_array($res)) {
?>
                  <option value='<?php echo $row["lable"] ?>'><?php echo $row["lable"].".".$row['title']; ?></option>
<?php
	    }
	}    
?>
                  </select>
                </td>
                <td><label>Result:</label></td>
                <td>
                  <select size="1" name="showres" id="showres">
                    <option value=''>All</option>
                    <option value='Accepted'>Accepted</option>
<?php
    if (db_contest_has_cha($cid)) {
?>
                    <option value='Pretest Passed'>Pretest Passed</option>
                    <option value='Challenged'>Challenged</option>
<?php
	}    
?>
                    <option value='Wrong Answer'>Wrong Answer</option>
                    <option value='Runtime Error'>Runtime Error</option>
                    <option value='Time Limit Exceed'>Time Limit Exceed</option>
                    <option value='Memory Limit Exceed'>Memory Limit Exceed</option>
                    <option value='Output Limit Exceed'>Output Limit Exceed</option>
                    <option value='Presentation Error'>Presentation Error</option>
                    <option value='Restricted Function'>Restricted Function</option>
                    <option value='Compile Error'>Compile Error</option>
                  </select>
                </td>
                <td><label>Language:</label></td>
                <td>
                  <select size="1" name="showlang" id="showlang">
                    <option value="">All</option>
                    <option value="1">GNU C++</option>
                    <option value="2">GNU C</option>
                    <option value="3">Oracle Java</option>
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
                  </select>
                </td>
                <th><input type='submit' value='Show' /></th>
              </tr>
            </table>
          </form>
<?php
    list($hideot)=mysql_fetch_array(mysql_query("select hide_others from contest where cid='$cid'"));
    if ($hideot&&!db_user_isroot($nowuser)) {
?>
        <div class="center">In this contest, you can only view the submits from yourself.</div>
<?php
    }
?>
          <table class="display" id="statustable">
            <thead>
              <tr>
                <th width='8%'>User</th>
                <th>RunID</th>
                <th width='4%'>ID</th>
                <th width='15%'>Result</th>
                <th width='9%'>Language</th>
                <th width='8%'>Time</th>
                <th width='8%'>Memory</th>
                <th width='7%'>Length</th>
                <th width='13%'>Submit Time</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
        <div class="topdialog" id="sourcewindow">
        </div>
        <div id="one_content_base"></div>
      </div>

