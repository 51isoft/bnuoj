<?php
  $pagetitle="北京师范大学ACM/ICPC校队";
  include_once("header.php");
  include_once("menu.php");
?>
    <link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.css' />
    <div id="site_content">
      <div id="content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
          <p>上次更新日期：<?php echo date ("Y-m-d H:i:s", getlastmod()); ?></p>
          <div id="infotabs">
            <ul>
              <li style="padding:0"><a href="#teaminfo">队伍简介</a></li>
              <li style="padding:0"><a href="#memberinfo">队员现况</a></li>
              <li style="padding:0"><a href="#honorinfo">ICPC成绩</a></li>
              <li style="padding:0"><a id="tplan" href="#trainingplan">2012暑期训练计划</a></li>
            </ul>
            <div id="memberinfo">
              <table style="width:100%" id="membertable" class="display infotable">
                <thead>
                  <tr>
                    <th style="width:80px">姓名</th>
                    <th style="width:90px">年级</th>
                    <th style="width:105px">学院</th>
                    <th style="width:490px">经历与现况</th>
                  </tr>
                </thead>
                <tbody>
  <tr><td>王希</td><td>1999级本</td><td>信科</td><td>放弃保研，英国留学一年，曾任北京师范大学珠海分校教师兼ACM教练，现就职于2K Games中国分公司</td></tr>
  <tr><td>李丹</td><td>1999级本</td><td>信科</td><td>保送到清华大学计算机系攻读直博。2007年博士毕业后加入微软亚洲研究院任副研究员，2010年3月加入清华大学计算机系，现任副教授，硕士生导师。</td></tr>
  <tr><td>马云</td><td>1999级本</td><td>信科</td><td>现就职于微软美国总部，任软件设计工程师</td></tr>
  <tr><td>付妍</td><td>1999级本</td><td>信科</td><td>保送北大、十佳大学生、1999年度唐仲英德育奖学金</td></tr>
  <tr><td>邢飞</td><td>1999级本<br />2003级硕</td><td>信科</td><td>本校保研</td></tr>
  <tr><td>穆西晗</td><td>1999级本<br />2003级硕<br />2005级博</td><td>信科（本）<br />地遥（硕博）</td><td>本校保研，现就职于本校地遥学院，讲师</td></tr>
  <tr><td>勾朗</td><td>1999级本</td><td>信科</td><td>保送中科院软件所，博士毕业后进入微软，任软件测试工程师（SDET II）</td></tr>
  <tr><td>邹永强</td><td>2000级本</td><td>信科</td><td>保送中科院计算所，十佳提名，现就职于腾讯，任软件工程师</td></tr>
  <tr><td>陈硕</td><td>2000级本<br />2004级硕</td><td>信科</td><td>本校保研，擅长 C++ 多线程网络编程和实时分布式系统架构。曾在微软亚洲工程院（程序开发和调试）、IBM中国研究中心（程序开发）、微软亚洲研究院、Google中国实习，在摩根士丹利 IT 部门工作 5 年，从事实时外汇交易系统开发。现在在美国加州硅谷某互联网大公司工作，从事大规模分布式系统的可靠性工程。编写了开源 C++ 网络库 muduo，参与翻译了《代码大全（第 2 版）》和《C++ 编程规范（繁体版）》，整理了《C++ Primer （第 4 版）（评注版）》，并曾多次在各地技术大会演讲。</td></tr>
  <tr><td>周浩</td><td>2000级本<br />2004级硕</td><td>管理</td><td>本校保研，十佳提名，现就职于傲游</td></tr>
  <tr><td>李绍明</td><td>2000级本<br />2004级硕</td><td>信科</td><td>本校读研</td></tr>
  <tr><td>苟禹</td><td>2000级本<br />2004级硕</td><td>信科</td><td>本校读研，现就职于招商银行</td></tr>
  <tr><td>余吉</td><td>2001级本</td><td>信科</td><td>保送清华</td></tr>
  <tr><td>周游</td><td>2002级本<br />2006级硕</td><td>数科</td><td>本校保研</td></tr>
  <tr><td>张静宁</td><td>2002级本</td><td>物理</td><td>保送中科院，清华博士后</td></tr>
  <tr><td>邓子睿</td><td>2003级本</td><td>信科</td><td>保送北大研究生，现任微软服务器与开发工具事业部工程师，主要负责 SQL Server 测试及测试工具维护工作</td></tr>
  <tr><td>唐巧</td><td>2003级本<br />2007级硕</td><td>信科</td><td>保送本校研究生、十佳大学生，曾在IBM实习，负责办公软件的研发，签约网易有道，曾负责网易邮箱、网易微博的研发，以及有道云笔记的开发。现于粉笔网创业。</td></tr>
  <tr><td>邹宗尧</td><td>2003级本</td><td>心理</td><td>百度之星决赛选手，签约百度</td></tr>
  <tr><td>唐福林</td><td>2003级本</td><td>信科</td><td>达到保研成绩，放弃保研，签约新浪，新浪微博架构师</td></tr>
  <tr><td>李凤凤</td><td>2003级本</td><td>信科</td><td>达到保研成绩，放弃保研，签约华为</td></tr>
  <tr><td>曾强</td><td>2003级本</td><td>数学</td><td>保送北大直博，现于UIUC攻读博士学位</td></tr>
  <tr><td>吴莹莹</td><td>2003级本</td><td>心理</td><td>十佳大学生，放弃读研，任美国Topcoder公司运营部副总裁，在哈佛大学取得硕士学位，Stony Brooks大学博士后</td></tr>
  <tr><td>李文</td><td>2003级本<br />2007级硕</td><td>信科</td><td>保送本校研究生，现在新加坡</td></tr>
  <tr><td>杜晓宇</td><td>2004级本<br />2008级硕</td><td>信科</td><td>保送本校研究生，Google奖学金获得者，曾在百度实习，负责百度贴吧的研发，现任成都信息工程大学教师兼ACM教练</td></tr>
  <tr><td>苏鑫</td><td>2004级本</td><td>信科</td><td>达到保研成绩，放弃保研，现正在创业</td></tr>
  <tr><td>付莉</td><td>2004级本<br />2008级硕</td><td>数学</td><td>保送本校研究生</td></tr>
  <tr><td>张明欣</td><td>2004级本<br />2008级硕</td><td>数学</td><td>保送本校研究生</td></tr>
  <tr><td>王丹丹</td><td>2004级本</td><td>信科</td><td>保送中科院软件所研究生</td></tr>
  <tr><td>刘立波</td><td>2005级本<br />2011级硕</td><td>信科</td><td>11级本校研究生，签约FOXIT，负责PDF阅读器的开发，2年后放弃工作考研</td></tr>
  <tr><td>唐忆戈</td><td>2005级本<br />2009级硕</td><td>信科</td><td>保送本校研究生，签约淘宝，负责一淘的后台搜索技术研发</td></tr>
  <tr><td>陈传亮</td><td>2005级本</td><td>信科</td><td>十佳提名，达到保研成绩，放弃保研，出国读研，获得UCLA硕士学位，曾在微软亚洲研究院实习，创办了云飞跃留学申请平台，现正在创业，公司为乐荐网络，负责社交网络的数据挖掘</td></tr>
  <tr><td>林晓燕</td><td>2005级本</td><td>信科</td><td>保送北大研究生</td></tr>
  <tr><td>黄锟</td><td>2006级本</td><td>信科</td><td>保送北大研究生、十佳大学生</td></tr>
  <tr><td>郭斯瑶</td><td>2006级本</td><td>信科</td><td>放弃北大保送资格，保送港中文研究生</td></tr>
  <tr><td>于济航</td><td>2006级本</td><td>数科</td><td>签约Gameloft，主要负责3D引擎的制作与研发工作</td></tr>
  <tr><td>车丽美</td><td>2007级本</td><td>信科</td><td>保送北大研究生、十佳大学生，Google奖学金获得者</td></tr>
  <tr><td>陈杉</td><td>2007级本</td><td>数科</td><td>保送清华研究生（姚期智的实验室）、十佳提名</td></tr>
  <tr><td>王甦易</td><td>2007级本</td><td>物理</td><td>达到保研成绩，放弃保研，现就读于OSU（俄亥俄州立大学）</td></tr>
  <tr><td>高扬福</td><td>2007级本</td><td>信科</td><td>11级清华研究生</td></tr>
  <tr><td>林子敏</td><td>2007级本</td><td>信科</td><td>11级中科院软件所研究生，在创新工厂行云项目组实习，主要负责数据分析</td></tr>
  <tr><td>龚治</td><td>2007级本<br />2011级硕</td><td>信科</td><td>保送本校研究生，2010年度计算机世界奖学金，2010有道难题现场决赛选手，获得第28名，曾在创新工厂（负责云计算平台构建）、拉手网实习（负责用户推荐算法）</td></tr>
  <tr><td>易超</td><td>2007级本<br />2011级硕</td><td>信科</td><td>保送本校研究生，BNUOJ的开发者</td></tr>
  <tr><td>詹钰</td><td>2007级本<br />2011级硕</td><td>信科</td><td>本科电子系成绩、综合排名第一，保送本校研究生</td></tr>
  <tr><td>杨经宇</td><td>2007级本</td><td>信科</td><td>签约腾讯，负责QQ浏览器的研发工作</td></tr>
  <tr><td>洪涛</td><td>2007级本</td><td>信科</td><td>签约新浪，负责新浪微博客户端软件（Symbian平台）的开发</td></tr>
  <tr><td>张思亮</td><td>2007级本</td><td>信科</td><td>签约人民搜索，负责基础技术（文本提取等）</td></tr>
  <tr><td>韦添</td><td>2007级本<br />2011级硕</td><td>管理</td><td>保送本校研究生</td></tr>
  <tr><td>李思源</td><td>2008级本<br />2012级硕</td><td>信科</td><td>保送本校研究生，2011百度之星前十，VK Cup 2012现场赛选手，2013年微软“编程之美”大赛总决赛前六。曾在网易有道（负责有道云笔记iOS客户端开发）实习。</td></tr>
  <tr><td>卓越</td><td>2008级本</td><td>信科</td><td>达到保研成绩，放弃保研，Google奖学金获得者，现就读于TAMU（德州农工大学）</td></tr>
  <tr><td>陆济川</td><td>2009级本</td><td>信科</td><td>达到保研成绩，放弃保研，Google奖学金获得者，现就读于CMU（卡内基梅隆大学）</td></tr>
  <tr><td>马飞龙</td><td>2009级本</td><td>心理</td><td>09信科转09心理，达到保研成绩，放弃保研，北师大优秀毕业生，现于Dartmouth（达特茅斯学院）攻读博士</td></tr>
  <tr><td>王铭康</td><td>2009级本</td><td>物理</td><td>达到保研成绩，放弃保研，北京市三好学生，现攻读香港科技大学物理系博士</td></tr>
  <tr><td>王骁</td><td>2009级本</td><td>信科</td><td></td></tr>
  <tr><td>于馨培</td><td>2009级本</td><td>信科</td><td>达到保研成绩，放弃保研，现就读于NEU（美国东北大学）</td></tr>
  <tr><td>黎明明</td><td>2009级本</td><td>信科</td><td>签约金山西山居，负责跨平台开发</td></tr>
  <tr><td>龙翔</td><td>2009级本</td><td>天文</td><td>保送清华研究生</td></tr>
  <tr><td>何冬杰</td><td>2009级本</td><td>教技</td><td></td></tr>
  <tr><td>赵力</td><td>2010级本</td><td>信科</td><td>09管理转10信科</td></tr>
  <tr><td>王梦非</td><td>2010级本</td><td>信科</td><td></td></tr>
  <tr><td>陈辉</td><td>2010级本</td><td>信科</td><td>2013年腾讯编程马拉松北京赛区冠军队队长。</td></tr>
  <tr><td>张伯威</td><td>2010级本</td><td>信科</td><td>2013年腾讯编程马拉松北京赛区冠军队成员。</td></tr>
  <tr><td>诸海婷</td><td>2010级本</td><td>心理</td><td></td></tr>
  <tr><td>刘芳</td><td>2010级本</td><td>信科</td><td></td></tr>
  <tr><td>焦璐</td><td>2010级本</td><td>信科</td><td></td></tr>
  <tr><td>盛乔一</td><td>2010级本</td><td>信科</td><td></td></tr>
  <tr><td>董自鸣</td><td>2011级本</td><td>信科</td><td>2013年腾讯编程马拉松北京赛区冠军队成员。</td></tr>
  <tr><td>袁伟舜</td><td>2011级本</td><td>地遥</td><td></td></tr>
                </tbody>
              </table>
              <p style="align:right;text-align:right;padding:0;">
                注：上表包括所有代表北京师范大学参加过现场赛的队员。
              </p>
            </div>
            <div id="teaminfo">
