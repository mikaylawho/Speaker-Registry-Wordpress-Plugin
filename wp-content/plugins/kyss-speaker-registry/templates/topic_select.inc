<?php
/**
 * Created by PhpStorm.
 * User: mikelhensley
 * Date: 9/17/14
 * Time: 3:48 PM
 * The Topic Picker code that is used on the Speaker Archive and Topics templates.
 */

$topics = get_terms( 'topics', array(
		'orderby'    => 'name',
		'order'      => 'ASC'
	)
);
?>

<script type="text/javascript">

	function changeTopic(value)
	{

		window.location.href = value;
	}



</script>


<div id='topic_select_div'>
	<select id="topic_select" onchange="changeTopic(this.value);">
		<option>Select a topic</option>
		<?php
		if( !empty( $topics ) && !is_wp_error( $topics ) ){

			foreach($topics as $topic){ ?>
				<!-- TODO: find a way to replace hardcoded 'topics' below with a lookup -->
				<option value="<?php echo site_url() . '/topics/' . $topic->slug; ?>"><?php echo $topic->name; ?></option>

			<?php }
		} ?>
	</select>
</div>