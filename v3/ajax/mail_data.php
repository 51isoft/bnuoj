<?php
include_once(dirname(__FILE__)."/../functions/users.php");

$user=convert_str($_GET['username']);
$aColumns = array( 'mailid', 'sender','reciever', 'title', 'mail_time', 'status' );
$sIndexColumn = "mailid";
$sTable = "mail";

//paging
$sLimit = "";
if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
{
    $sLimit = "LIMIT ".convert_str( $_GET['iDisplayStart'] ).", ".
        convert_str( $_GET['iDisplayLength'] );
}
if(!$current_user->match($user)||!$current_user->is_valid()) $sLimit = "LIMIT 0,0";

//ordering
if ( isset( $_GET['iSortCol_0'] ) )
{
    $sOrder = "ORDER BY  ";
    for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
    {
        if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
        {
            $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                ".convert_str( $_GET['sSortDir_'.$i] ) .", ";
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
        $sWhere .= $aColumns[$i]." LIKE '%".convert_str( $_GET['sSearch'] )."%' OR ";
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
        $sWhere .= $aColumns[$i]." LIKE '%".convert_str($_GET['sSearch_'.$i])."%' ";
    }
}


if ( $sWhere == "" ) $sWhere = "WHERE (reciever='$user' or sender='$user')";
$sWhere.= " AND (reciever='$user' or sender='$user')" ;

/* Total data set length */
$sQuery = "
    SELECT COUNT(".$sIndexColumn.")
    FROM   $sTable where reciever='$user' or sender='$user'
";
$aResultTotal = $db->get_row($sQuery,ARRAY_N);
$iTotal = $aResultTotal[0];
if ($EZSQL_ERROR) die("SQL Error!");

/*
 * SQL queries
 * Get data to display
 */
$sQuery = "
    SELECT COUNT(".$sIndexColumn.")
    FROM   $sTable
    $sWhere
    $sOrder
";
$db->query($sQuery);
list($iFilteredTotal)=$db->get_row($sQuery,ARRAY_N);

/*
 * Output
 */
$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);

$sQuery = "
    SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
    FROM   $sTable
    $sWhere
    $sOrder
    $sLimit
";

foreach ( (array)$db->get_results( $sQuery,ARRAY_A ) as $aRow )
{
    $row = array();
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( $aColumns[$i] != ' ' )
        {
            /* General output */
            $row[] = $aRow[ $aColumns[$i] ];
        }
    }
    $output['aaData'][] = $row;
}

echo json_encode( $output );

?>
