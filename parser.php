<?php
//Include script functions
include_once 'functions.php';

//Script options
$url="http://dl.dropbox.com/u/4735170/streams.xml";
//$url="http://dl.dropboxusercontent.com/u/142085967/Lista2.xml";
$folder_name="channels";
$persistent_file_name="persistent_data.txt";

//Script
$persisten_data=array();
load_persistent_data( $persisten_data, $persistent_file_name);

$data = array("last_file_md5"=>md5(file_get_contents($url)));

if (empty($persisten_data)){
	//There is no file, we create one
	echo "NO FILE";
	save_persistent_data($data, $persistent_file_name);
}

$data = array("last_file_md5"=>md5(file_get_contents($url)));

if (check_new_content($url, $persisten_data['last_file_md5'])==true){
	//There is new content
	echo "NEW CONTENT";
	parse_xml_to_strms($url,$folder_name);
	save_persistent_data($data, $persistent_file_name);
}else{
	echo "NO NEW CONTENT";
}

?>
