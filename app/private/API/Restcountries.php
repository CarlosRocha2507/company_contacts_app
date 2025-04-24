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
    public static function getCountryLikeSelect(){
        include_once __DIR__ . '/../components/select.php';
        return generateSelect("country_id", self::getOptions(self::getCountries()), "<span class='icon is-small'><i class='fa-solid fa-globe'></i></span>");
    }
    private static function getOptions($data){
        if(empty($data) || $data == null){
            return "<option value='0' selected disabled>No data found!</option>";
        }
        $options = "";
        foreach ($data as $row) {
            if (isset($row['cca2']) && isset($row['idd']['root']) && isset($row['idd']['suffixes'][0])) {
                $code = $row['cca2']; 
                $dial = $row['idd']['root'] . $row['idd']['suffixes'][0]; 
                $optionText = $code . " ($dial)";
                $selected = ($code === "PT") ? "selected" : "";
                $options .= "<option value=\"$dial\" $selected>$optionText</option>";
            }
        }
        if (empty($options)) {
            return "<option value='0' selected disabled>No data found!</option>";
        }
        return $options;
    }
    private static function sortCountriesByName($countries){
        usort($countries, function($a, $b) {
            return strcmp($a['name']['common'], $b['name']['common']);
        });
        return $countries;
    }
}