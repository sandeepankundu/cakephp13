<?php

echo $this->Form->create();
echo $this->Form->inputs(  array('title' , 'body') );
echo $this->Form->end('Save');

//echo json_encode($id);

?>