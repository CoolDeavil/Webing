<?php
use GuzzleHttp\Psr7\Response;
require_once('..'.
    DIRECTORY_SEPARATOR.'vendor'.
    DIRECTORY_SEPARATOR.'autoload.php');
$countryList = json_decode(file_get_contents("countries.json"), true);
$needle = $_GET['name'];
$regex = "#".$needle."#";
$matched = [];
foreach ($countryList as $country) {
    if( preg_match($regex,  $country['name'])){
        $matched[] = $country;
    }
}
$response = new Response();
$response->getBody()->write(json_encode($matched));
Http\Response\send($response);
