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

	$data_array=xml2array($url);

	exec("rm ".dirname(__FILE__)."/".$folder_name." -R");
	exec("mkdir ".dirname(__FILE__)."/".$folder_name);

	exec("mkdir ".dirname(__FILE__)."/".$folder_name."/ALL_STRM");
	foreach ($data_array["channels"]["channel"] as &$channel) {
		$channel_name=clean_n($channel["name"]);
		exec("mkdir ".dirname(__FILE__)."/".$folder_name."/".$channel_name);

		foreach ($channel["subchannel"] as &$subchannel) {
			$subchannel_name=clean_n($subchannel["name"]);
			exec("mkdir ".dirname(__FILE__)."/".$folder_name."/".$channel_name."/".$subchannel_name);

			foreach ($subchannel["subitems"] as &$subitem) {
				foreach ($subitem as &$item){
					$item_title=clean_n($item["title"]);

					if(strncmp($item_title, "OFF", 3)!=0){ //we dont create named OFF channels
						file_put_contents(dirname(__FILE__)."/".$folder_name."/".$channel_name."/".$subchannel_name."/".$item_title.".strm", $item["link"]);
						file_put_contents(dirname(__FILE__)."/".$folder_name."/ALL_STRM/".$item_title.".strm", $item["link"]);
						echo $folder_name."/".$channel_name."/".$subchannel_name."/".$item_title.".strm </br> \n";
						//var_dump($item);
					}

				}

			}

		}

	}
}

function clean_n($str){
	return preg_replace("/[^a-zA-Z0-9+]/", "",$str);
}

function xmlEscape($string) {
	return str_replace(array('&',  '\''), array('&amp;', '&apos;' ), $string);
}

function xml2array($url, $get_attributes = 1, $priority = 'tag')
{
	$contents = "";
	if (!function_exists('xml_parser_create'))
	{
		return array ();
	}
	$parser = xml_parser_create('');

	$contents .= file_get_contents($url);
	$contents = xmlEscape($contents);

	xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, trim($contents), $xml_values);
	xml_parser_free($parser);
	if (!$xml_values)
		return; //Hmm...
	$xml_array = array ();
	$parents = array ();
	$opened_tags = array ();
	$arr = array ();
	$current = & $xml_array;
	$repeated_tag_index = array ();
	foreach ($xml_values as $data)
	{
		unset ($attributes, $value);
		extract($data);
		$result = array ();
		$attributes_data = array ();
		if (isset ($value))
		{
			if ($priority == 'tag')
				$result = $value;
			else
				$result['value'] = $value;
		}
		if (isset ($attributes) and $get_attributes)
		{
			foreach ($attributes as $attr => $val)
			{
				if ($priority == 'tag')
					$attributes_data[$attr] = $val;
				else
					$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
			}
		}
		if ($type == "open")
		{
			$parent[$level -1] = & $current;
			if (!is_array($current) or (!in_array($tag, array_keys($current))))
			{
				$current[$tag] = $result;
				if ($attributes_data)
					$current[$tag . '_attr'] = $attributes_data;
				$repeated_tag_index[$tag . '_' . $level] = 1;
				$current = & $current[$tag];
			}
			else
			{
				if (isset ($current[$tag][0]))
				{
					$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
					$repeated_tag_index[$tag . '_' . $level]++;
				}
				else
				{
					$current[$tag] = array (
							$current[$tag],
							$result
					);
					$repeated_tag_index[$tag . '_' . $level] = 2;
					if (isset ($current[$tag . '_attr']))
					{
						$current[$tag]['0_attr'] = $current[$tag . '_attr'];
						unset ($current[$tag . '_attr']);
					}
				}
				$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
				$current = & $current[$tag][$last_item_index];
			}
		}
		elseif ($type == "complete")
		{
			if (!isset ($current[$tag]))
			{
				$current[$tag] = $result;
				$repeated_tag_index[$tag . '_' . $level] = 1;
				if ($priority == 'tag' and $attributes_data)
					$current[$tag . '_attr'] = $attributes_data;
			}
			else
			{
				if (isset ($current[$tag][0]) and is_array($current[$tag]))
				{
					$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
					if ($priority == 'tag' and $get_attributes and $attributes_data)
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
					}
					$repeated_tag_index[$tag . '_' . $level]++;
				}
				else
				{
					$current[$tag] = array (
							$current[$tag],
							$result
					);
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if ($priority == 'tag' and $get_attributes)
					{
						if (isset ($current[$tag . '_attr']))
						{
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset ($current[$tag . '_attr']);
						}
						if ($attributes_data)
						{
							$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
						}
					}
					$repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
				}
			}
		}
		elseif ($type == 'close')
		{
			$current = & $parent[$level -1];
		}
	}
	return ($xml_array);
}

?>
