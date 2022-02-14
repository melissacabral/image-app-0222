#get all posts and comment count, even ones without comments
SELECT posts.*,  COUNT(comments.comment_id) AS total, users.username
FROM posts
	LEFT JOIN comments
	ON posts.post_id = comments.post_id
    LEFT JOIN users
    ON posts.user_id = users.user_id
    LEFT JOIN categories
    ON posts.category_id = categories.category_id
GROUP BY posts.post_id 