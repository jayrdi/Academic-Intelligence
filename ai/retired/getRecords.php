<?php


    // =================================================================== //
    // ===================== CONNECT TO WOS API ========================== //
    // =================================================================== //


    // set WSDL for authentication and create new SOAP client
    $auth_url  = "http://search.webofknowledge.com/esti/wokmws/ws/WOKMWSAuthenticate?wsdl";
    // array options are temporary and used to track request & response data in printout below (line 65)
    $auth_client = @new SoapClient($auth_url);
    // run 'authenticate' method and store as variable
    $auth_response = $auth_client->authenticate();

    // set WSDL for search and create new SOAP client
    $search_url = "http://search.webofknowledge.com/esti/wokmws/ws/WokSearch?wsdl";
    // array options are temporary and used to track request & response data in printout below (line 130)
    $search_client = @new SoapClient($search_url);
    // call 'setCookie' method on '$search_client' storing SID (Session ID) as the response (value) given from the 'authenticate' method
    $search_client->__setCookie('SID',$auth_response->return);


    // =================================================================== //
    // ============== PASS IN PARAMETERS FOR SOAP REQUEST ================ //
    // =================================================================== //


    // data passed in from user via form in index.html

    // search type for journals (publication name)
    $queryType1 = "SO";

    // keyword(s)
    // check if journal1 field has been populated, if not entered then set to blank
    if (isset($_POST["journal1"])) {
        $queryJournal1 = $_POST["journal1"];
        $queryJournal1 = $queryType1. "=" .$queryJournal1;
    } else {
        $queryJournal1 = "";
    };

    // check if journal2 field has been populated, if not entered then set to blank
    if (isset($_POST["journal2"])) {
        $queryJournal2 = $_POST["journal2"];
        $queryJournal2 = " OR " .$queryType1. "=" .$queryJournal2;
    } else {
        $queryJournal2 = "";
    };

    // check if journal3 field has been populated
    if (isset($_POST["journal3"])) {
        $queryJournal3 = $_POST["journal3"];
        $queryJournal3 = " OR " .$queryType1. "=" .$queryJournal3;
    } else {
        $queryJournal3 = "";
    };

    // search type for titles
    $queryType2 = "TI";

    // keyword(s)
    // check if title1 field has been populated
    if (isset($_POST["title1"])) {
        $queryTitle1 = $_POST["title1"];
        $queryTitle1 = $queryType2. "=" .$queryTitle1;
    } else {
        $queryTitle1 = "";
    };

    // check if title2 field has been populated
    if (isset($_POST["title2"])) {
        $queryTitle2 = $_POST["title2"];
        $queryTitle2 = " OR " .$queryType2. "=" .$queryTitle2;
    } else {
        $queryTitle2 = "";
    };

    // check if title3 field has been populated
    if (isset($_POST["title3"])) {
        $queryTitle3 = $_POST["title3"];
        $queryTitle3 = " OR " .$queryType2. "=" .$queryTitle3;
    } else {
        $queryTitle3 = "";
    };
    
    // sort type
    $sortType = "TC";

    // check if timespan fields have been populated
    if (!$_POST["timeStart"]) {
        $timeStart = "1864-01-01";
        $timeEnd = "2080-01-01";
    } else {
        $timeStart = $_POST["timeStart"];
        $timeEnd = $_POST["timeEnd"];
    };

    // pass in relevant parameters for search, this is the format necessary for Web of Science Web Service
    $search_array = array(
        'queryParameters' => array(
            'databaseId' => 'WOS',
            'userQuery' => $queryJournal1 . $queryJournal2 . $queryJournal3 . ' AND ' . $queryTitle1 . $queryTitle2 . $queryTitle3,
            'editions' => array('collection' => 'WOS', 'edition' => 'SCI'),
            'timeSpan' => array('begin' => $timeStart, 'end' => $timeEnd),
            'queryLanguage' => 'en'
        ),
        'retrieveParameters' => array(
            'count' => '100',
            'sortField' => array(
                array('name' => $sortType, 'sort' => 'D')
            ),
            'firstRecord' => '1'
        )
    );


    // =================================================================== //
    // ======== PERFORM SEARCH USING PARAMETERS & SOAP CLIENT ============ //
    // =================================================================== //


    // try to store as a variable the 'search' method on the '$search_array' called on the SOAP client with associated SID 
    try {
        $search_response = $search_client->search($search_array);
    } catch (Exception $e) {  
        echo $e->getMessage(); 
    };

    // number of records found by search, used to finish loop (check if no records first)
    // if soap fault, i.e. no recordsFound then set $len to null to avoid undefined variable on line 205
    if (isset($search_response->return->recordsFound)) {
        $len = $search_response->return->recordsFound;
        // echo variable for use in jscript
        echo $len;
    } else {
        $len = "";
    }

?>