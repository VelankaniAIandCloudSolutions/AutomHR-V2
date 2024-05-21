$(document).ready(function() {
	
	// Area Chart
	
  //   Morris.Area({
		// element: 'area-charts',
		// data: [
		// 	{ y: '2006', a: 100, b: 90 },
		// 	{ y: '2007', a: 75,  b: 65 },
		// 	{ y: '2008', a: 50,  b: 40 },
		// 	{ y: '2009', a: 75,  b: 65 },
		// 	{ y: '2010', a: 50,  b: 40 },
		// 	{ y: '2011', a: 75,  b: 65 },
		// 	{ y: '2012', a: 100, b: 90 }
		// ],
		// xkey: 'y',
		// ykeys: ['a', 'b'],
		// labels: ['Total Invoice', 'Pending Invoice'],
		// lineColors: ['#667eea','#764ba2'],
		// lineWidth: '3px',
		// resize: true,
		// redraw: true
  //   });

	// Bar Chart
	
	Morris.Bar({
		element: 'bar-charts',
		data: [
			{ y: '2006', a: 100, b: 90 },
			{ y: '2007', a: 75,  b: 65 },
			{ y: '2008', a: 50,  b: 40 },
			{ y: '2009', a: 75,  b: 65 },
			{ y: '2010', a: 50,  b: 40 },
			{ y: '2011', a: 75,  b: 65 },
			{ y: '2012', a: 100, b: 90 }
		],
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Total Income', 'Total Outcome'],
		lineColors: ['#667eea','#764ba2'],
		lineWidth: '3px',
		barColors: ['#667eea','#764ba2'],
		resize: true,
		redraw: true
	});
	
	// Line Chart
	
	// Morris.Line({
	// 	element: 'line-charts',
	// 	data: [
	// 		{ y: '2006', a: 50, b: 90 },
	// 		{ y: '2007', a: 75,  b: 65 },
	// 		{ y: '2008', a: 50,  b: 40 },
	// 		{ y: '2009', a: 75,  b: 65 },
	// 		{ y: '2010', a: 50,  b: 40 },
	// 		{ y: '2011', a: 75,  b: 65 },
	// 		{ y: '2012', a: 100, b: 50 }
	// 	],
	// 	xkey: 'y',
	// 	ykeys: ['a', 'b'],
	// 	labels: ['Total Sales', 'Total Revenue'],
	// 	lineColors: ['#667eea','#764ba2'],
	// 	lineWidth: '3px',
	// 	resize: true,
	// 	redraw: true
	// });
	
	// Donut Chart
		
	Morris.Donut({
		element: 'pie-charts',
		colors: [
			'#667eea',
			'#764ba2',
			'#a5b3f3',
			'#bfa8d7'
		],
		data: [
			{label: "Employees", value: 30},
			{label: "Clients", value: 15},
			{label: "Projects", value: 45},
			{label: "Tasks", value: 10}
		],
		resize: true,
		redraw: true
	});
		
});