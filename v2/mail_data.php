<?php
include_once("conn.php");
    $user=convert_str($_GET['username']);
    $aColumns = array( 'mailid', 'sender','reciever', 'title', 'mail_time', 'status' );
    $sIndexColumn = "mailid";
    $sTable = "mail";
    
    //paging
    $sLimit = "";
    if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
    {
        $sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
            mysql_real_escape_string( $_GET['iDisplayLength'] );
    }
    if($nowuser!=$user||$nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)) $sLimit = "LIMIT 0,0";

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
    $sWhere = "";
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
            /*if ($aColumns[$i]=="vname") {
                if ($_GET['sSearch_'.$i]=="BNU") $sWhere .= "vname = '' ";
                else $sWhere .= $aColumns[$i]." = '".mysql_real_escape_string($_GET['sSearch_'.$i])."' ";
            }
            else*/ $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
        }
    }
//    echo "<script>alert($sWhere);</script>";
    if ( $sWhere == "" ) $sWhere = "WHERE (reciever='$user' or sender='$user')";
    $sWhere.= " AND (reciever='$user' or sender='$user')" ;
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
        SELECT COUNT(".$sIndexColumn.")
        FROM   $sTable where reciever='$user' or sender='$user'
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
        for ( $i=0 ; $i<count($aColumns)-1 ; $i++ )
        {
            if ($aColumns[$i] == "sender" ) {
                $row[] = "<a href='userinfo.php?name=".$aRow[$aColumns[$i]]."'>".$aRow[ $aColumns[$i] ]."</a>";
            }
            else if ($aColumns[$i] == "title" ) {
                if ($aRow[ $aColumns[count($aColumns)-1] ]=="0") $row[] = "<a class='getmail' href='javascript:void(0);' name='".$aRow[$aColumns[0]]."'><b>".htmlspecialchars($aRow[ $aColumns[$i] ])."</b></a>";
                else $row[] = "<a class='getmail' name='".$aRow[$aColumns[0]]."' href='javascript:void(0);'>".htmlspecialchars($aRow[ $aColumns[$i] ])."</a>"; 
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
