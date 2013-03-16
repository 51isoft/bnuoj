<?php
include_once("conn.php");

$aColumns = array( 'cid', 'title', 'start_time', 'end_time', 'hide_others' , 'isprivate', 'owner','isvirtual','type','has_cha','challenge_end_time','challenge_start_time' );
$sIndexColumn = "cid";
$sTable = "contest";

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
$sWhere = "";
if ( $_GET['sSearch'] != "" )
{
    $sWhere = "WHERE (";
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
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
        if ($aColumns[$i]=="isvirtual"||$aColumns[$i]=="isprivate") $sWhere .= $aColumns[$i]." = '".convert_str($_GET['sSearch_'.$i])."' ";
        else if ($aColumns[$i]=="type") {
            if ($_GET['sSearch_'.$i]!="-99") $sWhere .= $aColumns[$i]." = '".convert_str($_GET['sSearch_'.$i])."' ";
            else $sWhere .= $aColumns[$i]." != '99' ";
        }
        else $sWhere .= $aColumns[$i]." LIKE '%".convert_str($_GET['sSearch_'.$i])."%' ";
    }
}
//    echo "<script>alert($sWhere);</script>";
/*
 * SQL queries
 * Get data to display
 */
$sQuery = "
    SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." type
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
    FROM   $sTable
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
    //var_dump($aRow);
    $row = array();
    $orgt=$aRow['title'];
    if ($aRow[8]==1) $aRow['title']="<span style='color:blue'>[CF]</span> ".$aRow['title'];
    else if ($aRow[8]==99) $aRow['title']="<span style='color:blue'>[Replay]</span> ".$aRow['title'];
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ($aColumns[$i]=='hide_others') continue;
        if ($aColumns[$i]=='end_time') {
            if ($aRow['has_cha']==1) $row[] = $aRow[ 10 ];
            else $row[] = $aRow[ $aColumns[$i] ];
            $nowt=date("Y-m-d H:i:s");
            if ($nowt<$aRow['start_time']) $row[]="<span class='cscheduled'>Scheduled</span>";
            else if ($aRow['has_cha']==1&&$nowt>$aRow['end_time']&&$nowt<$aRow[11]) $row[]="<span class='crunning'>Intermission</span>";
            else if ($aRow['has_cha']==1&&$nowt>$aRow[11]&&$nowt<$aRow[10]) $row[]="<span class='crunning'>Challenging</span>";
            else if ($nowt<$aRow['end_time']) $row[]="<span class='crunning'>Running</span>";
            else $row[]="<span class='cpassed'>Passed</span>";
        }
        // else if ($aColumns[$i] == "cid" ) {
        //     $row[] = "<a href='contest_show.php?cid=$aRow[0]'>".$aRow[ $aColumns[$i] ]."</a>";
        // }
        // else if ($aColumns[$i] == "title" ) {
        //     $row[] = "<a title=\"".htmlspecialchars($orgt)."\" href='contest_show.php?cid=$aRow[0]'>".$aRow[ $aColumns[$i] ]."</a>";
        // }
        else if ( $aColumns[$i] == 'isprivate' ) {
            if ($aRow[$i]==0) $row[]="<span class='cpublic'>Public</span>";
            else if ($aRow[$i]==2) $row[]="<span class='cprivate'>Password</span>";
            else $row[]="<span class='cprivate'>Private</span>";
        }
        else if ( $aColumns[$i] == 'isvirtual' ) {
            if ($aRow[$i]==0) $row[]="Normal";
            else if ($aRow[$i]==1) $row[]="Virtual";
        }
        // else if ( $aColumns[$i] == 'owner' ) {
        //     if ($aRow[$i]=='') $row[]="-";
        //     else $row[]="<a href='userinfo.php?name=".$aRow[$aColumns[$i]]."'>".$aRow[$aColumns[$i]]."</a>";
        // }
        else if ( $aColumns[$i]!='challenge_end_time' && $aColumns[$i]!='challenge_start_time' && $aColumns[$i] != 'has_cha' && $aColumns[$i] != ' ') {
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
