<?php
use GuzzleHttp\Client;

require __DIR__ . '/../plugins/vendor/autoload.php';
class Restcountries{
    /**
     * Retrieves a list of countries from the Restcountries API.
     *
     * @return array An array containing country data retrieved from the API.
     * @throws Exception If there is an error during the API request or response processing.
     */
    public static function getCountries(){
        $client = new Client();
        $response = $client->request('GET', 'https://restcountries.com/v3.1/all');
        $data = json_decode($response->getBody(), true);
        return self::sortCountriesByName($data);
    }
    /**
     * Retrieves a list of countries formatted for use in a select dropdown.
     *
     * This method fetches country data and formats it in a way that can be
     * directly used in an HTML select element. It is useful for applications
     * that require users to select a country from a predefined list.
     *
     * @return string HTML string containing the options for a select dropdown.
     */
    public static function getCountryLikeSelect(){
        include_once __DIR__ . '/../components/select.php';
        return generateSelect("country_id", self::getOptions(self::getCountries()), "<span class='icon is-small'><i class='fa-solid fa-globe'></i></span>");
    }
    /**
     * Retrieves options based on the provided data.
     *
     * @param mixed $data The input data used to generate options.
     * @return string The generated HTML options based on the input data.
     */
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
    /**
     * Sorts an array of countries by their name in ascending order.
     *
     * @param array $countries An array of countries where each country is expected to have a 'name' key.
     * @return array The sorted array of countries.
     */
    private static function sortCountriesByName($countries){
        usort($countries, function($a, $b) {
            return strcmp($a['name']['common'], $b['name']['common']);
        });
        return $countries;
    }
}