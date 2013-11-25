<?php
	foreach ($posts as $i => $post) {
		$post['Post']['url'] = $this->Html->url( array(
			'action' => 'view',
			$post['Post']['id']
		), true);
		$posts[$i] = $post;
	}
	echo json_encode($posts);
?>