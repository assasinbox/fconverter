<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alex
 * Date: 06.10.13
 *
 * Read and Write Classes to help for converter
 */

class JsonHelper {

    public $content_type = 'application/json';

    public function read($file_path){
        $data = file_get_contents($file_path);
        return json_decode($data);
    }

    public function write($data){
        return json_encode($data);
    }

}

class XMLHelper {

    public $content_type = 'text/xml';

    public function read($file_path){
        $data = file_get_contents($file_path);
        return new SimpleXMLElement($data);
    }

    public function write($data){
        $data = object_to_array($data);
        return array_to_xml($data);
    }

}

class CSVHelper {

    public $content_type = 'text/csv';

    public function read($file_path){

        $data = array();

        if(($handle = fopen($file_path, 'r')) !== FALSE) {

            while(($row = fgetcsv($handle, 1000, ';')) !== FALSE)
                array_push($data, $row);

            fclose($handle);
        }
        return $data;

    }

    public function write($data){

        $data = object_to_array($data);

        return array_to_csv($data);

    }

}

function prn($content)
{
    echo '<pre style="background: lightgray; border: 1px solid black;">';
    print_r($content);
    echo '</pre>';
}

function object_to_array($obj)
{
    $arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
    $arr = array();
    foreach ($arrObj as $key => $val) {
        $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

function multi_implode($array, $glue) {
    $ret = '';

    foreach ($array as $item) {
        if (is_array($item)) {
            $ret .= multi_implode($item, $glue) . $glue;
        } else {
            $ret .= $item . $glue;
        }
    }

    $ret = substr($ret, 0, 0-strlen($glue));

    return $ret;
}

function array_to_xml($data){
    ob_start();
    echo "<?xml version='1.0' encoding='UTF-8' standalone='yes' ?>\n";
    to_xml($data);
    return ob_get_clean();
}

function to_xml($data, $parent_node = 'item'){
    foreach($data as $key => $val){

        if (is_numeric($key))
            $key = $parent_node;

        echo "<" . $key . ">";
        if(is_array($val))
            to_xml($val, $key);
        else
            echo $val."";
        echo "</" . $key . ">\n";
    }
}

function array_to_csv($data){
    $scv_string = '';
    foreach($data as $val){
        foreach($val as &$item){
            if(is_array($item))
                $item .= multi_implode($val, ';') . ">\n";

        }
        $scv_string .= implode(';', $val) . "\n";

    }
    return $scv_string;
}

