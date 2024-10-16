<?php

trait TEMPLATE {
    protected $file;
    protected $values = array();

    public function __construct($file) {
        $this->file = $file;
    }

    public function set($key, $value) {
        $this->values[$key] = $value;
    }

    public function output() {
        if (!file_exists($this->file)) {
            return "Error loading template file ($this->file).";
        }
        $output = file_get_contents($this->file);

        foreach ($this->values as $key => $value) {
            $tagToReplace = "[@$key]";
            $output = str_replace($tagToReplace, $value, $output);
        }

        return $output;
    }
}

trait FUNCTIONS{

    var $url = 'http://7a4j4neatr-1.algolia.io/1/indexes/*/queries';
    var $requests= array();

    function sendPostData($url, $post){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=UTF-8',
            'Host: 7a4j4neatr-3.algolia.io',
            'X-Algolia-API-Key: 6ac7e952b25cae67a74606ab61ef1858',
            'X-Algolia-Application-Id: 7A4J4NEATR'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        $toArray = json_decode($result,JSON_PRETTY_PRINT);
        curl_close($ch);
        return $toArray;
    }
}

class AWOK_SCRAP{

    use FUNCTIONS;
    use TEMPLATE;

    function __construct(){

    }

    public function fetch(){

        $query_key = (isset($_GET['query']) and !empty($_GET['query']))
                        ? $_GET['query']
                        : false;

        $query_sort = (isset($_GET['sort']))
                        ? $_GET['sort']
                        : false;

        $query_order = (isset($_GET['order']) and $_GET['order'] > 0)
                        ? $_GET['order']
                        : false;

        $parameters = 'query=&maxValuesPerFacet=50&hitsPerPage=32&page=0&facets=["Optional Functions","Eyes","Type of Thermometer","Metal Type","Camera Screen Size","Stone Type","Sensor Type","Type Of Cut","Megapixels","Gender","Optical Zoom","Jewellery Type","Camera Color","Lips","DSLR Type","Watch Type","DSLR Lens Type","Material","DSLR Optical Zoom","Smart Garden:","Lens Type","Operating System","MemoryCard Capacity","Storage Type","Brand","Most Visited","Color Available","Capacity","Effective Pixels","Popular Picks","Input Type","Connectivity","Du Plans","Screen Size","Memory","Diaper Size","Primary Camera","Processor","Baby Weight","Type","Graphics Card","Wipe Count","Face","Storage Capacity","Nail","Color","TV Display Features","categories","Language ","TV Screen Size","Model","Tablet Type","Shoe Style","Periods","Tablet Screen Size","Shoes For","Dedicated Graphics Memory","Tablet Connectivity","Material Type","SSD Capacity","Array","Material Finish","Merchandise Type","Gaming Trends","Sole","Advertisement","Gaming Platform","Colors","Diaper Count","Offer","Printer Type","Gaming Bundles","Printer Output","TV Display Type","Printer Functions","Brushes Accessories","Printer Interface","Toys Type"]&facetFilters=[]';

        ## convert query string into array
        parse_str($parameters, $paramArray);

        $paramArray['query'] = ($query_key) ? $query_key : "iphone";

        array_push($this->requests, array("indexName" => 'liveen', "params" => http_build_query($paramArray,null,"&",PHP_QUERY_RFC3986)));

        $query = array(
            'apiKey' => "6ac7e952b25cae67a74606ab61ef1858",
            'appID' => "7A4J4NEATR",
            "requests" => $this->requests
        );

        $data = reset($this->sendPostData($this->url,json_encode($query))['results'])['hits'];

        ##SORT BY PRICE FROM LOW TO HIGH
        if($query_sort and $query_order == 1)
            usort($data, function($a, $b) {return $a['price'] - $b['price'];});

        ##SORT BY PRICE FROM HIGH TO LOW
        if($query_sort and $query_order == 2)
            usort($data, function($a, $b) {return $a['price'] < $b['price'];});


        echo json_encode($data, JSON_PRETTY_PRINT);

        exit();
    }

    public function display(){

        $this->file = 'home.tpl';
        $this->set("title", "AWOK");
        echo $this->output();

    }

}

$awok = new AWOK_SCRAP();

if(isset($_GET['query']))
    $awok->fetch();

$awok->display();