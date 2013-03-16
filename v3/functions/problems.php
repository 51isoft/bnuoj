<?php
include_once(dirname(__FILE__)."/global.php");

class Problem {
    private $info=array();
    private $valid=false;

    function set_problem($pid) {
        global $db;
        $sql="select * from problem where pid='$pid'";
        $db->query($sql);
        $num=$db->num_rows;
        if ($num==0) return false;
        $this->valid=true;
        unset($this->info);
        $this->info["pid"]=$pid;
        // $this->info=$db->get_row(null,ARRAY_A);
        return true;
    }

    function get_to_page($problemperpage) {
        global $db;
        if (!$this->valid) return null;
        if (isset($this->info["to_page"])) return $this->info["to_page"];
        $querypage="select count(*) from problem where pid<'".$db->escape($this->info["pid"])."' and hide=0";
        list($ppage)=$db->get_row($querypage,ARRAY_A);
        return $this->info["to_page"]=intval($ppage/$problemperpage)+1;
    }
    function get_to_url() {
        global $db;
        if (!$this->valid) return null;
        if (isset($this->info["to_url"])) return $this->info["to_url"];
        if (!isset($this->info["vname"])) $this->get_val("vname");
        if (!isset($this->info["vid"])) $this->get_val("vid");
        
        $vname=$db->escape($this->info["vname"]);
        $vid=$db->escape($this->info["vid"]);

        if ($vname=="PKU")  $this->info["to_url"]="<a href='http://acm.pku.edu.cn/JudgeOnline/problem?id=$vid' target='_blank'>$vid</a>";
        if ($vname=="CodeForces")  {
            $ov=$vid;
            $v1=$vid[strlen($vid)-1];
            $tv=$vid;
            $tv[strlen($vid)-1]='/';
            $this->info["to_url"]="<a href='http://codeforces.com/problemset/problem/$tv$v1' target='_blank'>$ov</a>";
        }
        if ($vname=="HDU")  $this->info["to_url"]="<a href='http://poj.org/problem?id=$vid' target='_blank'>$vid</a>";
        if ($vname=="SGU")  $this->info["to_url"]="<a href='http://acm.sgu.ru/problem.php?contest=0&problem=$vid' target='_blank'>$vid</a>";
        if ($vname=="LightOJ")  $this->info["to_url"]="<a href='http://www.lightoj.com/volume_showproblem.php?problem=$vid' target='_blank'>$vid</a>";
        if ($vname=="Ural")  $this->info["to_url"]="<a href='http://acm.timus.ru/problem.aspx?num=$vid' target='_blank'>$vid</a>";
        if ($vname=="ZJU")  $this->info["to_url"]="<a href='http://acm.zju.edu.cn/onlinejudge/showProblem.do?problemCode=$vid' target='_blank'>$vid</a>";
        if ($vname=="SPOJ")  $this->info["to_url"]="<a href='http://www.spoj.pl/problems/$vid/' target='_blank'>$vid</a>";
        if ($vname=="UESTC")  $this->info["to_url"]="<a href='http://acm.uestc.edu.cn/problem.php?pid=$vid' target='_blank'>$vid</a>";
        if ($vname=="FZU")  $this->info["to_url"]="<a href='http://acm.fzu.edu.cn/problem.php?pid=$vid' target='_blank'>$vid</a>";
        if ($vname=="NBUT")  $this->info["to_url"]="<a href='http://cdn.ac.nbutoj.com/Problem/view.xhtml?id=$vid' target='_blank'>$vid</a>";
        if ($vname=="WHU")  $this->info["to_url"]="<a href='http://acm.whu.edu.cn/land/problem/detail?problem_id=$vid' target='_blank'>$vid</a>";
        if ($vname=="SYSU")  $this->info["to_url"]="<a href='http://soj.me/$vid' target='_blank'>$vid</a>";
        if ($vname=="UVALive")  {
            if (intval($vid)>5722) $svid=intval($vid)+10;
            else $svid=$vid;
            $this->info["to_url"]="<a href='http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&page=show_problem&problem=".(intval($svid)-1999)."' target='_blank'>$vid</a>";
        }
        if ($vname=="UVA")  {
            list($url)=$db->get_row("select url from vurl where voj='UVA' and vid='$vid'",ARRAY_A);
            $this->info["to_url"]="<a href='$url' target='_blank'>$vid</a>";
        }
        return $this->info["to_url"];
    }

    function get_i64io_info() {
        global $db;
        if (!$this->valid) return null;
        if (isset($this->info["i64io_info"])) return $this->info["i64io_info"];
        if (!isset($this->info["vname"])) $this->get_val("vname");
        $vname=$db->escape($this->info["vname"]);
        $ojrow=$db->get_row("select * from ojinfo where name='$vname'",ARRAY_A);
        $this->info["i64io_info"]=$ojrow['int64io'];
        $this->info["java_class"]=$ojrow['javaclass'];
        return $this->info["i64io_info"];
    }

    function get_java_class() {
        global $db;
        if (!$this->valid) return null;
        if (isset($this->info["java_class"])) return $this->info["java_class"];
        if (!isset($this->info["vname"])) $this->get_val("vname");
        $vname=$db->escape($this->info["vname"]);
        $ojrow=$db->get_row("select * from ojinfo where name='$vname'",ARRAY_A);
        $this->info["i64io_info"]=$ojrow['int64io'];
        $this->info["java_class"]=$ojrow['javaclass'];
        return $this->info["java_class"];
    }

