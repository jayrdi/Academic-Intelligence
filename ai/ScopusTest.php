<?php

$apiKey = "&apiKey=c2cb86c3a511ed34dd6f03f481c637c1";
$search1 = urlencode("badgers");
$search2 = urlencode(" OR weasels");
$start = 0;
$scopusData = [];
$finalScopus = [];
// create an array to represent citation values to ignore, i.e. not interested
// in any publications with less than 4 citations
$ignore = array(0, 1, 2, 3);

// set processing time for browser before timeout
ini_set('max_execution_time', 3600);
// override default PHP memory limit
ini_set('memory_limit', '-1');

// REST HTTP GET Request searching for people associated with keywords (term)
$searchLink = "http://api.elsevier.com/content/search/scopus?query=KEY(" . $search1 . $search2 . ")" . $apiKey . "&sort=citedby-count&count=100&start=" . $start . "&view=complete";

// save results to a variable
$searchResponse = file_get_contents($searchLink);

// convert JSON to PHP variable
$searchJson = json_decode($searchResponse, true);

// get total number of results for query to know when to stop iterating data
$total = $searchJson['search-results']['opensearch:totalResults'];

// iterate data loading next 200 results (max) each time and adding new results to array
for ($i = $start; $i <= $total; $i+=100) {
    // REST HTTP GET Request searching for people associated with keywords (term)
    $eachLink = "http://api.elsevier.com/content/search/scopus?query=KEY(" . $search1 . $search2 . ")" . $apiKey . "&sort=citedby-count&count=100&start=" . $i . "&view=complete";

    // save results to a variable
    $eachResponse = file_get_contents($eachLink);

    $eachJson = json_decode($eachResponse, true);

    // echo "</br>RECORDS:</br>";
    // print "<pre>\n";
    // print_r($eachJson);
    // print "</pre>";

    foreach ($eachJson['search-results']['entry'] as $record) {
        // array to store authors
        $authors = [];
        if (isset($record['author'])) {
            foreach ($record['author'] as $thisAuthor) {
                // push initials and surname to array
                array_push($authors, ($thisAuthor['initials'] . $thisAuthor['surname']));
            }
        };
        // scopus ID
        $scopusID = $record['dc:identifier'];
        // paper title
        $title = $record['dc:title'];
        // date
        $date = substr($record['prism:coverDate'], 0, 4);
        // citations, if less than 4 then break out of iteration
        if (!in_array(($cites = $record['citedby-count']), $ignore)) {
            $cites = $record['citedby-count'];
        } else {
            break 2;
        }

        $thisData = [
                        "authors" => $authors,
                        "ID"      => $scopusID,
                        "title"   => $title,
                        "date"    => $date,
                        "cites"   => $cites
        ];

        array_push($scopusData, $thisData);
    }
};

// need to replace single quotes to avoid char escape
for ($i = 0; $i < count($scopusData); $i++) {
    foreach ($scopusData[$i]['authors'] as &$edit) {
        $edit = str_replace("'", "", $edit);
    };
    $scopusData[$i]['title'] = str_replace("'", "", $scopusData[$i]['title']);
};

// for some reason Scopus returns duplicate authors for same record
// this will remove duplicates within the same paper
for ($i = 0; $i < count($scopusData); $i++) {
    $scopusData[$i]['authors'] = array_unique($scopusData[$i]['authors']);
    // reset indices for array
    $scopusData[$i]['authors'] = array_values($scopusData[$i]['authors']);
};

// echo "</br>RECORDS:</br>";
// print "<pre>\n";
// print_r($finalScopus);
// print "</pre>";

?>