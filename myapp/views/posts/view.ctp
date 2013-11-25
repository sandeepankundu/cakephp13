<h1>
	<?php echo $post['Post']['title']; ?>
</h1>

<p>
	<?php echo $post['Post']['body']; ?>
</p>
<?php
echo json_encode($post);
?>