    function get_tagged_category() {
        global $db;
        if (!$this->valid) return null;
        if (isset($this->info["tagged_category"])) return $this->info["tagged_category"];
        $this->info["tagged_category"]=$db->get_results("select name,catid,weight from category, problem_category where pid='".$db->escape($this->info["pid"])."' and category.id=problem_category.catid and weight>0",ARRAY_A);
        return $this->info["tagged_category"];
    }

    function get_stat() {
        global $db;
        if (!$this->valid) return null;
        if (isset($this->info["stat"])) return $this->info["stat"];
        $this->info["stat"]=array();
        $pid=$db->escape($this->info['pid']);
        list($this->info["stat"]["num_ac"])=$db->get_row("select count(*) from status where pid='$pid' and result='Accepted'",ARRAY_N);
        list($this->info["stat"]["num_ce"])=$db->get_row("select count(*) from status where pid='$pid' and result='Compile Error'",ARRAY_N);
        list($this->info["stat"]["num_wa"])=$db->get_row("select count(*) from status where pid='$pid' and result='Wrong Answer'",ARRAY_N);
        list($this->info["stat"]["num_pe"])=$db->get_row("select count(*) from status where pid='$pid' and result='Presentation Error'",ARRAY_N);
        list($this->info["stat"]["num_re"])=$db->get_row("select count(*) from status where pid='$pid' and result='Runtime Error'",ARRAY_N);
        list($this->info["stat"]["num_tle"])=$db->get_row("select count(*) from status where pid='$pid' and result='Time Limit Exceed'",ARRAY_N);
        list($this->info["stat"]["num_mle"])=$db->get_row("select count(*) from status where pid='$pid' and result='Memory Limit Exceed'",ARRAY_N);
        list($this->info["stat"]["num_ole"])=$db->get_row("select count(*) from status where pid='$pid' and result='Output Limit Exceed'",ARRAY_N);
        list($this->info["stat"]["num_rf"])=$db->get_row("select count(*) from status where pid='$pid' and result='Restricted Function'",ARRAY_N);
        list($this->info["stat"]["num_total"])=$db->get_row("select count(*) from status where pid='$pid'",ARRAY_N);
        $this->info["stat"]["num_other"]=$this->info["stat"]["num_total"]-
            $this->info["stat"]["num_rf"]-
            $this->info["stat"]["num_ole"]-
            $this->info["stat"]["num_mle"]-
            $this->info["stat"]["num_tle"]-
            $this->info["stat"]["num_re"]-
            $this->info["stat"]["num_pe"]-
            $this->info["stat"]["num_wa"]-
            $this->info["stat"]["num_ce"]-
            $this->info["stat"]["num_ac"];
        return $this->info["stat"];
    }

    function get_col($str) {
        global $db;
        if (!$this->valid) return null;
        if (isset($this->info[$str])) return $this->info[$str];
        $row=$db->get_row("select $str from problem where pid='".$db->escape($this->info["pid"])."'",ARRAY_N);
        return $this->info[$str]=$row[0];
    }
    function get_val($str) {
        if (!$this->valid) return null;
        if (isset($this->info[$str])) return $this->info[$str];
        $tstr="get_".$str;
        if (method_exists($this,$tstr)) return $this->$tstr();
        else return $this->get_col($str);
    }

    function is_valid() {
        return $this->valid;
    }
}

function problem_exist($pid) {
    global $db;
    $db->query("select * from problem where pid = '$pid'");
    if ($db->num_rows==0) return false;
    else return true;
}

function problem_hidden($pid) {
    global $db;
    $row = $db->get_row("select hide from problem where pid = '$pid'",ARRAY_N);
    if ($row[0]=='0') return false;
    else return true;
}

function problem_get_title($pid) {
    global $db;
    $row = $db->get_row("select title from problem where pid = '$pid'",ARRAY_N);
    return $row[0];
}

function problem_get_id_from_virtual($vname,$vid) {
    global $db;
    $row = $db->get_row("select pid from problem where vname='$vname' and vid = '$vid'",ARRAY_N);
    return $row[0];
}

$problem_categories=null;
function problem_search_category($row,$depth) {
    global $problem_categories,$db;
    $trow["id"]=$row["id"];
    $trow["depth"]=$depth;
    $trow["name"]=$row["name"];
    $problem_categories[]=$trow;

    foreach ((array)$db->get_results("select * from category where parent='".$row['id']."'",ARRAY_A) as $row) problem_search_category($row,$depth+1);
}

function problem_get_category() {
    global $problem_categories,$db;
    if (isset($problem_categories)) return $problem_categories;
    foreach ((array)$db->get_results("select * from category where parent='-1'",ARRAY_A) as $row) problem_search_category($row,0);
    return $problem_categories;
}

function problem_get_category_name_from_id($id) {
    global $db;
    list($ctname)=$db->get_row("select name from category where id='$id'",ARRAY_N);
    return $ctname;
}

function problem_get_category_parent_from_id($id) {
    global $db;
    list($parent)=$db->get_row("select parent from category where id='$id'",ARRAY_N);
    return $parent;
}

?>
