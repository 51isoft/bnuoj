<?php
$pagetitle="Frequently Asked Question";
include("header.php");
?>
<div>

  <p align="justify"><font color="#0000FF" size="4" face="Calibri">1.Q: 
Where does my program input from and output to?</font></p>
<p align="justify"><font size="4" face="Calibri">A: </font><font color="#FF0000" size="4" face="Calibri">Your 
program should always input from stdin (standard input) and output to 
stdout (standard output).</font><font size="4" face="Calibri"> For example, 
you can use scanf in C or cin in C++ to read, and printf in C or cout 
in C++ to write. User programs are NOT allowed to open and read from/write 
to any file. </font> <br></p>

<p align="justify"><font size="4" face="Calibri">More should be noted 
about I/O operations in C++. Due to their complex underlying implementation 
models, </font><font color="#FF0000" size="4" face="Calibri">cin and 
cout are comparatively slower than scanf and printf. If a problem has 
huge input, using cin and cout will possibly lead to Time Limit Exceed.</font> <br>
</p>
<p align="justify"><font color="#0000FF" size="4" face="Calibri">2.Q: 
What are the compilers used by the judge? What should I notice when 
using these compliers?</font></p>
<p align="justify"><font size="4" face="Calibri">A: GCC 4.3.3(Debian 
4.3.3-5) are provided for G++/GCC.The compilation options are</font> <br>
</p>
<p align="justify"><font color="#00B050" size="4" face="Calibri">gcc 
runid.c -o runid -O -fno-asm -Wall</font></p>
<p align="justify"><font color="#00B050" size="4" face="Calibri">g++ 
runid.cpp -o runid -O -fno-asm -Wall</font> <br></p>
<p align="justify"><font size="4" face="Calibri">Notice the difference 
between GCC 4.3.3 and other compilers to avoid Compile Error when using 
G++/GCC. </font> <br></p>

<ul type="DISC">
  <li><font color="#FF0000" size="4" face="Calibri">The return 
  type of the main function must be int, otherwise it will cause Compile 
  Error.</font></li>
  <li><font color="#FF0000" size="4" face="Calibri">Remember 
  to include &lt;cstdlib&gt; and &lt;cstring&gt; when using some functions 
  in these library,GCC 4.3.3 won’t include them automatically when only 
  &lt;iostream&gt; is included.</font></li>
  <li><font color="#FF0000" size="4" face="Calibri">For 64-bit 
  integers,only long long is supported.You should use “%lld” as the 
  format string when using scanf or printf.</font></li>

</ul>
<p align="justify"><font size="4" face="Calibri"> </font></p>
<p align="justify"><font size="4" face="Calibri">JDK 1.6 are provided 
for JAVA.The compilation option are</font> <br></p>
<p align="justify"><font color="#00B050" size="4" face="Calibri">javac 
-g:none -Xlint Main.java</font></p>
<p align="justify"><font color="#00B050" size="4" face="Calibri">java 
-client Main</font> <br></p>
<p align="justify"><font size="4" face="Calibri">A Java program must 
be submitted as a single source file. Apart from complying with restrictions 
imposed on all submitted programs, </font><font color="#FF0000" size="4" face="Calibri">it 
must start execution in a static method named main in a class named 
Main</font><font size="4" face="Calibri">, otherwise Compile Error will 
be caused.</font> <br></p>
<p align="justify"><font color="#FF0000" size="4" face="Calibri">Java 
programs are allowed to run for THREE TIMES the time limit of GCC/G++, 
ans to use TWICE the memory limit of GCC/G++.</font> <br></p>

<p align="justify"><font color="#0000FF" size="4" face="Calibri">3.Q: 
What are the meanings of the judge&#39;s replies?</font></p>
<p align="justify"><font size="4" face="Calibri">A: Here is a list of 
the judge&#39;s replies with their common abbreviations and exact meanings:</font> <br>
</p>
<p align="justify"><font size="4" face="Calibri"><b>Judging/Waiting</b>: Your 
program is being judged or waiting to be judged.</font> <br></p>
<p align="justify"><font size="4" face="Calibri"><b>Rejudging</b>: Your 
program is waiting to be rejudged.</font> <br></p>
<p align="justify"><font color="#0000FF" size="4" face="Calibri"><b>Accepted 
(AC)</b></font><font size="4" face="Calibri">: Congratulations! Your 
program has produced the correct output!</font> <br></p>
<p align="justify"><font color="#FF0000" size="4" face="Calibri"><b>Presentation Error 
(PE)</b></font><font size="4" face="Calibri"> : Output Format Error.Your output is the same as the standard output 
when all blank characters are ignored. Check your output for spaces, 
blank lines, etc. against the problem output specification.</font> <br>

