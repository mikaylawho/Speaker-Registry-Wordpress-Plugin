
<?php
include ('/Users/mikelhensley/Sites/wp_phpstorm/wp-load.php');

for( $x=0;$x<50;$x++) {

	echo "speaker " . $x . " created";

	$to_insert = array();

	$to_insert['post_author']           = 0;
	$to_insert['post_content_filtered'] = '<h1>Here is my info</h1>\n<ul><li>one</li><li>two</li></ul>\n';
	$to_insert['post_type']             = 'speaker';
	$to_insert['post_title']            =  strval($x) . 'Test Speaker';

	wp_insert_post( $to_insert );
}