<h4>ACM-ICPC 简介</h4>
<hr />
<p style="text-indent:2em;">
ACM-ICPC 是由 ACM （美国计算机协会）主办的面向大学生的国际程序设计竞赛（International Collegiate Programming Contest），至今已举办了 36 届，被称为大学生的计算机奥林匹克竞赛。比赛由 3 名选手组成一队，在 5 小时内共用 1 台计算机编程比赛，特别强调团队配合和动手解决实际问题的能力，因此不仅被广泛认为是教学成果的体现，而且深受广大大学生的喜爱。目前，全国各知名高校都越来越重视本项赛事，大学生的参赛热情空前高涨。中国大陆地区的竞赛竞争最为激烈，水平居各大洲之首。
</p>
<h4>ACM-ICPC in BNU</h4>
<hr />
<p style="text-indent:2em;">
北师大ACM校队队员是来自信息科学与技术学院、物理系、数学科学学院、心理学院等不同学科的学生，在比赛中充分发挥学科知识互补的优势，每年通过ACM新生赛、北师大程序设计大赛等比赛选拔队员。
</p>
<p style="text-indent:2em;">
长期以来，学校、教务处和信息学院给予 ACM 校队大力支持，提供了良好的训练条件，使得 ACM 校队的水平稳步上升。我校 ACM 校队最初由罗运纶老师在 2002 年组建， 2003 年由信息学院冯速老师接任教练。经过教练和队员的多年努力，最终在 2005 年获得了两银三铜的成绩，结束了我校在该项赛事上从未获奖的历史。2007 年首次比赛就获得一金一铜，更是我校在该项赛事上的一次历史性突破。连续多年的优秀成绩证明，我校优秀学生的综合素质和编程能力已经跻身亚洲先进水平行列。
</p>
            </div>
            <div id="honorinfo">
              <table style="width:100%" id="honortable" class="display infotable">
                <thead>
                  <tr>
                    <th style="width:65px">年度</th>
                    <th style="width:235px">赛区（举办学校）</th>
                    <th style="width:115px">队名</th>
                    <th style="width:180px">队员</th>
                    <th style="width:160px">奖项（排名）</th>
                  </tr>
                </thead>
                <tbody>
