<?php if( DEBUG_MODE ){ ?>
<div class="debug-output">		
	<h2>Database Response</h2>
	<pre><?php print_r( $DB->errorInfo() ); ?></pre>
	
	<h2>POST data:</h2>
	<pre><?php print_r( $_POST ); ?></pre>

	<h2>GET data:</h2>
	<pre><?php print_r( $_GET ); ?></pre>

	<h2>COOKIE data:</h2>
	<pre><?php print_r( $_COOKIE ); ?></pre>	

	<h2>SESSION data:</h2>
	<pre><?php print_r( $_SESSION ); ?></pre>

	<h2>FILES data:</h2>
	<pre><?php print_r( $_FILES ); ?></pre>

	<h2>SERVER data</h2>
	<pre><?php print_r( $_SERVER ); ?></pre>
</div>
<?php } ?>