</p>
<p align="justify"><font color="#FF0000" size="4" face="Calibri"><b>Wrong 
Answer (WA)</b> </font><font size="4" face="Calibri">: Correct solution 
not reached for the inputs. The inputs and outputs that we use to test 
the programs are not public. Some problems with special judge may not 
reply &quot;Presentation Error&quot;, replaced by &quot;Wrong Answer&quot;.</font> <br>
</p>
<p align="justify"><font color="#FF0000" size="4" face="Calibri"><b>Runtime 
Error (RE)</b></font><font size="4" face="Calibri"><b> </b>
: Your program failed during the execution (segmentation fault, floating 
point exception...). The exact cause is reported except Java.</font> <br>

</p>
<p align="justify"><font color="#FF0000" size="4" face="Calibri"><b>Time 
Limit Exceeded (TLE) </b></font><font size="4" face="Calibri">: Your 
program tried to run with too much CPU time.</font><font color="#FF0000" size="4" face="Calibri"> 
Java programs are allowed to run for THREE TIMES the time limit of GCC/G++.</font> <br>
</p>
<p align="justify"><font color="#FF0000" size="4" face="Calibri"><b>Memory 
Limit Exceeded (MLE) </b></font><font size="4" face="Calibri">: Your 
program tried to use more memory than the judge default settings. </font><font color="#FF0000" size="4" face="Calibri">Java programs are allowed to 
use TWICE the memory limit of GCC/G++.</font> <br></p>
<p align="justify"><font color="#FF0000" size="4" face="Calibri"><b>Output 
Limit Exceeded (OLE)</b></font><font size="4" face="Calibri"><b>:</b> 
Your program tried to write too much. This usually occurs if it goes 
into an infinite loop. </font><font color="#FF0000" size="4" face="Calibri">The 
output limit is 32MB.</font> <br></p>

<p align="justify"><font color="#00B050" size="4" face="Calibri"><b>Compile 
Error (CE)</b></font><font size="4" face="Calibri"><b> : </b>
The compiler could not compile your program. Of course, warning messages 
are not error messages. Click the link at the judge reply to see the 
actual error message.</font> <br></p>
<p align="justify"><font size="4" face="Calibri"><b>Restricted Function 
(RF):</b> Your program tried to call restricted functions. For example, 
maybe you have tried to open a file which is forbidden on OJ. It may 
also caused by Runtime Error (e.g. maybe a pointer point to wrong funtion), 
just consider it as Runtime Error in this situation.</font> <br>
</p>
<p align="justify"><font color="#0000FF" size="4" face="Calibri">4.Q: 
What does the phrase &quot;Special Judge&quot; under the problem title 
means?</font></p>

<p align="justify"><font size="4" face="Calibri">A: When a problem has 
multiple acceptable answers, a special judge program is needed. The 
special judge program uses the input data and some other information 
to check your program&#39;s output and returns the result to the judge.Be 
carefull that </font><font color="#FF0000" size="4" face="Calibri">Format 
error may cause WA not PE for such problems.</font> <br></p>
<p align="justify"><font color="#0000FF" size="4" face="Calibri">5.Q:My 
program can get right answer for all data in the problem with my computer,but 
when I submit it,it get WA.Why?</font> <br></p>
<p align="justify"><font size="4" face="Calibri">A:After your source 
code has been submitted,judgers will use data which is not public to 
test your program.If you get wrong output for these data,Judgers will 
reply Wrong Answer.So your program must have some bugs,please check 
your program.</font> <br></p>
<p align="justify"><font color="#0000FF" size="4" face="Calibri">6.Q:Some 
problems which was Accepted when I submit it turn to be Wrong Answer,Why?</font></p>
<p align="justify"><font size="4" face="Calibri">A:When judgers find 
some bugs in test data,they may rejudge all submits.But </font><font color="#FF0000" size="4" face="Calibri">in a contest,problems never 
be rejudged from AC to WA. </font></p>

</div>


<?php
include("footer.php");
?>
