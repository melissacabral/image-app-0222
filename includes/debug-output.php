<?php if( DEBUG_MODE ){ ?>
<div class="debug-output">
	<h2>Logged In User Info</h2>
	<pre><?php print_r( $logged_in_user ); ?></pre>

	<h2>Variables</h2>
	<pre><?php print_r( get_defined_vars() ); ?></pre>	

	<h2>SERVER</h2>
	<pre><?php print_r( $_SERVER ); ?></pre>	
	
</div>
<?php } ?>