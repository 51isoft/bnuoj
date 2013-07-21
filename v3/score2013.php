<?php
$pagetitle="2013年暑期训练积分榜";
include_once("header.php");

$contests=array(613,614,615,616,617,618,619,620,621,622,623,624,625,626,627,628);

$team=array();
$team[]=array("random","易超 龚治 李思源");
$team[]=array("Attiix","赵力 王骁 王梦非");
$team[]=array("_CD","吴浪 龙翔 张伯威");
$team[]=array("Sellamoe","黎明明 于馨培 袁伟舜");
$team[]=array("chd","董自鸣 何冬杰 陈辉");
$team[]=array("11621","刘芳 盛乔一 焦璐");
$team[]=array("xuanmei","徐丽妹 王奕轩 吴锦昊");
$team[]=array("actravel","李奕 李安然 孙萧育");

for ($i=0;$i<8;$i++) $team[$i]["punish"]=0.0;
$team[4]["punish"]=-0.4;
$team[6]["punish"]=-0.4;
$team[7]["punish"]=-0.2;
$team[2]["punish"]=-0.4;
$team[3]["punish"]=-0.2;
foreach ($team as $j => $v) {
    $team[$j]["tval"]=array();
    $team[$j]["csum"]=0;
    $team[$j]["psum"]=array();
    foreach ($contests as $i) {
        list($team[$j]["cac".$i])=mysql_fetch_array(mysql_query("select count(distinct(pid)) from status 
            where result='Accepted' and username='".$v[0]."' and contest_belong='$i'"));
        list($fitime)=mysql_fetch_array(mysql_query("select unix_timestamp(end_time) from contest where cid='$i'"));
        list($team[$j]["aac".$i])=mysql_fetch_array(mysql_query("select count(distinct(pid)) from status
            where result='Accepted' and username='".$v[0]."'
            and unix_timestamp(time_submit) <= ".($fitime+2*24*60*60)."
            and pid=any(select pid from contest_problem where cid='$i')"));
        $team[$j]["aac".$i]=($team[$j]["aac".$i]-$team[$j]["cac".$i])*0.3;
        $team[$j]["tval"][]=$team[$j]["aac".$i]+$team[$j]["cac".$i];
        $team[$j]["csum"]+=$team[$j]["cac".$i];
        $team[$j]["rsum"]+=$team[$j]["aac".$i]+$team[$j]["cac".$i];
        $team[$j]["psum"][$i]=$team[$j]["rsum"];
    }
    sort($team[$j]["tval"]);
    $team[$j]["sum"]=0;
    for ($i=sizeof($team[$j]["tval"])-1;$i>=sizeof($team[$j]["tval"])-14;$i--) {
        $team[$j]["sum"]+=$team[$j]["tval"][$i];
    }
    $team[$j]["sum"]+=$team[$j]["punish"];
}
?>
          <div class="span12">
            <h1><?= $pagetitle?></h1>
            <table class="table table-condensed table-striped table-hover">
              <thead>
                <tr>
                  <th style="min-width:140px">队伍/比赛</th>
                  <th>积分<sup>1</sup></th>
                  <th>总分</th>
                  <th>赛中</th>
                  <th>罚分</th>
                  <?php foreach ($contests as $i) { ?>
                  <th><?= "<a href='contest_show.php?cid=$i' target=_blank>".$i."</a>" ?></th>
                  <th><?= $i ?>*</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($team as $j => $v) { ?>
                <tr> 
                  <td><?= $v[0]."<br />".$v[1] ?></td>
                  <td><?= $v["sum"] ?></td>
                  <td><?= $v["rsum"] ?></td>
                  <td><?= $v["csum"] ?></td>
                  <td><?= $v["punish"] ?></td>
                  <?php foreach ($contests as $i) { ?>
                  <td><?= $v["cac".$i] ?></td>
                  <td><?= $v["aac".$i] ?></td>
                  <?php } ?>
                <?php } ?>
                </tr>
              </tbody>
            </table>
            <p align="center">1. 积分为16取14。</p>
            <div id="rank_chart" style="min-width: 400px; height: 400px; margin: 0 auto">
            </div>
            <div id="score_sum_chart" style="min-width: 400px; height: 400px; margin: 0 auto">
            </div>
          </div>

<script type="text/javascript" src="js/highcharts.js"></script>
<script type="text/javascript">
$("table").tablesorter({sortList: [[1,1]]}); 

var chart = new Highcharts.Chart({
    chart: {
        renderTo: 'rank_chart',
            backgroundColor: null,
            type: 'line'
    },
    title: {
        text: '排名曲线',
            x: -20 //center
    },
    xAxis: {
        categories: [<?= implode($contests,',') ?>]
    },
    yAxis: {
        title: {
            text: '排名'
        },
        plotLines: [{
            value: 0,
                width: 1,
                color: '#808080'
        }],
        min: 1,
        max: 9,
        reversed: true
    },
    tooltip: {
        formatter: function() {
            return '<b>'+ this.series.name +'</b><br/>'+
                this.x +': 第'+ this.y +'名';
        }
    },
        legend: {
            layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
        },
        series: [{
            name: 'random',
                data: [2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1]
        }, {
            name: 'Attiix',
                data: [1,2,3,2,2,5,2,2,2,2,2,4,3,2,2,3]
        },{
            name: '_CD',
                data: [3,4,4,4,3,3,3,3,7,4,5,5,2,3,4,2]
        },{
            name: 'Sellamoe',
                data: [4,7,2,3,6,2,7,4,6,3,3,2,5,4,3,4]
        },{
            name: 'chd',
                data: [6,5,6,5,4,4,4,5,4,6,4,3,4,6,5,5]
        },{
            name: '11621',
                data: [5,3,5,6,5,6,6,6,3,5,7,6,6,5,6,6]
        },{
            name: 'xuanmei',
                data: [7,8,7,7,8,8,5,7,5,7,6,8,7,7,8,7]
        },{
            name: 'actravel',
                data: [7,6,8,8,7,7,7,8,8,8,8,7,8,8,7,8]
        }]
});
var sumchart = new Highcharts.Chart({
    chart: {
        renderTo: 'score_sum_chart',
            backgroundColor: null,
            type: 'line'
    },
    title: {
        text: '累计分数曲线（全取，不算罚分）',
            x: -20 //center
    },
    xAxis: {
        categories: [<?=implode($contests,",") ?>]
    },
    yAxis: {
        title: {
            text: '累计分数'
        },
        plotLines: [{
            value: 0,
                width: 1,
                color: '#808080'
        }],
        min:0
    },
    tooltip: {
        formatter: function() {
            return '<b>'+ this.series.name +'</b><br/>'+
                '累积到CID '+this.x +': '+ this.y +'分';
        }
    },
        legend: {
            layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
        },
        series: [
<?php
$fir=false;
foreach ($team as $j => $v) {
    if ($fir) echo ",";
    $fir=true;
?>
    {
        name: '<?= $v[0] ?>',
            data: [<?= implode($v["psum"],",") ?>]
    }
<?php
}
?>
]
});
</script>
<?php
include("footer.php");
?>
