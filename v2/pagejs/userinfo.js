        function striptags(a) {
            return a.replace(/(<([^>]+)>)/ig,"");
        }
        Highcharts.visualize = function(table, options) {
            // the data series
            options.series = [{
                type: 'pie',
                name: '',
                data: []
            }];
            var flag=false;
//            options.series.data=[];
            jQuery('tr', table).each( function(i) {
                if (i>0) {
                    var tr = this;
                    options.series[0].data.push([]);
                    jQuery('th, td', tr).each( function(j) {
                        if (j==1) options.series[0].data[i-1].push(parseFloat(striptags(this.innerHTML)));
                        else options.series[0].data[i-1].push(this.innerHTML);
                    });
                }
                else if (striptags(jQuery('td',this)[0].innerHTML)=="0") {
                    flag=true;
                }
            });
            if (flag) {
                options.series[0].data[9][0]="Total";
                options.series[0].data[9][1]=1;
            }
            var chart = new Highcharts.Chart(options);
        }
    
        // On document ready, call visualize on the datatable.
        jQuery(document).ready(function() {         
            var table = document.getElementById('userstat');
            var options = {
               chart: {
                   renderTo: 'userpie',
                   plotBackgroundColor: null,
                   plotBorderWidth: null,
                   plotShadow: false
               },
               title: {
                   text: 'User Statistics'
               },
               tooltip: {
                   formatter: function() {
                       return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
                   }
               },
               plotOptions: {
                   pie: {
                       allowPointSelect: true,
                       cursor: 'pointer',
//                       showInLegend: true,
                       dataLabels: {
                           enabled: false
                       }
                   }
               }
            };
            Highcharts.visualize(table, options);
        });

$( "button, a.button, input:submit" ).button();
$ ("#showac").click(function() {
    $(this).hide();
    $("#userac").show("blind",1000,function(){ $("#hideac").show(); });
});
$ ("#hideac").click(function() {
    $(this).hide();
    $("#userac").hide("blind",1000,function(){ $("#showac").show(); });
});
$ ("#compareform").submit(function() {
    var target=$("div#compareinfo");
    target.html('<img src="style/ajax-loader.gif" /> Loading...');
    target.show();
    $("#hidecompare").hide();
    $("#compare").hide();
    $.get('compare.php',{ name1: nametoc , name2:$("#user2").attr("value") }, function(data) {
        target.hide("blind","easeInQuint",300);
        target.html(data);
        target.show("blind",1000,function(){ $("#hidecompare").show(); $("#compare").show();});
    });
    return false;
});
$ ("#hidecompare").click(function() {
    $(this).hide();
    $("#compare").hide();
    $("#compareinfo").hide("blind",300,function() { $("#compare").show(); });
});

if (getURLPara("name")==$.cookie("username")) $("#userspace").addClass("tab_selected");
