<?php
    include_once("conn.php");
    $pid = convert_str($_GET['pid']);
    if ($pid=="") $pid="0";
    $aColumns = array( "runid","count(*)","runid","username","time_used","memory_used","language","length(source)" );
    $sTable = "(select runid,username,time_used,memory_used,language,source,time_submit from status where result='Accepted' and pid='$pid' order by time_used,memory_used,length(source),time_submit) status2 ";
    
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
    
//    echo "<script>alert($sWhere);</script>";
    /*
     * SQL queries
     * Get data to display
     */
    $sQuery = "
        SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
        FROM   $sTable
        GROUP BY username
        $sWhere
        $sOrder
        $sLimit
    ";
//    echo $sQuery;die();
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
        SELECT COUNT(*)
        FROM   $sTable
    ";
//    echo $sQuery;die();

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
    $cnt=0;
    while ( $aRow = mysql_fetch_array( $rResult ) )
    {
        $cnt++;
        $row = array();
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ($aColumns[$i] == "username" ) {
                $row[] = "<a href='userinfo.php?name=".$aRow[ $aColumns[$i] ]."'>".$aRow[ $aColumns[$i] ]."</a>";
            }
            else if ($i == 0 ) {
                $row[] = intval($_GET['iDisplayStart'])+$cnt;
            }
            else if ($aColumns[$i] == "time_used" ) {
                $row[] = $aRow[ $aColumns[$i] ]." ms";
            }
            else if ($aColumns[$i] == "memory_used") {
                $row[] = $aRow[ $aColumns[$i] ]." KB";
            }
            else if ($aColumns[$i] == "length(source)") {
                $row[] = $aRow[ $aColumns[$i] ]." B";
            }
            else if ($aColumns[$i] == "language") {
                $row[] = match_lang($aRow[ $aColumns[$i] ]);
            }
            else if ($aColumns[$i] == "count(*)") {
                $row[] = "<a href='status.php?showpid=$pid&showres=Accepted&showname=".$aRow['username']."'>".$aRow[ $aColumns[$i] ]."</a>";
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
