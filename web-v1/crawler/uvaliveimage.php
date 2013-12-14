<?php
require_once 'simple_html_dom.php';
for ($i=367;$i<=367;$i++) {
$baseurl="http://livearchive.onlinejudge.org/";
$purl="http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&page=show_problem&problem=$i";
$html=file_get_html($purl);
if ($html==null) continue;
$iframe=$html->find("iframe",0);
//echo "src: ".$baseurl.$iframe->src;
$turl=strstr($iframe->src,".html",true).".pdf";
//echo "pdf: ".$baseurl.$turl;
$baseext=substr($iframe->src,0,strrpos($iframe->src,"/"))."/";
$base=$baseurl.$baseext;
//echo $base;
$html=file_get_html($baseurl.$iframe->src);
//echo $html;
//echo $html->find("pre",0);
$imgs=$html->find("img");
$ced=array();
foreach ($imgs as $img) {
	$real=$baseext.$img->src;
	if (isset($ced[$real])) continue;
	file_put_contents("/var/www/contest/".$real, file_get_contents("http://livearchive.onlinejudge.org/".$real));
	echo $real."<br />";
	$ced[$real]=true;
}
}
?>
