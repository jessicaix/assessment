<?php

/**
 * @file
 * This class provide GET/POST functions for API's to access.
 */

namespace Drupal\gobear_jobs;

class Apis {
    
    protected $url;
            
    function __construct(){
        $this->url = "https://jobs.github.com/positions.json";
    }
    
    /**
    * Takes the request and validates it. Pass it to GET/POST function.
    * 
    * @param $method
    *   API access Method.
    * 
    * @param $params
    *   Parameter list.
    * 
    * @return array
    *   Return position list.
    */
   function api_call_request($method = 'GET', $params = array()) {
     $methods = [
       'POST',
       'GET',
     ];
     $result = array();

     try {
       if (in_array(strtoupper($method), $methods) === FALSE) {
         throw new Exception("Invalid method");
       }
       else {
         // will need a request call for GET, POST
         switch (strtoupper($method)) {
           case 'GET':
               $result = $this->api_get_request($method, $params);
             break;
           case 'POST':
               $result = $this->api_post_request($method, $params);
             break;
           default:
             throw new Exception("Invalid method");
             break;
         }
       }
     }
     catch (Exception $e) {
       echo $e->getMessage();
     }

     return $result;
   }
   
   /**
    * Function for GET request.
    * 
    * @param $method
    *   API access Method.
    * 
    * @param $params
    *   Parameter list.
    * 
    * @return array
    *   Make GET request.
    */
   function api_get_request($method, $params) {
     try {

        $client = new \GuzzleHttp\Client(['base_uri' => $this->url]);
        $response = $client->request('GET', $this->url, [
          'query' => $params
        ]);
        $stream = $response->getBody();
        $contents = $stream->getContents();
        $json = json_decode($contents, true);
        return $json;
     }
     catch (RequestException $e) {
       return($this->t('Error'));
     }
     
   }
   
   /**
    * Function for POST request.
    *
    * @param $method
    *   API access Method.
    * 
    * @param $params
    *   Parameter list.
    * 
    * @return array
    *   Make POST request.
    */
   function api_post_request($method, $params) {

       try {
         $client = \Drupal::httpClient();
         $response = $client->request('POST',$requesturl, ['form_params' => $params]);
         $data = $response->getBody()->getContents();
         return $data;
       }
       catch (RequestException $e) {
           return($this->t('Error'));
       }
       
   }


}