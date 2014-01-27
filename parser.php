<?php
//Include script functions
include_once 'functions.php';

$folder_name="channels1";
$url="http://dl.dropbox.com/u/4735170/streams.xml";
parse_xml_to_strms($url,$folder_name, "streams.xml");

$folder_name="channels2";
$url="http://dl.dropboxusercontent.com/u/142085967/Lista.xml";
parse_xml_to_strms($url,$folder_name, "Lista.xml");

$folder_name="channels3";
$url="http://dl.dropboxusercontent.com/u/8036850/livesports.xml";
parse_xml_to_strms($url,$folder_name, "livesports.xml");

$folder_name="channels4";
$url="http://dl.dropboxusercontent.com/u/108091935/Lista.xml";
parse_xml_to_strms($url,$folder_name, "Lista.xml");

?>
