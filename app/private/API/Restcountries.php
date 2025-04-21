<?php
use GuzzleHttp\Client;

require __DIR__ . '/../plugins/vendor/autoload.php';
class Restcountries{
    public static function getCountries(){
        $client = new Client();
        $response = $client->request('GET', 'https://restcountries.com/v3.1/all');
        $data = json_decode($response->getBody(), true);
        return self::sortCountriesByName($data);
    }
    private static function sortCountriesByName($countries){
        usort($countries, function($a, $b) {
            return strcmp($a['name']['common'], $b['name']['common']);
        });
        return $countries;
    }
}