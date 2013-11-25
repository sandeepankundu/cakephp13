<h1>Posts</h1>

<?php	if(!empty($posts)){ ?>

	<ul>
		<?php foreach ($posts as $post ) { ?>
			<li>
				<?php echo $this->Html->link($post['Post']['title'],  array('action' => 'view' , $post['Post']['id'] )); ?>
				:
				<?php echo $this->Html->link('Edit',  array('action' => 'edit' , $post['Post']['id'] )); ?>
				-
				<?php echo $this->Html->link('Delete',  array('action' => 'delete' , $post['Post']['id'] )); ?>
			</li>		
		<?php } ?>
	</ul>
<?php } ?>
<?php echo $this->Html->link('Create a new Post', array('action'=>'add')); ?>
