<?php

class SoapWrapper {
    
    function __construct($url) {
        // create a new SoapClient instance with passed in WSDL URL
        // array params are for error tracking
        $client = new SoapClient($url, [
                                            "trace"      => 1,
                                            "exceptions" => 0
                                        ]);
        return $client;
    };

    public function authenticateUser($client) {
        // call authenticate method from WoS API
        $response = $client->authenticate();
        return $response;
    }

    // set the SID in the cookie
    public function setCookie($client, $response) {

        if (isset($response->return)) {

            $client->__setCookie('SID', $response->return);
        } else {
            // route to relevant view to display throttle error
            return Redirect::to('throttle');
        }
    }
}