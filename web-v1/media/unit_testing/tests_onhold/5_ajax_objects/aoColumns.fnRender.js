// DATA_TEMPLATE: empty_table
oTest.fnStart( "aoColumns.fnRender" );

$(document).ready( function () {
	/* Check the default */
	var mTmp = 0;
	var oTable = $('#example').dataTable( {
		"sAjaxSource": "../../../examples/ajax/sources/objects.txt",
		"aoColumns": [
			{ "mDataProp": "engine" },
			{
				"mDataProp": "browser",
				"fnRender": function (a) {
					mTmp++;
					return a.aData['browser'];
				}
			},
			{ "mDataProp": "platform" },
			{ "mDataProp": "version" },
			{ "mDataProp": "grade" }
		]
	} );
	var oSettings = oTable.fnSettings();
	
	oTest.fnWaitTest( 
		"Single column - fnRender is called twice for each row",
		null,
		function () { return mTmp == 57; }
	);
	
	oTest.fnWaitTest( 
		"Confirm that fnRender passes one argument (an object) with three parameters",
		function () {
			mTmp = true;
			oSession.fnRestore();
			oTable = $('#example').dataTable( {
				"sAjaxSource": "../../../examples/ajax/sources/objects.txt",
				"aoColumns": [
					{ "mDataProp": "engine" },
					{ 
						"fnRender": function (a) {
							if ( arguments.length != 1 || typeof a.iDataRow=='undefined' ||
						 		typeof a.iDataColumn=='undefined' || typeof a.aData=='undefined' )
							{
								mTmp = false;
							}
							return a.aData['browser'];
						},
						"mDataProp": "browser"
					},
					{ "mDataProp": "platform" },
					{ "mDataProp": "version" },
					{ "mDataProp": "grade" }
				]
			} );
		},
		function () { return mTmp; }
	);
	
	oTest.fnWaitTest( 
		"fnRender iDataColumn is the column",
		function () {
			mTmp = true;
			oSession.fnRestore();
			oTable = $('#example').dataTable( {
				"sAjaxSource": "../../../examples/ajax/sources/objects.txt",
				"aoColumns": [
					{ "mDataProp": "engine" },
					{
						"mDataProp": "browser",
						"fnRender": function (a) {
							if ( a.iDataColumn != 1 )
							{
								mTmp = false;
							}
							return a.aData['browser'];
						}
					},
					{ "mDataProp": "platform" },
					{ "mDataProp": "version" },
					{ "mDataProp": "grade" }
				]
			} );
		},
		function () { return mTmp; }
	);
	
	oTest.fnWaitTest( 
		"fnRender aData is data array of correct size",
		function () {
			mTmp = true;
			oSession.fnRestore();
			oTable = $('#example').dataTable( {
				"sAjaxSource": "../../../examples/ajax/sources/objects.txt",
				"aoColumns": [
					{ "mDataProp": "engine" },
					{
						"mDataProp": "browser",
						"fnRender": function (a) {
							if ( a.aData.length != 5 )
							{
								mTmp = false;
							}
							return a.aData['browser'];
						}
					},
					{ "mDataProp": "platform" },
					{ "mDataProp": "version" },
					{ "mDataProp": "grade" }
				]
			} );
		},
		function () { return mTmp; }
	);
	
	oTest.fnWaitTest( 
		"Passed back data is put into the DOM",
		function () {
			oSession.fnRestore();
			oTable = $('#example').dataTable( {
				"sAjaxSource": "../../../examples/ajax/sources/objects.txt",
				"aoColumns": [
					{ "mDataProp": "engine" },
					{
						"mDataProp": "browser",
						"fnRender": function (a) {
							return 'unittest';
						}
					},
					{ "mDataProp": "platform" },
					{ "mDataProp": "version" },
					{ "mDataProp": "grade" }
				]
			} );
		},
		function () { return $('#example tbody tr:eq(0) td:eq(1)').html() == 'unittest'; }
	);
	
	oTest.fnWaitTest( 
		"Passed back data is put into the DOM",
		function () {
			oSession.fnRestore();
			oTable = $('#example').dataTable( {
				"sAjaxSource": "../../../examples/ajax/sources/objects.txt",
				"aoColumns": [
					{ "mDataProp": "engine" },
					{ "mDataProp": "browser" },
					{ 
						"mDataProp": "platform",
						"fnRender": function (a) {
							return 'unittest1';
						}
					},
					{ 
						"mDataProp": "version",
						"fnRender": function (a) {
							return 'unittest2';
						}
					},
					{ "mDataProp": "grade" }
				]
			} );
		},
		function () {
			var bReturn = 
				$('#example tbody tr:eq(0) td:eq(2)').html() == 'unittest1' &&
				$('#example tbody tr:eq(0) td:eq(3)').html() == 'unittest2';
			return bReturn; }
	);
	
	
	
	
	
	oTest.fnComplete();
} );