		<footer class="footer">
			this is the footer
		</footer>
	</div> <!-- end .site -->

	<?php include('includes/debug-output.php'); ?>
	<!-- Additions to the footer. Deferred JS scripts. Add this before </body>-->
	<?php if($logged_in_user){ ?>
		<script type="text/javascript">
			//LIKE/UNLIKE	
			document.body.addEventListener('click', function(e){
				if (e.target.className == 'heart-button'){
				   //console.log(e.target.dataset.postid)
				   likeUnlike(e.target)
				}
			});

			async function likeUnlike( el ){
				let postId = el.dataset.postid
				let userId = <?php echo $logged_in_user['user_id']; ?>;
				//get the container that will be updated after liking
				let container = el.closest('.likes')

				//console.log(postId, userId)
				let formData = new FormData()
				formData.append('postId', postId)
				formData.append('userId', userId)
				
				let response = await fetch("fetch-handlers/like-unlike.php", {
					method:'POST',
					body: formData
				})
				if (response.ok) {
					let result = await response.text()
				    	// console.log('ok')
				    	container.innerHTML = result

	    } else {
	    	console.log(response.status)
	    }
	}
</script>
<?php } ?>
<!-- end script  Additions -->
</body>
</html>