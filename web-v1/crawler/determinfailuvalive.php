<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'simple_html_dom.php';
$base="http://202.112.88.60/bnuoj/";
for ($i=8542;$i<=10176;$i++) {
	$purl="http://202.112.88.60/bnuoj/problem_show.php?pid=$i";
	$html=file_get_contents($purl);
//	echo $html;
	if ($html==null) continue;
	$doc = new DOMDocument();
	$doc->loadHTML($html);
	$imgs=$doc->getElementsByTagName('img');
	foreach ($imgs as $aimg) {
		$img=$aimg->getAttribute('src');
		$real=$base.$img;
		if (isset($ced[$img])) continue;
		if (!file_exists($real)) {
			echo "$i\t".($i-6176)."\n";
			flush();
			break;
		}
		$ced[$img]=true;
	}
}
?>
