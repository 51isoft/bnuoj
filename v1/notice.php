<?php
include("header.php");
?>

<center><h2><font color='darkblue'>如何避免由于编译器差别带来的错误</font></h2></center>

<div>

<p align="justify"><font size="4" style="FONT-FAMILY: monospace" face="Calibri">1、判题系统使用的是G++编译器，和普通使用的TC，VC都有所不同，建议大家使用Dev C++作为IDE，或者用TC和VC写代码，提交前使用Dev C++编译，预防编译错误。</p>
<p>提交C语言代码最好使用G++，G++兼容C和C++。C的代码可以用GCC也可用G++提交，而C++的代码不能够用GCC提交，只能用G++。</p>
<p>2、G++包含库的时候不要使用iostream.h，应该使用&lt;iostream></p>
<p>有些常用的函数所在的库会被VC自动包含，但是不会被G++包含。</p>
<p>例如memset，strlen，strstr等和字符串处理相关的函数在库&lt;cstring>中；abs在&lt;cstdlib>中；fabs，sin，sqrt等数学函数在&lt;cmath>中</p>
<p>为了避免CE，大家可以索性一次性把所有可能用到的库都给包含上。</p>
<p>C++注意要使用using namespace std;</p>
<p>3、关于整数，在G++下，long和int是完全一样的</p>
<p>4、浮点数:使用double以减小误差，格式控制字符串是"%lf"(不要使用float)。浮点数的相等不能直接用==来判断，需要使用实数判等。</p>
<p>5、标识符，G++中有一些在VC中没有的保留字，比如and，or，not等等，使用这些保留字作为标识符会产生CE。</p>
<p>6、对于输入输出，建议不要使用cin和cout，这种输入输出方式会比较慢，在数据量大的时候容易引起超时。</p>
<p>7、关于main函数，定义一定要是int型，并记得加上return 0。</p>
<p>int main(){... return 0; }</p>
<p>8、当使用类似于for (int i=0;i&lt;n;i++)这种形式对循环变量进行定义时，注意循环变量的作用域只在这个循环内。</p>
<p>9、输入法在敲代码和提交代码的时候一定要确保关闭，代码中(除了注释部分)有全角字符会引起CE，注释建议使用英文。</p>
<p>10、使用STL的同学请注意例如下面的声明是会引起CE的</p>
<p>vector&lt;vector&lt;int>> adj; 应该改为 vector&lt;vector&lt;int> > adj;</p>
<p>连续两个左右箭头间要一个空格。</font></p>

</div>


<?php
include("footer.php");
?>
