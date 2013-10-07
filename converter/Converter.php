<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alex
 * Date: 06.10.13
 */

require 'RWHelpers.php';

class Converter {

    private $input_format = '';
    private $output_format = '';
    private $data = array();
    private $output_data = '';
    private $content_type = '';

    public function convert($file_name, $file_path, $output_format, $output = true){
        $this->input_format =  $this->getFormat($file_name);
        $this->output_format = $output_format;

        $this->read($file_path);
        //prn($this->output_format);
        //prn($this->data);
        //dei();
        $this->write();
        //prn($this->output_data);
        //die();

        if ($output)
            $this->output();
        else
            return $this->output_data;
    }

    public function getFormat($file){
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    public function read($file_path){
        $read_helper = $this->getRWHelper($this->input_format);
        $this->data = $read_helper->read($file_path);
    }

    public function write(){
        $write_helper = $this->getRWHelper($this->output_format);
        $this->output_data = $write_helper->write($this->data);
        $this->content_type = $write_helper->content_type;
    }

    public function getRWHelper($format){
        switch($format){
            case 'json':
                $helper = new JsonHelper();
                break;

            case 'xml':
                $helper =  new XMLHelper();
                break;

            case 'csv':
                $helper = new CSVHelper();
                break;
        }

        return $helper;
    }

    private function output(){
        header("Content-type: " . $this->content_type);
        header("Content-Disposition: attachment; filename=converted_data." . $this->output_format);
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $this->output_data;
    }
}