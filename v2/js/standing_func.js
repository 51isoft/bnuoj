function ieOrFireFox(ob){
     if (ob.textContent != null)
     return ob.textContent;
     var s = ob.innerText;
     return s.substring(0, s.length);
}

function get_time(unix_time)
{
     unix_time=convert(unix_time);
     first = parseInt(unix_time/3600);
     mid = parseInt((unix_time-first*3600)/60);
     last = unix_time%60;
     return first+":"+mid+":"+last;
}

function split(unix_time) {
     var pos1,val;
     pos1=unix_time.indexOf('(',0);
     val=unix_time.substr(pos1,unix_time.length-pos1-1);
     unix_time=unix_time.substr(0,pos1);
     unix_time=convert(unix_time);
     return unix_time;
}

function get_ptime(unix_time)
{
     var pos1,val;
     pos1=unix_time.indexOf('(',0);
     val=unix_time.substr(pos1,unix_time.length-pos1);
     unix_time=unix_time.substr(0,pos1);
     unix_time=convert(unix_time);
     first = parseInt(unix_time/3600);
     mid = parseInt((unix_time-first*3600)/60);
     last = unix_time%60;
     return first+":"+mid+":"+last+val;
}

function convert(sValue) {
    return parseInt(sValue);
}

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

var mint = new Array;

function sortAble(table){
	if (table==null) return;
    var tbody = table.tBodies[0];
    var colRows = tbody.rows;
    var aTrs = new Array;
    var lastCol=table.rows.item(0).cells.length-3;
    for (var j=4;j<lastCol;j++) mint[j]=-9999;
    for (var i=0; i < colRows.length; i++) {
         aTrs[i] = colRows[i];
         for (var j=4;j<lastCol;j++) {
            if (ieOrFireFox(aTrs[i].cells[j])==""||ieOrFireFox(aTrs[i].cells[j]).substr(0,1)=="(") continue;
            if (mint[j]<0||mint[j]>split(ieOrFireFox(aTrs[i].cells[j]))) mint[j]=split(ieOrFireFox(aTrs[i].cells[j]));
         }
    }

    aTrs.sort(compareEle(lastCol));
   
    var oFragment = document.createDocumentFragment();

    for (var i=0; i < aTrs.length; i++) {
        aTrs[i].cells[0].innerHTML=i+1;
        aTrs[i].cells[lastCol].innerHTML=get_time(convert(ieOrFireFox(aTrs[i].cells[lastCol])));
        for (var j=4;j<lastCol;j++) {
//            alert(ieOrFireFox(aTrs[i].cells[j]).substr(0,1));
            if (ieOrFireFox(aTrs[i].cells[j]).substr(0,1)=="(") aTrs[i].cells[j].className="notac_stat";
            if (ieOrFireFox(aTrs[i].cells[j])==""||ieOrFireFox(aTrs[i].cells[j]).substr(0,1)=='(') continue;
            if (mint[j]==convert(ieOrFireFox(aTrs[i].cells[j]))) aTrs[i].cells[j].className="acfb_stat";
            else aTrs[i].cells[j].className="ac_stat";
            aTrs[i].cells[j].innerHTML=get_ptime(ieOrFireFox(aTrs[i].cells[j]));
        }
        oFragment.appendChild(aTrs[i]);
    }
    tbody.appendChild(oFragment);
}