<tr><td>2005</td><td>四川赛区（成都大学）</td><td>SHISHI</td><td>吴莹莹 唐文斌 周游</td><td>银奖*（队伍第7名）</td></tr>
<tr><td>2005</td><td>四川赛区（成都大学）</td><td>ABNUYL</td><td>邓子睿 张静宁 唐巧 周游</td><td>银奖（队伍第13名）</td></tr>
<tr><td>2005</td><td>北京赛区（北京大学）</td><td>Arbiter</td><td>唐福林 邹宗尧 杜晓宇</td><td>铜奖（队伍第24名）</td></tr>
<tr><td>2005</td><td>北京赛区（北京大学）</td><td>ACME_BNU</td><td>张静宁 李凤凤 吴莹莹</td><td>铜奖（队伍第41名）</td></tr>
<tr><td>2006</td><td>北京赛区（清华大学）</td><td>Zendos</td><td>邹宗尧 苏鑫 杜晓宇</td><td>优胜奖（队伍第22名）</td></tr>
<tr><td>2007</td><td>长春赛区（吉林大学）</td><td>Tendos</td><td>唐巧 苏鑫 杜晓宇</td><td>金奖（队伍第7名）</td></tr>
<tr><td>2007</td><td>长春赛区（吉林大学）</td><td>Alleyoop</td><td>付莉 张明欣 林晓燕</td><td>铜奖（队伍第41名）</td></tr>
<tr><td>2007</td><td>成都赛区（西华大学）</td><td>Archimedes</td><td>刘立波 黄锟 郭斯瑶</td><td>银奖（队伍第25名）</td></tr>
<tr><td>2007</td><td>北京赛区（北京航空航天大学）</td><td>Tendos</td><td>唐巧 苏鑫 杜晓宇</td><td>铜奖（队伍第37名）</td></tr>
<tr><td>2008</td><td>合肥赛区（中国科技大学）</td><td>Archimedes</td><td>刘立波 黄锟 郭斯瑶</td><td>金奖（队伍第8名）</td></tr>
<tr><td>2008</td><td>合肥赛区（中国科技大学）</td><td>GREEDY</td><td>杜晓宇 龚治 于济航</td><td>银奖（队伍第29名）</td></tr>
<tr><td>2008</td><td>北京赛区（北京交通大学）</td><td>ThinkWorld</td><td>杨经宇 洪涛 车丽美</td><td>优胜奖（队伍第65名）</td></tr>
<tr><td>2008</td><td>北京赛区（北京交通大学）</td><td>Alleyoop</td><td>付莉 张明欣 林晓燕</td><td>优胜奖（队伍第73名）</td></tr>
<tr><td>2008</td><td>成都赛区（西南民族大学）</td><td>uTOPia</td><td>王甦易 易超 林子敏</td><td>优胜奖（队伍第63名）</td></tr>
<tr><td>2008</td><td>杭州赛区（杭州电子科技大学）</td><td>Archimedes</td><td>刘立波 黄锟 郭斯瑶</td><td>银奖（队伍第29名）</td></tr>
<tr><td>2008</td><td>杭州赛区（杭州电子科技大学）</td><td>uTOPia</td><td>王甦易 易超 林子敏</td><td>铜奖（队伍第47名）</td></tr>
<tr><td>2008</td><td>哈尔滨赛区（哈尔滨工程大学）</td><td>GREEDY</td><td>杜晓宇 龚治 于济航</td><td>铜奖（队伍第45名）</td></tr>
<tr><td>2009</td><td>合肥赛区（中国科技大学）</td><td>prayer</td><td>李思源 卓越 陈杉</td><td>优胜奖（队伍第67名）</td></tr>
<tr><td>2009</td><td>合肥赛区（中国科技大学）</td><td>Acapriccio</td><td>王甦易 张思亮 高扬福</td><td>优胜奖（队伍第69名）</td></tr>
<tr><td>2009</td><td>宁波赛区（浙江大学宁波理工学院）</td><td>AlCyoneus</td><td>于济航 林子敏 易超</td><td>银奖（队伍第25名）</td></tr>
<tr><td>2009</td><td>宁波赛区（浙江大学宁波理工学院）</td><td>Spphins</td><td>杨经宇 车丽美 洪涛</td><td>铜奖（队伍第70名）</td></tr>
<tr><td>2009</td><td>上海赛区（东华大学）</td><td>Sphinx</td><td>黄锟 郭斯瑶 龚治</td><td>银奖（队伍第18名）</td></tr>
<tr><td>2009</td><td>上海赛区（东华大学）</td><td>Acapriccio</td><td>王甦易 张思亮 詹钰</td><td>铜奖（队伍第75名）</td></tr>
<tr><td>2009</td><td>武汉赛区（武汉大学）</td><td>AlCyoneus</td><td>于济航 林子敏 易超</td><td>铜奖（队伍第43名）</td></tr>
<tr><td>2009</td><td>武汉赛区（武汉大学）</td><td>prayer</td><td>李思源 卓越 陈杉</td><td>优胜奖（队伍第83名）</td></tr>
<tr><td>2009</td><td>哈尔滨赛区（哈尔滨工业大学）</td><td>Sphinx</td><td>黄锟 郭思瑶 龚治</td><td>金奖（队伍第10名）</td></tr>
<tr><td>2009</td><td>哈尔滨赛区（哈尔滨工业大学）</td><td>Spphins</td><td>杨经宇 车丽美 洪涛</td><td>优胜奖（队伍第82名）</td></tr>
<tr><td>2010</td><td>哈尔滨赛区（哈尔滨工程大学）</td><td>Horizon</td><td>王甦易 易超 陆济川</td><td>金奖（队伍第11名）</td></tr>
<tr><td>2010</td><td>哈尔滨赛区（哈尔滨工程大学）</td><td>OCD</td><td>赵力 马飞龙 王铭康</td><td>铜奖（队伍第39名）</td></tr>
<tr><td>2010</td><td>天津赛区（天津大学）</td><td>Prayer</td><td>李思源 卓越 陈杉</td><td>银奖（队伍第19名）</td></tr>
<tr><td>2010</td><td>天津赛区（天津大学）</td><td>OCD</td><td>赵力 马飞龙 王铭康</td><td>优胜奖（队伍第102名）</td></tr>
<tr><td>2010</td><td>杭州赛区（浙江理工大学）</td><td>GOT</td><td>龚治 林子敏 陈辉</td><td>银奖（队伍第26名）</td></tr>
<tr><td>2010</td><td>成都赛区（四川大学）</td><td>Prayer</td><td>李思源 卓越 陈杉</td><td>银奖（队伍第28名）</td></tr>
<tr><td>2010</td><td>成都赛区（四川大学）</td><td>Foxhound</td><td>唐忆戈 张思亮 詹钰</td><td>优胜奖（队伍第72名）</td></tr>
<tr><td>2010</td><td>福州赛区（福州大学）</td><td>GOT</td><td>龚治 林子敏 陈辉</td><td>银奖（队伍第31名）</td></tr>
<tr><td>2010</td><td>福州赛区（福州大学）</td><td>Horizon</td><td>王甦易 易超 陆济川</td><td>银奖（队伍第36名）</td></tr>
<tr><td>2011</td><td>大连赛区（大连理工大学）</td><td>Thunderbolt</td><td>易超 龚治 马飞龙</td><td>银奖（队伍第30名）</td></tr>
<tr><td>2011</td><td>大连赛区（大连理工大学）</td><td>Attiix</td><td>赵力 王骁 王梦非</td><td>铜奖（队伍第46名）</td></tr>
<tr><td>2011</td><td>大连赛区（大连理工大学）</td><td>MIX</td><td>陈辉 韦添 诸海婷</td><td>优胜奖（队伍第113名）</td></tr>
<tr><td>2011</td><td>上海赛区（复旦大学）</td><td>Young For AC</td><td>李思源 卓越 詹钰</td><td>银奖（队伍第26名）</td></tr>
<tr><td>2011</td><td>北京赛区（北京邮电大学）</td><td>Thunderbolt</td><td>易超 龚治 马飞龙</td><td>银奖（队伍第20名）</td></tr>
<tr><td>2011</td><td>北京赛区（北京邮电大学）</td><td>WTF</td><td>于馨培 黎明明 张伯威</td><td>优胜奖（队伍第90名）</td></tr>
<tr><td>2011</td><td>北京赛区（北京邮电大学）</td><td>MIX</td><td>陈辉 韦添 诸海婷</td><td>优胜奖（队伍第106名）</td></tr>
<tr><td>2011</td><td>成都赛区（成都东软学院）</td><td>Young For AC</td><td>李思源 卓越 詹钰</td><td>银奖（队伍第23名）</td></tr>
<tr><td>2011</td><td>福州赛区（福建师范大学）</td><td>Attiix</td><td>赵力 王骁 王梦非</td><td>银奖（队伍第27名）</td></tr>
<tr><td>2011</td><td>福州赛区（福建师范大学）</td><td>What's BNU?</td><td>易超 龚治 李思源</td><td>金奖*（队伍第10名）</td></tr>
<tr><td>2012</td><td>长春赛区（东北师范大学）</td><td>Random</td><td>易超 龚治 李思源</td><td>金奖（队伍第8名）</td></tr>
<tr><td>2012</td><td>长春赛区（东北师范大学）</td><td>Attiix</td><td>赵力 王骁 王梦非</td><td>银奖（队伍第37名）</td></tr>
<tr><td>2012</td><td>长春赛区（东北师范大学）</td><td>_CD</td><td>龙翔 张伯威 吴浪</td><td>银奖（队伍第52名）</td></tr>
<tr><td>2012</td><td>天津赛区（天津理工大学）</td><td>Sellamoe</td><td>黎明明 于馨培 袁伟舜</td><td>铜奖（队伍第60名）</td></tr>
<tr><td>2012</td><td>天津赛区（天津理工大学）</td><td>CHD</td><td>陈辉 何冬杰 董自鸣</td><td>铜奖（队伍第67名）</td></tr>
<tr><td>2012</td><td>天津赛区（天津理工大学）</td><td>11621</td><td>刘芳 焦璐 盛乔一</td><td>优胜奖（队伍第113名）</td></tr>
<tr><td>2012</td><td>金华赛区（浙江师范大学）</td><td>_CD</td><td>龙翔 张伯威 吴浪</td><td>银奖（队伍第17名）</td></tr>
<tr><td>2012</td><td>杭州赛区（浙江理工大学）</td><td>Sellamoe</td><td>黎明明 于馨培 袁伟舜</td><td>铜奖（队伍第59名）</td></tr>
<tr><td>2012</td><td>成都赛区（成都东软学院）</td><td>Random</td><td>易超 龚治 李思源</td><td>银奖（队伍第21名）</td></tr>
<tr><td>2012</td><td>成都赛区（成都东软学院）</td><td>Attiix</td><td>赵力 王骁 王梦非</td><td>铜奖（队伍第70名）</td></tr>
                </tbody>
              </table>
              <p style="align:right;text-align:right;padding:0;">
                注：“*”号表示友情参赛，不计入ICPC排名。
              </p>
            </div>
            <div id="trainingplan">
              <p>以下是2012年暑期的训练计划（<b>暂定，随时可能修改</b>），放在了Google Calendar上，加载较慢，看不见的同学请点<a href="https://www.google.com/calendar/embed?src=7v94d82vuutb9cd3m5rjphghoo%40group.calendar.google.com&ctz=Asia/Shanghai" target="_blank"><b>&gt;&gt;这里&lt;&lt;</b></a>查看。另外<b style="color:red">请务必仔细阅读页面下方的说明。</b></p>
              <div id="calendar"></div>
              <div>
                一些说明：
                <ol>
                  <li>实践周日期为暂定日期，确切日期还需等待学院通知。</li>
                  <li>参加暑期训练的每位同学都可以选择7.7-7.28之间的任意两至三周休息。</li>
                  <li>专题训练除了讲解以外，简要解题报告以及参考程序均会在专题训练结束后给出。</li>
                  <li>机房自由训练期间同学可以来电子楼机房做题，或者参加同时进行的一二队组队训练。</li>
                  <li>7.29-7.31期间将进行三场积分赛，题目由一二队准备，积分规则将提前2天给出。</li>
                  <li>我们将根据积分赛的结果顺序进行组队，在参考各同学的组队意向的同时进行部分调整。</li>
                  <li>8月进行进行组队赛，我们将会对每次组队赛进行积分，最终结合暑期组队赛积分（20%-40%）以及5场网络预赛的表现情况（60%-80%）决定参加区域赛的队伍。具体规则将在8月前给出。</li>
                  <li>组队赛期间，我们将根据队伍通过题目的情况以及排名情况来报销各队的晚餐费用。具体规则也将在8月前给出。</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div id="one_content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<script type='text/javascript' src='fullcalendar/fullcalendar.js'></script>
