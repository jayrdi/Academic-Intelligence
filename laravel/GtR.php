<?php

// css
echo '<link rel="stylesheet" type="text/css" href="style2.css"/>';

// set processing time for browser before timeout
ini_set('max_execution_time', 3600);

// REST HTTP GET Request searching for people associated with keywords (term)
$url = "http://gtr.rcuk.ac.uk/search/project.json?term=soil+mechanics&fetchSize=100";
// save results to a variable
$response = file_get_contents($url);

// convert JSON to PHP variable
$json = json_decode($response, true);

// store total number of projects returned by query for iteration count
$count = $json['resourceHitCount'][0]['count'];
print "<pre>\n";
echo "TOTAL RECORDS: " . $count;
print "</pre>";

// total number of results pages
$pages = ceil($count/100);
print "<pre>\n";
echo "TOTAL PAGES: " . $pages;
print "</pre>";

// set initial page so that each iteration adds to this to get next page
$page = 1;

// array to store id details for projects retured from search
$projects = [];

// initiate a counter to give records a number
$counter = 1;

// // var to store funds
// $funds = 0;

// print table with suitable headers
echo '<table id="table" <tr>
          <th>Record Number</th>
          <th>Project ID</th>
          <th>Project Title</th>
          <th>Value</th>
          <th>Year</th>
          <th>Funder</th>
          <th>Fund Type</th>
          <th>Organisation</th>
          <th>Organisation ID</th>
          <th>First Name</th>
          <th>Surname</th>
          <th>Person ID</th>
      </tr>>';

// iterate data loading next page each time and adding new results to array
for($i = 1; $i <= $pages; $i++) {

    // set page number to current iteration number
    $page = $i;
    $thisUrl = "http://gtr.rcuk.ac.uk/search/project.json?term=soil+mechanics&fetchSize=100&page=" . $page;
    $thisResponse = file_get_contents($thisUrl);
    $thisJson = json_decode($thisResponse, true);

    foreach($thisJson['results'] as $project) {

      // start table row
      echo "<tr>";
      // record number
      echo "<td>" . $counter . "</td>";
      $counter++;
      // project ID
      echo "<td>" . $project['projectComposition']['project']['id'] . "</td>";
      // project title
      echo "<td>" . $project['projectComposition']['project']['title'] . "</td>";
      // value
      echo "<td>" . $project['projectComposition']['project']['fund']['valuePounds'] . "</td>";
      // year
      echo "<td>" . $project['projectComposition']['project']['fund']['start'] . "</td>";
      // funder
      echo "<td>" . $project['projectComposition']['project']['fund']['funder']['name'] . "</td>";
      // fund type
      echo "<td>" . $project['projectComposition']['project']['fund']['type'] . "</td>";
      // organisation
      echo "<td>" . $project['projectComposition']['leadResearchOrganisation']['name'] . "</td>";
      // organisation ID
      echo "<td>" . $project['projectComposition']['leadResearchOrganisation']['id'] . "</td>";
      // first name
      echo "<td>" . @$project['projectComposition']['personRole'][0]['firstName'] . "</td>";
      // surname
      echo "<td>" . @$project['projectComposition']['personRole'][0]['surname'] . "</td>";
      // person ID
      echo "<td>" . @$project['projectComposition']['personRole'][0]['id'] . "</td>";
    };
};

// print "<pre>\n";
// print_r($thisJson);
// print "</pre>";