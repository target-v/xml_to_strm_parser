xml_to_strm_parser
==================

Created for my personal use, maybe can help someone if it's in my situation, XBMC running on a Raspberry Pi to see national TV using rtmp streams. It is much more faster than use an XBMC addon, remember that the RPi as very few process capacity.

This script parse a stream list from a XML to create .strm. From a URL it takes a .xml file suposed to be maintained with rtmp streams.

This file has a syntax like:


	.
	.
	 <subitem>
    <title>Canal + Liga 2</title>	      
	<link>rtmp://50.7.133.138/iguide playpath=x4xacom3nv091wu swfUrl=http://cdn.iguide.to/player/secure_player_iguide_embed_token.swf live=1 pageUrl=http://www.iguide.to/ token=#ed%h0#w18623jsda6523lDGDve</link>
    <thumbnail>http://4.bp.blogspot.com/-eLNL3tImrsk/UXQy9FIX7iI/AAAAAAAAQqc/kaPotVff6zw/s1600/canal+++liga.jpg</thumbnail>
	</subitem>
	.
	.


 RUNNING IT:


Just ssh your machine our use the command line directly if you are on local and execute the script:

    php parser.php
 
It will generate a folder structure and all .strm files with the stream info.
