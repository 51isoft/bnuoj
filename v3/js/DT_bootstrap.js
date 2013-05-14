/* Set the defaults for DataTables initialisation */
$.extend( true, $.fn.dataTable.defaults, {
	"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
	"sPaginationType": "bootstrap",
	"oLanguage": {
		"sLengthMenu": "_MENU_ records per page"
	}
} );


/* Default class modification */
$.extend( $.fn.dataTableExt.oStdClasses, {
	"sWrapper": "dataTables_wrapper form-inline"
} );


/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
{
	return {
		"iStart":         oSettings._iDisplayStart,
		"iEnd":           oSettings.fnDisplayEnd(),
		"iLength":        oSettings._iDisplayLength,
		"iTotal":         oSettings.fnRecordsTotal(),
		"iFilteredTotal": oSettings.fnRecordsDisplay(),
		"iPage":          oSettings._iDisplayLength === -1 ?
			0 : Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
		"iTotalPages":    oSettings._iDisplayLength === -1 ?
			0 : Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
	};
};


/* Bootstrap style pagination control */
$.extend( $.fn.dataTableExt.oPagination, {
	"full_numbers": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};

			$(nPaging).addClass('pagination').append(
				'<ul>'+
					'<li class="first disabled"><a href="#">&laquo; '+oLang.sFirst+'</a></li>'+
					'<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
					'<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
					'<li class="last disabled"><a href="#">'+oLang.sLast+' &raquo; </a></li>'+
				'</ul>'
			);
			var els = $('a', nPaging);
			$(els[0]).bind( 'click.DT', { action: "first" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[2]).bind( 'click.DT', { action: "next" }, fnClickHandler );
			$(els[3]).bind( 'click.DT', { action: "last" }, fnClickHandler );
		},

		"fnUpdate": function ( oSettings, fnDraw ) {
			var iListLength = 5;
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var an = oSettings.aanFeatures.p;
			var i, ien, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

			if ( oPaging.iTotalPages < iListLength) {
				iStart = 1;
				iEnd = oPaging.iTotalPages;
			}
			else if ( oPaging.iPage <= iHalf ) {
				iStart = 1;
				iEnd = iListLength;
			} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
				iStart = oPaging.iTotalPages - iListLength + 1;
				iEnd = oPaging.iTotalPages;
			} else {
				iStart = oPaging.iPage - iHalf + 1;
				iEnd = iStart + iListLength - 1;
			}

			for ( i=0, ien=an.length ; i<ien ; i++ ) {
				// Remove the middle elements
				$('li:gt(1)', an[i]).filter(':not(.next)').filter(":not(.last)").remove();

				// Add the new list items and their event handlers
				for ( j=iStart ; j<=iEnd ; j++ ) {
					sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
					$('<li '+sClass+'><a href="#">'+j+'</a></li>')
						.insertBefore( $('li.next', an[i])[0] )
						.bind('click', function (e) {
							e.preventDefault();
							oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
							$(oSettings.oInstance).trigger('page', oSettings);
							fnDraw( oSettings );
						} );
				}

				// Add / remove disabled classes from the static elements
				if ( oPaging.iPage === 0 ) {
					$('li.first,li.prev', an[i]).addClass('disabled');
				} else {
					$('li.first,li.prev', an[i]).removeClass('disabled');
				}

				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
					$('li.last,li.next', an[i]).addClass('disabled');
				} else {
					$('li.last,li.next', an[i]).removeClass('disabled');
				}
			}
		}
	},
	"two_buttons": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};

			$(nPaging).addClass('pagination').append(
				'<ul>'+
					'<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
					'<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
				'</ul>'
			);
			var els = $('a', nPaging);
			$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
		},

		"fnUpdate": function ( oSettings, fnDraw ) {
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var an = oSettings.aanFeatures.p;

			if ( oPaging.iPage === 0 ) {
				$('li.prev',an).addClass('disabled');
			} else {
				$('li.prev',an).removeClass('disabled');
			}

			if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
				$('li.next',an).addClass('disabled');
			} else {
				$('li.next',an).removeClass('disabled');
			}
		}
	},
	"four_buttons": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};

			$(nPaging).addClass('pagination').append(
				'<ul>'+
					'<li class="first disabled"><a href="#">&laquo; '+oLang.sFirst+'</a></li>'+
					'<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
					'<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
					'<li class="last disabled"><a href="#">'+oLang.sLast+' &raquo; </a></li>'+
				'</ul>'
			);
			var els = $('a', nPaging);
			$(els[0]).bind( 'click.DT', { action: "first" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[2]).bind( 'click.DT', { action: "next" }, fnClickHandler );
			$(els[3]).bind( 'click.DT', { action: "last" }, fnClickHandler );
		},

		"fnUpdate": function ( oSettings, fnDraw ) {
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var an = oSettings.aanFeatures.p;

			if ( oPaging.iPage === 0 ) {
				$('li.prev,li.first',an).addClass('disabled');
			} else {
				$('li.prev,li.first',an).removeClass('disabled');
			}

			if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
				$('li.next,li.last',an).addClass('disabled');
			} else {
				$('li.next,li.last',an).removeClass('disabled');
			}
		}
	},
	"input": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};

			$(nPaging).addClass('pagination').append(
				'<div class="input-prepend input-append">' +
				  '<button class="btn first disabled">&laquo;<span class="hidden-phone"> '+oLang.sFirst+'</span></button>' +
				  '<button class="btn prev disabled">&larr;<span class="hidden-phone"> '+oLang.sPrevious+'</span></button>' +
				  '<input type="text" class="input-mini" id="appendedPrependedInput" style="text-align:center"><span class="add-on" id="totalp" val="0"> of 0</span>' +
				  '<button class="btn next disabled"><span class="hidden-phone">'+oLang.sNext+' </span>&rarr; </button>' +
				  '<button class="btn last disabled"><span class="hidden-phone">'+oLang.sLast+' </span>&raquo; </button>' +
				'</div>'
			);
			var els = $('button', nPaging);
			$(els[0]).bind( 'click.DT', { action: "first" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[2]).bind( 'click.DT', { action: "next" }, fnClickHandler );
			$(els[3]).bind( 'click.DT', { action: "last" }, fnClickHandler );

			$('input',nPaging).keyup( function (e) {
              
	            if ( e.which == 38 || e.which == 39 )
	            {
	                this.value++;
	            }
	            else if ( (e.which == 37 || e.which == 40) && this.value > 1 )
	            {
	                this.value--;
	            }
	              
	            if ( this.value == "" || this.value.match(/[^0-9]/) )
	            {
	                return;
	            }
	              
	            var iNewStart = oSettings._iDisplayLength * (this.value - 1);
	            if ( iNewStart > oSettings.fnRecordsDisplay() )
	            {
	                /* Display overrun */
	                oSettings._iDisplayStart = (Math.ceil((oSettings.fnRecordsDisplay()-1) /
	                    oSettings._iDisplayLength)-1) * oSettings._iDisplayLength;
	                $(oSettings.oInstance).trigger('page', oSettings);
	                fnCallbackDraw( oSettings );
	                return;
	            }
	              
	            oSettings._iDisplayStart = iNewStart;
	            $(oSettings.oInstance).trigger('page', oSettings);
	            fnDraw( oSettings );
	        } );

		},
		"fnUpdate": function ( oSettings, fnDraw )
	    {
	        if ( !oSettings.aanFeatures.p )
	        {
	            return;
	        }
	        var iPages = Math.ceil((oSettings.fnRecordsDisplay()) / oSettings._iDisplayLength);
	        var iCurrentPage = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1;
	        var an = oSettings.aanFeatures.p;
	        $("input",an).val(iCurrentPage);
	        $("#totalp",an).text(" of "+iPages);

	        if ( iCurrentPage === 1 ) {
				$('button.prev,button.first',an).addClass('disabled');
			} else {
				$('button.prev,button.first',an).removeClass('disabled');
			}

			if ( iPages === iCurrentPage || iPages === 0 ) {
				$('button.next,button.last',an).addClass('disabled');
			} else {
				$('button.next,button.last',an).removeClass('disabled');
			}

	    }
	}
} );


/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
 */
if ( $.fn.DataTable.TableTools ) {
	// Set the classes that TableTools uses to something suitable for Bootstrap
	$.extend( true, $.fn.DataTable.TableTools.classes, {
		"container": "DTTT btn-group",
		"buttons": {
			"normal": "btn",
			"disabled": "disabled"
		},
		"collection": {
			"container": "DTTT_dropdown dropdown-menu",
			"buttons": {
				"normal": "",
				"disabled": "disabled"
			}
		},
		"print": {
			"info": "DTTT_print_info modal"
		},
		"select": {
			"row": "active"
		}
	} );

	// Have the collection use a bootstrap compatible dropdown
	$.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
		"collection": {
			"container": "ul",
			"button": "li",
			"liner": "a"
		}
	} );
}

