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
            var table = document.getElementById('probstat');
            var options = {
               chart: {
                   renderTo: 'probpie',
                   plotBackgroundColor: null,
                   plotBorderWidth: null,
                   plotShadow: false
               },
               title: {
                   text: 'Problem Statistics'
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

    var oTable = $('#pleader').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bJQueryUI": true,
        "sAjaxSource": "problem_leader.php?pid="+ppid,
/*        "fnServerParams": function ( aoData ) {
            aoData.push( { "pid": ppid } );
        },*/
        "oLanguage": {
            "oPaginate": {
                "sFirst": "&lt;&lt;",
                "sPrevious": "&lt;",
                "sNext": "&gt;",
                "sLast": "&gt;&gt;"
            },
            "sEmptyTable": "No one solved this, yet."
        },
        "sDom": '<"H"p>rt<"F"i>',
        "sPaginationType": "full_numbers" ,
        "iDisplayLength": pstatperpage,
        "bLengthChange": false,
        "aaSorting": [ [4,'asc'],[5,'asc'],[7,'asc'] ],
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0,1,2,3,6 ] }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            $(nRow).children().each(function(){$(this).addClass('gradeC');});
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#pleader td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#pleader td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
        },
        "iDisplayStart": 0
    } );

$("#problem").addClass("tab_selected");

