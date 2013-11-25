<p>Click the <strong>Delete</strong> button to delete</p> the post <?php echo $post['Post']['title']; ?> <p>

<?php
echo $this->Form->create(arrray('url'=>array('action'=>'delete', $post['Post']['id'])));
echo $this->Form->hidden('Post.id', array('value'=>$post['Post']['id']));
echo $this->Form->end('Delete');
?>