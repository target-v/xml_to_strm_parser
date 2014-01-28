<?php
//Include script functions
include_once 'functions.php';

parse_xml_to_strms("https://www.dropbox.com/s/d7nqkfxh0hn9y9g/streams.xml","channels1");

parse_xml_to_strms("http://dl.dropbox.com/u/4735170/streams.xml","channels2");

parse_xml_to_strms("http://dl.dropboxusercontent.com/u/142085967/Lista2.xml","channels3");

parse_xml_to_strms("http://dl.dropboxusercontent.com/u/8036850/livesports.xml","channels4");

parse_xml_to_strms("http://dl.dropboxusercontent.com/u/108091935/Lista.xml","channels5");

?>
