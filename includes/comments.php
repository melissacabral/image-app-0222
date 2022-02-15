<?php //get all the approved comments on this post, oldest first
$result = $DB->prepare('SELECT c.body, c.date, u.username, u.profile_pic, u.user_id
						FROM comments AS c, users AS u
						WHERE c.user_id = u.user_id
							AND c.post_id = :id
							AND c.is_approved = 1
						ORDER BY c.date ASC
						LIMIT 100
						');
$result->execute( array( 'id' => $post_id ) );
$total = $result->rowCount();
if( $total > 0 ){
?>
<div class="comments">
	<h2>
		<?php echo $total == 1 ? 'One Comment' : "$total Comments"; ?>	
	</h2>
	<?php 
	while( $row = $result->fetch() ){ 
		extract( $row );
	?>
	<div class="one-comment">
		<div class="user">
			<img src="<?php echo $profile_pic; ?>" 
				width="40" height="50" 
				alt="<?php echo $username; ?>">
			<?php echo htmlspecialchars( $username ); ?>
		</div>
		<p><?php echo  $body; ?></p>
		<span class="date"><?php echo time_ago( $date ); ?></span>
	</div>
	<?php } //end while ?>
</div>
<?php }//end if there are comments ?>