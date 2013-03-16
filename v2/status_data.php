<?php
include_once("conn.php");

    $aColumns = array( 'username', 'runid', 'pid', 'result', 'language', 'time_used', 'memory_used', 'length(source)',"time_submit","isshared" );
    $sIndexColumn = "runid";
    $sTable = "status";
    
    //paging
    $sLimit = "";
    if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
    {
        $sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
            mysql_real_escape_string( $_GET['iDisplayLength'] );
    }

    //ordering
/*    if ( isset( $_GET['iSortCol_0'] ) )
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
*/
    $sOrder = "ORDER BY runid desc";
    
    //filtering
/*    $sWhere = "";
    if ( $_GET['sSearch'] != "" )
    {
        $sWhere = "WHERE (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
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
    }*/
    
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
            if ($aColumns[$i]=="pid"||$aColumns[$i]=="language"||$aColumns[$i]=="username"||$aColumns[$i]=="result") {
                $sWhere .= $aColumns[$i]." = '".mysql_real_escape_string($_GET['sSearch_'.$i])."' ";
            }
            else $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
        }
    }
    $extra_condition="(contest_belong=0 or contest_belong in (select cid from contest where end_time<now()))";
    $normal_condition="contest_belong=0";
    $condition=$extra_condition;
//    echo "<script>alert($sWhere);</script>";
    if ( $sWhere == "" ) $sWhere = "WHERE ".$condition;
    else $sWhere.= " AND ".$condition ;
    /*
     * SQL queries
     * Get data to display
     */
    $sQuery = "
        SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
        FROM   $sTable
        $sWhere
        $sOrder
        $sLimit
    ";
//    echo $sQuery;die();
    $rResult = mysql_query( $sQuery ) or die(mysql_error());
    
    /* Data set length after filtering */
/*    $sQuery = "
        SELECT FOUND_ROWS()
    ";
    $rResultFilterTotal = mysql_query( $sQuery ) or die(mysql_error());
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];*/
    $iFilteredTotal=$maxrunid;
    
    /* Total data set length */
    $sQuery = "
        SELECT max(".$sIndexColumn.")
        FROM   $sTable
    ";
    $rResultTotal = mysql_query( $sQuery ) or die(mysql_error());
    $aResultTotal = mysql_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];
//    $iTotal=$maxrunid;
    
    
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
    $isv=db_user_iscodeviewer($nowuser);
    while ( $aRow = mysql_fetch_array( $rResult ) )
    {
        $row = array();
        $aRow["language"]=match_lang($aRow["language"]);
        if ($aRow["memory_used"]!=0) {
            $aRow["memory_used"].=" KB";
            $aRow["time_used"].=" ms";
        }
        else {
            $aRow["memory_used"]="";
            if ($aRow["time_used"]!=0) $aRow["time_used"].=" ms"; else $aRow["time_used"]="";
        }
        $aRow["length(source)"].=" B";
        if ($aRow["isshared"]==TRUE||strcasecmp($nowuser,$aRow["username"])==0||$isv) $aRow["length(source)"]='[ '.$aRow["length(source)"].' ]';
        for ( $i=0 ; $i<count($aColumns)-1 ; $i++ )
        {
            if ($aColumns[$i] == "pid" ) {
                $row[] = "<a href='problem_show.php?pid=".$aRow[ $aColumns[$i] ]."'>".$aRow[ $aColumns[$i] ]."</a>";
            }
            else if ($aColumns[$i] == "runid"||$aColumns[$i]=="length(source)"||$aColumns[$i]=="language" ) {
                if ($aRow["isshared"]==TRUE||strcasecmp($nowuser,$aRow["username"])==0||$isv) {
                    $row[] = "<a href='#' class='showsource' name='".$aRow["runid"]."'>".$aRow[ $aColumns[$i] ]."</a>";
                }
                else $row[] = $aRow[ $aColumns[$i] ];
            }
            else if ($aColumns[$i] == "username") {
                $row[] = "<a target='_blank' href='userinfo.php?name=".$aRow[ $aColumns[$i] ]."'>".$aRow[ $aColumns[$i] ]."</a>";
            }
            else if ($aColumns[$i] == "result") {
                if ($aRow[ $aColumns[$i] ]=="Compile Error") {
                    $row[] = "<a href='#' class='ceinfo' title='".$aRow["runid"]."'><span class='".getshort($aRow[ $aColumns[$i] ])."'>".$aRow[ $aColumns[$i] ]."</span></a>
                              <div class='ceinfo'><img height='20px' src='style/ajax-loader.gif' /> Loading...</div>
                    ";
                }
                else {
                    $row[] = "<span class='".getshort($aRow[ $aColumns[$i] ])."'>".$aRow[ $aColumns[$i] ]."</span>";
                }
            }
            else if ( $aColumns[$i] != ' ' )
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
