<?php 
    echo $form->create('Photo', array('action' => 'add', 'type' => 'file'));
    echo $form->file('File');
    echo $form->submit('Upload');
    echo $form->end();

    //$errors = $this->ModelName->validationErrors;
    //echo 'erroes : ' .json_encode($errors);
?>