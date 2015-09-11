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

		<section class="graphs container">
			<div class="graph_fields_wrap row">
					<div class="col-lg-6">
						<h4>Authors with Largest Number of Citations</h4>
						<h4>(all time)</h4>
						<div class="chart1 well bs-component"></div>
					</div> <!-- col-lg-6 -->
					<div class="col-lg-6">
						<h4>Authors with Largest Number of Citations</h4>
						<h4>(user defined)</h4>
						<div class="chart2 well bs-component"></div>
					</div> <!-- col-lg-6 -->
			</div> <!-- row -->

			<div class="col-lg-3">
				<label for="select" class="control-label">Time span:</label>
				<select class="form-control" id="timeSelect">
					<option value="chart2" selected>User defined</option>
					<option value="chart4">Last 10 years</option>
					<option value="chart5">Last 5 years</option>
					<option value="chart6">Last 2 years</option>
				</select>
			</div> <!-- col-lg-3 -->

			<div class="row">
				<div class="col-lg-6" id="rankChart">
					<h4>Authors with Largest Determined Ranking</h4>
					<div class="chart3 well bs-component"></div>
				</div> <!-- col-lg-6 -->
			</div> <!-- row -->

			<!-- <div class="row">
				<div class="col-lg-6" id="freqChart">
					<h4>Frequency of Authors in Data</h4>
					<div class="chart3 well bs-component"></div>
				</div>
			</div> -->

			<div class="row">
				<div class="panel panel-info col-lg-8">
					<div class="panel-heading">
						<h2 class="panel-title">Search Parameters</h2>
					</div>
					<div class="panel-body">
						<div id="journalData"></div>
						<div id="keywordData"></div>
						<div id="timescaleData"></div>
					</div>
				</div> <!-- panel panel-info -->
			</div> <!-- row -->

			<div class="jumbotron well bs-component">
				<h2>Information</h2>
				<p>The y-axis represents the number of citations for publications for the author on the x-axis.</p>
				<p>Click on the author's bar to perform a search by their name with Google.</p>
				<p>Try to find these authors on <a target="_blank" href="https://uk.linkedin.com/">LinkedIn</a> or the <a target="_blank" href="http://gtr.rcuk.ac.uk/" id="mail">Gateway to Research</a> sites.</p>
			</div>

			<script type="text/javascript">

				$(document).ready(function() {		

					//***** BAR HOVER EVENT *****//

					// create link when user clicks on a single bar of data
					d3.selectAll("rect")
					  .on("click", function (d) {
					  	  // variable stores url for google and adds author name relevant to bar that was clicked
					      var url = "https://www.google.co.uk/#q=" + d.author1;
					      // add an href html element with the url attached
					      $(location).attr("href", url);
					      window.location = url;
					  });

					//***** FREQUENCY BUBBLE CHART *****//


					// create new pack layout as bubble
					/* var bubble = d3.layout.pack()
								   // no sorting, size allocated 510x510
								   .sort(null)
								   .value(function (d) {
								       return d.frequency;	
								   })
								   .size([diameter, diameter]);

					// select chart3 div and append an svg canvas to draw the circles onto
					var canvas = d3.select(".chart3").append("svg")
													 .attr("width", diameter)
													 .attr("height", diameter)
													 .append("g");

					// create a tooltip
					var tooltip = d3.select("body")
									.append("div")
									.style("position", "absolute")
									.style("z-index", "10")
									.style("visibility", "hidden")
									.style("color", "white")
									.style("padding", "8px")
									.style("background-color", "rgba(0,0,0,0.75)")
									.style("border-radius", "6px")
									.style("font", "10px sans-serif")
									.text("tooltip");

					// parse data for use with bubble chart
					jsonData = JSON.parse(topFrequency);

					// run bubble returning array of nodes associated with data
					// will output array of data with computed position of all nodes
					// and populates some data for each node:
					// depth, starting at 0 for root, x coord, y coord, radius
					var nodes = bubble.nodes(jsonData)
									  // filter out the outer circle (root node)
									  .filter(function (d) {
									      return !d.children;	
									  });

					console.log(nodes);

					var node = canvas.selectAll(".node")
									 .data(nodes)
									 .enter()
									 .append("g")
									 // give nodes a class name for referencing
									 .attr("class", "node")
									 .attr("transform", function (d) {
									 	return "translate(" + d.x + "," + d.y + ")";
									 });

					// append the circle graphic for each node
					node.append("circle")
						// radius from data
					    .attr("r", function (d) {
					        return d.r;	
					    })
					    // colour circles according to associated frequency
					    .attr("fill", function (d) {
					    	if (d.frequency >= 10) {
					    		return "#003300";
					    	}
					    	else if (d.frequency = 9) {
					    		return "#000099";
					    	}
					    	else if (d.frequency = 8) {
					    		return "#0000cc";
					    	}
					    	else if (d.frequency = 7) {
					    		return "#0000ff";
					    	}
					    	else if (d.frequency = 6) {
					    		return "#808080";
					    	}
					    	else if (d.frequency = 5) {
					    		return "#909090";
					    	}
					    	else if (d.frequency = 4) {
					    		return "#a0a0a0";
					    	}
					    	else if (d.frequency = 3) {
					    		return "#b0b0b0";
					    	}
					    	else if (d.frequency = 2) {
					    		return "#c0c0c0";
					    	}
					    	else {
					    		return "#d0d0d0";
					    	}
					    })
					    // set stroke for circles
					    .attr("stroke", "#000000")
					    .attr("stroke-width", 2)
					    // display author name when hover over circle
					    .on("mouseover", function (d) {
					    	tooltip.text(d.author1);
					    	tooltip.style("visibility", "visible");
					    })
					    // when move mouse around circle, keep tooltip affixed to same place relative to pointer
					    .on("mousemove", function (d) {
					    	return tooltip.style("top", (d3.event.pageY-10)+"px").style("left", (d3.event.pageX+10))
					    })
					    .on("mouseout", function (d) {
					    	return tooltip.style("visibility", "hidden");
					    });

					// add author name to identify nodes
					node.append("text")
						.style("text-anchor", "middle")
						.style("font-family", "'Raleway', sans-serif")
						.style("font-weight", "bold")
						.style("fill", "#ffffff")
						.attr("dy", ".3em")
						.text(function (d) {
						    return d.children ? "" : d.frequency;
						}); */


					//***** SEARCH PARAMETER PANEL *****//

					// get data from $searchParams from wos.php
					var searchData = $.parseJSON('<?php echo json_encode($searchParams) ?>');

					// select location by HTML table id
					var infoJournal = document.getElementById('journalData');
					var paraJ = "<div class='col'>";

					paraJ +="<h5>Journal(s)</h5>" +
							"<p>" + searchData.journal1 + "</p>" +
							"<p>" + searchData.journal2 + "</p>" +
							"<p>" + searchData.journal3 + "</p>" +
							"</div>";

					infoJournal.innerHTML += paraJ;

					var infoKeyword = document.getElementById('keywordData');
					var paraK = "<div class='col'>";

					paraK +="<h5>Keyword(s)</h5>" +
							"<p>" + searchData.title1 + "</p>" +
							"<p>" + searchData.title2 + "</p>" +
							"<p>" + searchData.title3 + "</p>" +
							"</div>";

					infoKeyword.innerHTML += paraK;

					var infoTimescale = document.getElementById('timescaleData');
					var paraT = "<div class='col'>";

					paraT +="<h5>Timescale</h5>" +
							"<p>From: " + searchData.from + "</p>" +
							"<p>To: " + searchData.to + "</p>" +
							"</div>";

					infoTimescale.innerHTML += paraT;
				});

			</script>

		</section>

	</div> <!-- sg-content -->

	<!-- FOOTER -->
	<div class="sg-clear">&nbsp;</div>
	<div class="sg-footer">
		<p>Research &amp; Enterprise Services</a><br/>Newcastle University, Newcastle Upon Tyne,<br/>NE1 7RU, United Kingdom<br/><a href="mailto:res.policy@ncl.ac.uk">Email Webmaster</a><br/><br/>&copy; 2014 Newcastle University</p>
	</div>

	<!-- local script -->
	<script type="text/javascript" src="graphs.js">

		loadGraph(topCited, chart1);
		loadGraph(topCitedYears, chart2);
		loadGraph(topFreq, chart3);

	</script>
	<!-- bootstrap js -->
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<!-- check browser version, if outdates, prompt for update -->
	<script src="//browser-update.org/update.js"></script>

</body>

</html>