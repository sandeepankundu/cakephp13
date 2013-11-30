<h1>All Photos</h1>
<?php

$rootOutput = array(
	'title' => 'Evolvic Uploads',
	'link'	=> Router::url( $this->here, true ),
	'description'=> 'Image Feed Generated for image uploader/viewer for Evolvic - Proof of Concept',
	'generator'=> Router::url('/', true)
);


foreach ($photos as $key => $eachphoto) {
	$eachphotoDesc['title'] = $eachphoto['Photo']['name'] ;
	$eachphotoDesc['id'] = $eachphoto['Photo']['id'] ;
	$eachphotoDesc['link'] =  $this->Html->url(  array('action' => 'download' , $eachphoto['Photo']['id'] ), true);
	$eachphotoDesc['media']['m']  =  $this->Html->url(  array('action' => 'mobile' , $eachphoto['Photo']['id'] ), true);
	$eachphotoDesc['media']['t']  =  $this->Html->url(  array('action' => 'thumbnail' , $eachphoto['Photo']['id'] ), true);
	//$eachphotoDesc['thumb_url'] =  $this->Html->url(  array('action' => 'thumbnail' , $eachphoto['Photo']['id'] ), true);

	$rootOutput['items'][$key] = $eachphotoDesc;
}

echo '<pre>'.json_encode($rootOutput) .'</pre><br/>';

?>