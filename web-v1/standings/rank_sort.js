 
function ieOrFireFox(ob){     
     if (ob.textContent != null)     
     return ob.textContent;     
     var s = ob.innerText;     
     return s.substring(0, s.length);     
}     

function get_time(unix_time)
{
//     var pos1,val;
     unix_time=convert(unix_time);
//     pos1=unix_time.indexOf('(',0);
//     val=unix_time.substr(pos1,unix_time.length-pos1-1);
//     unix_time=unix_time(0,pos1);
//     unix_time=convert(unix_time);
     first = parseInt(unix_time/3600);
     mid = parseInt((unix_time-first*3600)/60);
     last = unix_time%60;
     return first+":"+mid+":"+last;
}

function split(unix_time) {
     var pos1,val;
//     unix_time=convert(unix_time);
     pos1=unix_time.indexOf('(',0);
//     if (pos1==0) return -9999;
     val=unix_time.substr(pos1,unix_time.length-pos1-1);
     unix_time=unix_time.substr(0,pos1);
     unix_time=convert(unix_time);
     return unix_time;
}

function get_ptime(unix_time)
{
     var pos1,val;
//     unix_time=convert(unix_time);
     pos1=unix_time.indexOf('(',0);
     val=unix_time.substr(pos1,unix_time.length-pos1);
     unix_time=unix_time.substr(0,pos1);
     unix_time=convert(unix_time);
     first = parseInt(unix_time/3600);
     mid = parseInt((unix_time-first*3600)/60);
     last = unix_time%60;
     return first+":"+mid+":"+last+val;
}

function changeDisplay(tableId,id) {
    var table = document.getElementById(tableId);
    var tbody = table.tBodies[0];
    var colRows = tbody.rows;
    var pd=1-id;
    for (var i=0;i<colRows.length;i++) {
        colRows[i].cells[1+id].style.display="";
        colRows[i].cells[1+pd].style.display="none";
    }
}

function hide(tableId,row,lastCol) {
    var table = document.getElementById(tableId);
    var tbody = table.tBodies[0];
    var colRows = tbody.rows;
    var pd=0;
    for (var i=1;i<colRows.length;i++) {
        if (colRows[i].style.display=="none") {
            pd=1;
            break;
        }
    }
    if (pd==1) {
        for (var i=1;i<colRows.length;i++) {
            colRows[i].style.display="";
            if (i%2==1) colRows[i].className="odd";
            else colRows[i].className="even";
        }
        return;
    }
    var j=0;
    for (var i=1;i<colRows.length;i++) {
        if (j%2==0) colRows[i].className="odd";
        else colRows[i].className="even";
        if (colRows[i].cells[lastCol].innerHTML.toLowerCase()!="<a href=\"contest_standing.php?cid="+row+"\">"+row+"</a>") {
            colRows[i].style.display="none";
        }
        else j++;
    }
}
      
function sortAble(tableId, lastCol){     
    var table = document.getElementById(tableId);     
    var tbody = table.tBodies[0];     
    var colRows = tbody.rows;     
    var aTrs = new Array;
    var mint = new Array;
//    var g;
    for (var j=3;j<=lastCol;j++) mint[j]=-9999;
    for (var i=0; i < colRows.length-1; i++) {     
         aTrs[i] = colRows[i+1];     
         for (var j=3;j<lastCol;j++) {
//            g=split(ieOrFireFox(aTrs[i].cells[j]));
//            if (g<0) continue;
            if (aTrs[i].cells[j].className!="fb"&&aTrs[i].cells[j].className!="ac") continue;
            if (mint[j]<0||mint[j]>split(ieOrFireFox(aTrs[i].cells[j]))) mint[j]=split(ieOrFireFox(aTrs[i].cells[j]));
         }
    }     
                                                 
    aTrs.sort(compareEle(lastCol));
    
    var oFragment = document.createDocumentFragment();     
                    
    for (var i=0; i < aTrs.length; i++) {     
        aTrs[i].cells[0].innerHTML=i+1;
        aTrs[i].cells[lastCol].innerHTML=get_time(convert(ieOrFireFox(aTrs[i].cells[lastCol])));
        if (i%2==1) aTrs[i].className="odd";
        else aTrs[i].className="even";
        for (var j=4;j<lastCol;j++) {
            if (mint[j]==convert(ieOrFireFox(aTrs[i].cells[j]))) aTrs[i].cells[j].className="fb";
            if (aTrs[i].cells[j].className=="fb"||aTrs[i].cells[j].className=="ac") aTrs[i].cells[j].innerHTML=get_ptime(ieOrFireFox(aTrs[i].cells[j]));
        }
        oFragment.appendChild(aTrs[i]);
    }                     
    tbody.appendChild(oFragment);     
 }     
 //将列的类型转化成相应的可以排列的数据类型     
function convert(sValue) {     
    return parseInt(sValue);  
}     
                  
 //排序函数，iCol表示列索引，dataType表示该列的数据类型     
function compareEle(iCol) {     
    return function (oTR1, oTR2) {     
         var vValue11 = convert(ieOrFireFox(oTR1.cells[3]));
         var vValue12 = convert(ieOrFireFox(oTR1.cells[iCol])); 
         var vValue21 = convert(ieOrFireFox(oTR2.cells[3]));
         var vValue22 = convert(ieOrFireFox(oTR2.cells[iCol]));

         if (vValue11 > vValue21) {     
             return -1;     
         } else if (vValue11 < vValue21) {     
             return 1;     
         } else {
             if (vValue12 < vValue22) return -1;
             else if (vValue12 > vValue22) return 1;
             else return 0;     
         }     
    };     
 }    