<script type='text/javascript' src='fullcalendar/gcal.js'></script>
<script type="text/javascript">
$("#ourteam").addClass("tab_selected");
$("#infotabs").tabs();
$("#tplan").click(function() {
    $('#calendar').html("").fullCalendar({
        events: 'http://www.google.com/calendar/feeds/7v94d82vuutb9cd3m5rjphghoo%40group.calendar.google.com/public/basic',
        className: 'gcal-event',
        theme: true,
        firstDay: 1,
        year: 2012
    });
});

if (self.document.location.hash.substring(1)=="trainingplan") $("#tplan").click();

$('#membertable').dataTable( {
    "bProcessing": true,
    "bJQueryUI": true,
    "sDom": '<"H"pf>rt<"F"il>',
    "sPaginationType": "full_numbers" ,
    "aaSorting": [ [1, 'asc'] ],
    "iDisplayLength": 100,
    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        $(nRow).children().each(function(){$(this).addClass('gradeC');});
        return nRow;
    },
    "fnDrawCallback": function(){
        $('.infotable td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
        $('.infotable td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
        $('.infotable .sorting_1').each(function(){$(this).removeClass('gradeC');$(this).addClass('gradeCC');});
    },
    "iDisplayStart": 0
});
$('#honortable').dataTable( {
    "bProcessing": true,
    "bJQueryUI": true,
    "sDom": '<"H"pf>rt<"F"il>',
    "sPaginationType": "full_numbers" ,
    "aaSorting": [ [0,'desc'] ],
    "iDisplayLength": 100,
    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        $(nRow).children().each(function(){$(this).addClass('gradeC');});
        return nRow;
    },
    "fnDrawCallback": function(){
        $('.infotable td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
        $('.infotable td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
        $('.infotable .sorting_1').each(function(){$(this).removeClass('gradeC');$(this).addClass('gradeCC');});
    },
    "iDisplayStart": 0
});

</script>

<?php
    include("end.php");
?>
