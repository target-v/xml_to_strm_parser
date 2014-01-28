<?php

function check_new_content($url, $hash_md5){
        if(md5(file_get_contents($url))==$hash_md5){
                return false;
        }else{
                return true;
        }
}

function load_persistent_data(&$data, $file_name){
        $content=file_get_contents(dirname(__FILE__)."/".$file_name);
        $data=unserialize($content);
}

function save_persistent_data($data, $file_name){
        $string = serialize($data);
        return file_put_contents(dirname(__FILE__)."/".$file_name, $string);
}


function parse_xml_to_strms($url, $folder_name){
	
	$file_name = $folder_name.".xml";

	exec("wget ".$url." ".dirname(__FILE__)."/ -O ".$file_name);

	//Inser file lines into array
	$handle = fopen($file_name, "r");
	$line_array = array();
	$i=0;
	while (($line = fgets($handle)) !== false) {
		$line_array[$i]=$line;
		$i++;
	}
	fclose($handle);
	//remove innecesary file
	exec("rm ".dirname(__FILE__)."/".$file_name);

	//tags to remove
	$to_remove = array("<title>", "</title>", "<link>", "</link>", "\t", "\n");

	//creating strm files;

	exec("rm ".dirname(__FILE__)."/".$folder_name." -R");
	exec("mkdir ".dirname(__FILE__)."/".$folder_name);
	$rtmp_array = array();
	$j=0;$k=0;
	while ($j < $i) {
		if (strpos($line_array[$j],'OFF') == false && strpos($line_array[$j],'<title>') !== false && strpos($line_array[$j+1],'<link>rtmp://') !== false){

			$title=clean_n(str_replace($to_remove, "", $line_array[$j]));
			$rtmp_string=str_replace($to_remove, "", $line_array[$j+1]);

			file_put_contents(dirname(__FILE__)."/".$folder_name."/".$title.".strm", $rtmp_string);

			echo $title.".strm -- ".$rtmp_string."<br><br>\n\n";
			$k++;$k++;
		}elseif (strpos($line_array[$j],'Actualizado') !== false ){
			$title=clean_n(str_replace($to_remove, "", $line_array[$j]));
			file_put_contents(dirname(__FILE__)."/".$folder_name."/".$title.".strm", "");
		}
		$j++;
	}
	unset($rtmp_array);
	unset($line_array);
}

function clean_n($str){
	return preg_replace("/[^a-zA-Z0-9+_]/", "",$str);
}


?>
