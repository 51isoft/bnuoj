// modified from http://acm.scs.bupt.cn/hefei/hefei.js
/*
String.prototype.trim= function(){  
	// 用正则表达式将前后空格  
	// 用空字符串替代。  
	return this.replace(/(^\s*)|(\s*$)/g, "");  
}
*/


/**
 * enables highlight  rows in data tables
 */

function table_Init(container) {
    // for every table row ...
    try{
        var rows = container.rows;
        for ( var i = 0; i < rows.length; i++ ) {
    		if (i % 2 == 1) {
    			rows[i].className = 'odd';
    		}
    		else {
    			rows[i].className = 'even';
    		}
		
        // ... add event listeners ...
        // ... to highlight the row on mouseover ...
        //if ( navigator.appName == 'Microsoft Internet Explorer' ) {
        // but only for IE, other browsers are handled by :hover in css
        rows[i].onmouseover = function() {
            this.className += ' hover';
        }
        rows[i].onmouseout = function() {
            this.className = this.className.replace( ' hover', '' );
        }
//        }
        
    }
}
catch (err){
	alert(err.description);
}
}
