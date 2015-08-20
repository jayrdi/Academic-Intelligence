<?php

// css
echo '<link rel="stylesheet" type="text/css" href="style2.css"/>';

// set processing time for browser before timeout
ini_set('max_execution_time', 3600);

// REST HTTP GET Request searching for people associated with keywords (term)
$url = "http://gtr.rcuk.ac.uk/search/project.json?term=badgers&fetchSize=100";

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

// initiate a counter to check if reached max number of records to know when to break iteration
$counter = 0;

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
    $thisUrl = "http://gtr.rcuk.ac.uk/search/person.json?term=badgers&fetchSize=100&page=" . $page;
    $thisResponse = file_get_contents($thisUrl);
    $thisJson = json_decode($thisResponse, true);
    //iterate results per page (100)
    for($j = 0; $j < 100; $j++) {
        $counter++;
        if($counter > $count) {
            break 2;
        };
        // push REST Request along with each id to array
        // now have list of REST requests for specific people search
        @$aPerson = json_decode(file_get_contents("http://gtr.rcuk.ac.uk/person/" . ($thisJson['results'][$j]['person']['id']) . ".json?"), true);
        @array_push($projects, $aPerson);
        // start table row
        echo "<tr>";
        // record number
        echo "<td>" . ($j+1) . "</td>";
        // first name
        $firstName = $projects[$j]['personOverview']['person']['firstName'];
        echo "<td>" . $firstName . "</td>";
        // surname
        $surname = $projects[$j]['personOverview']['person']['surname'];
        echo "<td>" . $surname . "</td>";
        // URL link
        $url = $projects[$j]['personOverview']['person']['url'];
        echo "<td>" . $url . "</td>";
        // iterate projects for each record
        foreach($projects[$j]['personOverview']['projectSearchResult']['results'] as $value) {
            // sum funds
            $funds += $value['projectComposition']['project']['fund']['valuePounds']; 
        }
        // display funds
        echo "<td>" . $funds . "</td>";
        echo "<td></td><td></td><td></td><td></td>";
        // organisation details
        @$org = $projects[$j]['personOverview']['organisation']['name'];
        echo "<td>" . $org . "</td>";
        @$orgLink = $projects[$j]['personOverview']['organisation']['url'];
        echo "<td>" . $orgLink . "</td>";
    }
}

// print "<pre>\n";
// print_r($projects);
// print "</pre>";