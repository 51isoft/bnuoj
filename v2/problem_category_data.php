<?php
include_once("conn.php");

    $aColumns = array( 'distinct(problem.pid)', 'problem.pid', 'title','source', 'total_ac', 'total_submit','vacnum', 'vtotalnum','vacpnum', 'vtotalpnum', 'vname', 'vid', 'author' );
    $sIndexColumn = "problem.pid";
    $sTable = "problem";
    $sWhere = "";

    if ($_GET["logic"]=="and") {
        for ($i=0;$i<$_GET["catenum"];$i++) {
            if ($i==0) $sTable.=" INNER JOIN ( SELECT pid FROM  `problem_category` WHERE catid ='".convert_str($_GET["cate$i"])."' ) db$i ON db$i.pid = problem.pid";
            else $sTable.=" INNER JOIN ( SELECT pid FROM  `problem_category` WHERE catid ='".convert_str($_GET["cate$i"])."' ) db$i ON db$i.pid = db".($i-1).".pid";
        }
    }
    else if ($_GET["logic"]=="or") {
        $sTable.=" INNER JOIN `problem_category` ON problem.pid =  `problem_category`.pid ";
        for ($i=0;$i<$_GET["catenum"];$i++) {
            if ($sWhere=="") $sWhere=" WHERE ( catid ='".convert_str($_GET["cate$i"])."' ";
            else $sWhere.=" OR catid ='".convert_str($_GET["cate$i"])."' ";
        }
        if ($sWhere!="") $sWhere.=" ) ";
    }
    
    //paging
    $sLimit = "";
    if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
    {
        $sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
            mysql_real_escape_string( $_GET['iDisplayLength'] );
    }

    //ordering
    if ( isset( $_GET['iSortCol_0'] ) )
    {
        $sOrder = "ORDER BY  ";
        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
        {
            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
            {
                $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                    ".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
            }
        }
        
        $sOrder = substr_replace( $sOrder, "", -2 );
        if ( $sOrder == "ORDER BY" )
        {
            $sOrder = "";
        }
    }
    
    //filtering
    if ( $_GET['sSearch'] != "" )
    {
        $sWhere = "WHERE (";
        for ( $i=1 ; $i<count($aColumns) ; $i++ )
        {
            if ($aColumns[$i]=="source"||$aColumns[$i]=="title") {
                $str=$_GET['sSearch'];
                $change = array(
                    ' '=>'%',
                );
                $s=strtr(mysql_real_escape_string($str),$change);
                $sWhere .= $aColumns[$i]." LIKE '%".$s."%' OR ";
            }
            else $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';
    }
    
    /* Individual column filtering */
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
        {
            if ( $sWhere == "" )
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            if ($i==0) {
                if ($_GET['sSearch_'.$i]=="1") $sWhere .= " problem.pid not in (select distinct(pid) from status where result='accepted' and username='$nowuser') ";
                else if ($_GET['sSearch_'.$i]=="0") $sWhere .= "problem.pid like '%'";
            }
            else if($aColumns[$i]=="vname") $sWhere .= $aColumns[$i]." = '".mysql_real_escape_string($_GET['sSearch_'.$i])."' ";
            else $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
        }
    }
    if ( $sWhere == "" ) $sWhere = "WHERE hide=0";
    else $sWhere.= " AND hide=0" ;
    /*
     * SQL queries
     * Get data to display
     */
    $sQuery = "
        SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
        FROM   $sTable
        $sWhere
        $sOrder
        $sLimit
    ";
    //echo $sQuery;die();
    $rResult = mysql_query( $sQuery ) or die(mysql_error());
    
    /* Data set length after filtering */
    $sQuery = "
        SELECT FOUND_ROWS()
    ";
    $rResultFilterTotal = mysql_query( $sQuery ) or die(mysql_error());
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];
    
    /* Total data set length */
    $sQuery = "
        SELECT COUNT(".$sIndexColumn.")
        FROM   $sTable where hide=0
    ";
    $rResultTotal = mysql_query( $sQuery ) or die(mysql_error());
    $aResultTotal = mysql_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];
    
    
    /*
     * Output
     */
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );
    
    $mark=0;
    
    while ( $aRow = mysql_fetch_array( $rResult ) )
    {
        $row = array();
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ($i==0) {
                if ($nowuser!="") {
                   if (mysql_num_rows(mysql_query("select * from status where pid='$aRow[1]' and username='$nowuser' and result='Accepted'"))>0) $row[]="Yes";
                   else if (mysql_num_rows(mysql_query("select * from status where pid='$aRow[1]' and username='$nowuser'"))>0) $row[]="No";
                   else $row[]="";
                }
                else $row[]="";
            }
            else if ($aColumns[$i] == "title" ) {
                $row[] = "<a href='problem_show.php?pid=$aRow[1]' title='".htmlspecialchars($aRow[ $aColumns[$i] ],ENT_QUOTES)."'>".$aRow[ $aColumns[$i] ]."</a>";
            }
            else if ($aColumns[$i] == "problem.pid" ) {
                $row[] = "<a href='problem_show.php?pid=$aRow[1]' title='".htmlspecialchars($aRow[ $aColumns[$i] ],ENT_QUOTES)."'>".$aRow[ 1 ]."</a>";
            }
            else if ($aColumns[$i] == "source" ) {
                $row[] = "<a class='source_search' href='javascript:void(0);' title='".htmlspecialchars($aRow[ $aColumns[$i] ],ENT_QUOTES)."'>".$aRow[ $aColumns[$i] ]."</a>";
            }
            else if ($aColumns[$i] == "total_ac") {
                $row[] = "<a href='status.php?showpid=$aRow[1]&showres=Accepted'>".$aRow[ $aColumns[$i] ]."</a>";
            }
            else if ($aColumns[$i] == "total_submit") {
                $row[] = "<a href='status.php?showpid=$aRow[1]'>".$aRow[ $aColumns[$i] ]."</a>";
            }
            else if ( $aColumns[$i] != ' ' && $aColumns[$i] != 'author' )
            {
                /* General output */
                $row[] = $aRow[ $aColumns[$i] ];
            }
        }
//        if ($mark%2==1) $row["DT_RowClass"]="gradeC even";
//        else $row["DT_RowClass"]="gradeC odd";
//        $row["DT_RowClass"]="gradeC";
        $mark++;
        $output['aaData'][] = $row;
    }
    
    echo json_encode( $output );

?>
