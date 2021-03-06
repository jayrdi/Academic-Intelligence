<!DOCTYPE HTML>

<html lang="en">

<head>

	<title>NCL Research Enterprises</title>

	<!-- LINKS -->

	<!-- Corporate visual identity (bootstrap) -->
	<link href="//resviz.ncl.ac.uk/static/style/cvi.css" media="screen" rel="stylesheet" type="text/css" />
	<!-- bootstrap css -->
	<link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/readable/bootstrap.min.css" rel="stylesheet">
	<!-- fonts for graph -->
	<link href='https://fonts.googleapis.com/css?family=Raleway:700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Lora:400,700' rel='stylesheet' type='text/css'>
	<!-- favicon, newcastle logo -->
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />	

	<!-- SCRIPTS -->

	<!-- jquery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- d3 -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.3/d3.min.js" charset="utf-8"></script>

	<!-- META -->

	<meta charset="UTF-8"/>
	<!-- ensure proper rendering and touch zooming in mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=10" />
	<meta name="keywords" content="academic intelligence, resviz, research visualisation, research and enterprise services"/>
	<meta name="description" content="A means of querying the Thomson Reuters Web of Science database using their API with SOAP HTTPS exchanges"/>
	<meta name="author" content="John Dawson"/>

</head>

<body>

	<!-- BREADCRUMBS -->
	<div class="sg-orientation">    
        <a href="#content" class="sg-button sg-skiptocontent">Skip to Content</a>
        <span class="sg-breadcrumbs">
            <a href="http://www.ncl.ac.uk/">Newcastle University</a> &gt;&gt;
            <a href="https://resviz.ncl.ac.uk/">Research Visualisation</a> &gt;&gt;
            <strong href="#">Academic Intelligence</strong>
        </span>
    </div>

	<!-- TITLE BAR -->
	<div class="sg-titlebar">
		<h1><a title="Newcastle University Homepage" accesskey="1" href="http://www.ncl.ac.uk/"/><span title="Newcastle University">Newcastle University</span></a></h1>
		<h2><a href="https://resviz.ncl.ac.uk/wos/">Academic Intelligence</a></h2>
	</div> 


	<div class="sg-navigation">&nbsp;</div>

	<div class="sg-content">

		<!-- NAVIGATION BAR -->
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="navbar-header">
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="https://resviz.ncl.ac.uk/"><span class="glyphicon glyphicon-home"></span></a></li>
						<li><a href="https://resviz.ncl.ac.uk/chords/">Research Visualisation</a></li>
						<li><a href="index.php">Academic Intelligence</a></li>
					</ul>
				</div> <!-- navbar-collapse -->
			</div> <!-- container -->
		</nav> <!-- navbar -->

		<section class="container">
			<h1>Authors with Largest Number of Citations</h1>

			<div class="chart well bs-component"></div>

			<div class="jumbotron">
				<h2>Information</h2>
				<p>The y-axis represents the number of citations for publications for the author on the x-axis.</p>
				<p>Click on the author's bar to perform a search by their name with Google.</p>
				<p>Try to find these authors on <a target="_blank" href="https://uk.linkedin.com/">LinkedIn</a> or the <a target="_blank" href="http://gtr.rcuk.ac.uk/" id="mail">Gateway to Research</a> sites.</p>
			</div>

			<script type="text/javascript">

				$(document).ready(function() {

					// get $recordArray data from wos.php
					var data = $.parseJSON('<?php echo json_encode($recordArray)?>');
						// test
						// alert(data);

						// javascript variable to store json data
						var topCited = data;

						// establish some margins for the graph area to avoid overlap with other HTML elements
						var margin = {
										top: 30,
										right: 30,
										bottom: 180,
										left: 100
							 		 };

						// initiate variables for max width and height of canvas for chart, determine largest citation value for domain and scaling
						// width, 10 bars at 75px each plus 3px padding
						var height = 500;
						var width = 870;

						// maximum height of y axis is maximum number of citations (first element)
						var maxY = topCited[0].citations;

						// set scale to alter data set so that it fits well in the canvas space
						// map the domain (actual data range) to the range (size of canvas)
						var linearScale = d3.scale.linear()
												  // 0 -> largest citations value
												  .domain([0, maxY])
												  // 0 -> 500
												  .range([0, height]);

						// create canvas for chart
						var svgContainer = d3.select(".chart").append("svg")
														      .attr("width", width)
											  				  // max size from data set plus 20px margin
											  				  .attr("height", height + margin.bottom);
											  				  

						// create an SVG Grouped Element (<g>) to contain all the 'bars' of the graph
						var barGroup = svgContainer.append("g")
												   .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

						// bind the data to SVG Rectangle elements
						var bar = barGroup.selectAll("rect")
										  .data(topCited) 
										  .enter()
										  .append("rect")
										  .attr("fill", "#7bafd4")
										  // highlight each bar as you hover over it
										  .on("mouseover", function () {
										      d3.select(this)
										        .attr("fill", "#003c71");	
										  })
										  // transition to remove highlight from bar
										  .on("mouseout", function() {
										      d3.select(this)
										        .transition()
										        .duration(250)
										        .attr("fill", "#7bafd4");
										  });

						// set variable to store bar width + padding
						var barWidth = 78;

						// set attributes for the rectangles (bars)
						var rectAttributes = bar.attr("width", 75)
												// set bar height by value of citations
												.attr("height", function (d) {
												    return linearScale(d.citations);
												})
												// index * 78 will move each bar (width, 75px) one bar width along and leave 3px padding
												.attr("x", function (d, i) {
												    return i * barWidth;
												})
												// this is determined from the top left corner so to get the bar at the bottom, take the bar height from the canvas height
												.attr("y", function (d) {
												    return height - linearScale(d.citations);
												})

						// bind the data to SVG Text elements
						var text = barGroup.selectAll("text")
										   .data(topCited)
										   .enter()
										   .append("text");

						// set attributes for the text on bars (citation values)
						var barLabels = text.attr("x", function (d, i) {
											     return (barWidth * i) + 37.5; // sets to halfway between each bar horizontally
											 })
											 .attr("y", function (d) {
											     return height - (linearScale(d.citations)) - 3; // sets to top of each bar + 5 to sit just above bar
											 })
											 .text(function (d) {
											     return d.citations; // value to display, citations value (number)	
											 })
											 .style("text-anchor", "middle")
											 .attr("font-family", "Raleway")
											 .attr("font-size", "26px")
											 .attr("fill", "#7bafd4");

						// create a scale for the horizontal axis
						var xScale = d3.scale.ordinal()
											 .domain(data.map(function (d) {
											 	return d.author1;
											 }))
											 .rangeRoundBands([0, 780], 0);

						// create a scale for the vertical axis
						var yScale = d3.scale.linear()
											 .domain([0, maxY])
											 .range([height, 0]);

						// define x-axis
						var xAxis = d3.svg.axis()
										  .scale(xScale)
										  .orient("bottom")
										  .ticks(10);

						// define y-axis
						var yAxis = d3.svg.axis()
									  .scale(yScale)
									  .orient("left")
									  .ticks(10);

						// if this calculation is done in "translate" below, it concatenates instead of adding values
						var translateY = height + margin.top;

						// create x-axis
						svgContainer.append("g")
									.attr("class", "axis")
									.attr("transform", "translate(" + margin.left + "," + translateY + ")")
									.call(xAxis)
									// select author names
									.selectAll("text")
									.attr("font-family", "Lora")
									.style("text-anchor", "end")
									// spacing
									.attr("dx", "-.8em")
									.attr("dy", ".15em")
									// rotate text as too long to display horizontally
									.attr("transform", function (d) {
									    return "rotate(-45)";
									});

						// create y-axis
						svgContainer.append("g")
									.attr("class", "axis")
									.attr("transform", "translate(" + margin.left  + "," + margin.top + ")")
									.call(yAxis)
									// append a title to the y-axis
									.append("text")
									.attr("transform", "rotate(-90)")
									.attr("y", -70)
									.attr("x", -200)
									.style("text-anchor", "end")
									.attr("fill", "#000")
									.attr("font-family", "Lora")
									.attr("font-size", "24px")
									.text("Citations");

						// create link when user clicks on a single bar of data
						d3.selectAll("rect")
						  .on("click", function (d) {
						  	  // variable stores url for google and adds author name relevant to bar that was clicked
						      var url = "https://www.google.co.uk/#q=" + d.author1;
						      // add an href html element with the url attached
						      $(location).attr("href", url);
						      window.location = url;
						  });

					// })
					// get data from $searchParams from wos.php
					/* var searchData = $.parseJSON('<?php echo json_encode($searchParams) ?>');

						// select location by HTML table id
						var tbody = document.getElementById('searchData');
						var tr = "<tr>";
						// header row
						tr += "<td>Journal 1</td>" +
							  "<td>Journal 2</td>" + 
							  "<td>Journal 3</td>" + 
							  "<td>Keyword 1</td>" + 
							  "<td>Keyword 2</td>" +
							  "<td>Keyword 3</td>" + 
							  "<td>From</td>" + 
							  "<td>To</td>" + 
							  "</tr>";

						// add data row
						tr += "<td>" + searchData.journal1 + "</td>" + 
							  "<td>" + searchData.journal2 + "</td>" + 
							  "<td>" + searchData.journal3 + "</td>" +
							  "<td>" + searchData.title1 + "</td>" +
							  "<td>" + searchData.title2 + "</td>" +
							  "<td>" + searchData.title3 + "</td>" +
							  "<td>" + searchData.from + "</td>" +
							  "<td>" + searchData.to + "</td>" +
							  "</tr>";
						// add row to body of table
						tbody.innerHTML += tr;
					// }); */
				});

			</script>

			<!-- <div class="row">
				<div class="col-lg-5">
					<div class="alert alert-danger">
						<h3>Temporary Graph is Temporary</h3>
					</div>
				</div>
				<div class="col-lg-7"></div>
			</div>

			<table class="table table-striped table-hover">
				<tbody id="searchData"></tbody>
			</table>

		</section> -->

	</div> <!-- sg-content -->

	<!-- FOOTER -->
	<div class="sg-clear">&nbsp;</div>
	<div class="sg-footer">
		<p><a href="http://www.ncl.ac.uk/res/about/office/research/">Research &amp; Enterprise Services</a><br/>Newcastle University, Newcastle Upon Tyne,<br/>NE1 7RU, United Kingdom<br/><a href="mailto:res.policy@ncl.ac.uk">Email Webmaster</a><br/><br/>&copy; 2014 Newcastle University</p>
	</div>

	<!-- bootstrap js -->
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<!-- check browser version, if outdates, prompt for update -->
	<script src="//browser-update.org/update.js"></script>

</body>

</html>