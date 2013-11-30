<?php 
/* app/controllers/photos_controller.php (Cake 1.2) */
class PhotosController extends AppController {

    public $components = array('RequestHandler');//, 'Security');

    

    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('add');
        $this->Auth->allow('download');
        $this->Auth->allow('thumbnail');
        $this->Auth->allow('mobile');
        $this->Auth->allow('feed');
        
        
        /*
        if($this->_isJSON()){
            $this->Auth->allow($this->action);
            $this->Security->loginOptions  = array(
                'type'  => 'basic',
                'realm' => 'My rest services',
                'login' => '_restLogin'
            );
            $this->Security->requireLogin();//$this->action);
            $this->Security->validatePost = false;
        }*/

        if($this->_isJSON() && !$this->RequestHandler->isGet()){
            $this->myDebugLog( '======================================================');
            $this->myDebugLog( '$_POST');
            $this->myDebugLog( $_POST);
            $this->myDebugLog( '======================================================');
            $this->myDebugLog( '$_FILES');
            $this->myDebugLog( $_FILES);
            $this->myDebugLog( '======================================================');

            if( empty($this->data) && !empty($_FILES)){
                $this->myDebugLog( 'setting it');
                $this->data[$this->modelClass] = $_FILES;
                $this->myDebugLog('model class');
                $this->myDebugLog($this->modelClass);

                $this->myDebugLog( 'data');
                $this->myDebugLog( $this->data);
            }
            $this->myDebugLog( '======================================================');
        }
    }

    protected function _restLogin($credentials){
        $model = $this->Auth->getModel();
        try{
                $id = $model->useToken($credentials['username']);
            if(empty($id)){
                $this->redirect(null, 503);
            }
        }catch(Exception $e){
            $id = null;
        }
        if(empty($id) || !$this->Auth->login(strval($id))){
            $this->Security->blackhole($this, 'login');
        }
    }

    public function beforeRender(){
        parent::beforeRender();
        if($this->_isJSON()){
            Configure::write('debug',0);
            //Configure::write('debug',2);
            $this->disableCache();
        }
    }


    function add() {
        if($this->_isJSON() && !$this->RequestHandler->isPost() ){
            $this->redirect(null, 400);
        }
        
        /*
        $this->myDebugLog( '======================================================');
        
        $this->myDebugLog( ' $this->request->data ====>');
        $this->myDebugLog( $this->params['form']['userfile']);
        //$this->myDebugLog( '=======>>>>>>>===============================================');
        //$this->myDebugLog( $this->RequestHandler);
        //$this->myDebugLog( '=======>>>>>>>===============================================');
        $this->myDebugLog('model class');
            $this->myDebugLog($this->modelClass);
        
        if($this->_isJSON() && empty($this->data) && !empty($this->params['form']) && !empty($this->params['form']['userfile'])){
            $this->myDebugLog( 'setting it');
            $this->data[$this->modelClass] = $_FILES;
            $this->myDebugLog('model class');
            $this->myDebugLog($this->modelClass);

            $this->myDebugLog( 'data');
            $this->myDebugLog( $this->data);
        }
        $this->myDebugLog( '======================================================');
        //$this->myDebugLog(  $_FILES );
        $this->myDebugLog( 'is _isJSON : ' . $this->_isJSON() );
        $this->myDebugLog( 'is data empty : ' . empty($this->data) );*/
           
        if(
            ( !$this->_isJSON() && !empty($this->data) && is_uploaded_file($this->data['Photo']['File']['tmp_name'])  )
            || ( 
                $this->_isJSON() && !empty($this->params['form'])
                && !empty($this->params['form']['userfile']) 
                && is_uploaded_file($this->params['form']['userfile']['tmp_name'])  )

            ){
            if($this->_isJSON()){
                
                $fileData = fread(fopen( $this->params['form']['userfile']['tmp_name'], "r"), 
                                     $this->params['form']['userfile']['size']);
                
                $this->data['Photo']['name'] = $this->params['form']['userfile']['name'];
                $this->data['Photo']['type'] = $this->params['form']['userfile']['type'];
                $this->data['Photo']['size'] = $this->params['form']['userfile']['size'];
                $this->data['Photo']['data'] = $fileData;
            }
            else{
                
                $fileData = fread(fopen($this->data['Photo']['File']['tmp_name'], "r"), 
                                     $this->data['Photo']['File']['size']);
                $this->data['Photo']['name'] = $this->data['Photo']['File']['name'];
                $this->data['Photo']['type'] = $this->data['Photo']['File']['type'];
                $this->data['Photo']['size'] = $this->data['Photo']['File']['size'];
                $this->data['Photo']['data'] = $fileData;
            }
                        
            $this->Photo->create();
            
            if($this->Photo->save($this->data)){
                $this->Session->setFlash('Photo uploaded successfully!!!');
                if($this->_isJSON() ){
                    $success = true;
                    $this->set(compact('success'));
                    //$this->redirect(null, 200);
                }
                else{
                    $this->redirect(array('controller' => 'photos', 'action' => 'add')); //@@TODO : change it to list/ list view /grid
                }
            }else{
                if($this->_isJSON() ){
                    $this->redirect(null, 403);
            
                    /*$errors = $this->Model->validationErrors;
                    $success = false;
                    $message = 'Validation Errors';
                    $step ='step 1';
                    $this->set(compact('success','message','errors','step'));
                    */
                }
                else{
                    $this->Session->setFlash('Please correct the errors marked below!!!');
                }
                
            }
            
        }
        else{
            if(!empty($this->data)){
                if($this->_isJSON() ){
                    $this->myDebugLog(' hey hey');
                    $this->redirect(null, 403);
                }
                $this->Session->setFlash('File not uploaded!!!');
            }
        }
    }

    function feed(){
        if($this->_isJSON() && !$this->RequestHandler->isGet() ){
            $this->redirect(null, 400);
        }

        $photos = $this->Photo->find('all');
        $this->set(compact('photos'));
    }
    
    function thumbnail($id) {
        //stream image size 100
        Configure::write('debug', 0);
        $file = $this->Photo->findById($id);
            
        header('Content-type: ' . $file['Photo']['type']);
        header('Content-length: ' . $file['Photo']['size']); // some people reported problems with this line (see the comments), commenting out this line helped in those cases
        header('Content-Disposition: attachment; filename="'.$file['Photo']['name'].'"');
        //echo $file['Photo']['thumbdata']; 
        //resize for thumbnail
        echo $file['Photo']['data'];                
        exit();
    }

    function mobile($id) {
        //stream image size 320
        Configure::write('debug', 0);
        $file = $this->Photo->findById($id);
            
        header('Content-type: ' . $file['Photo']['type']);
        header('Content-length: ' . $file['Photo']['size']); // some people reported problems with this line (see the comments), commenting out this line helped in those cases
        header('Content-Disposition: attachment; filename="'.$file['Photo']['name'].'"');
        //echo $file['Photo']['thumbdata'];
        //resize for mobile 320 px;  
        echo $file['Photo']['data'];              
        exit();
    }

    function download($id) {
        Configure::write('debug', 0);
        $file = $this->Photo->findById($id);
            
        header('Content-type: ' . $file['Photo']['type']);
        header('Content-length: ' . $file['Photo']['size']); // some people reported problems with this line (see the comments), commenting out this line helped in those cases
        header('Content-Disposition: attachment; filename="'.$file['Photo']['name'].'"');
        echo $file['Photo']['data'];
                
        exit();
    }


    /**
     * Create a generate thumbnail image from $inputFileName no taller or wider than 
     * $maxSize. Returns the new image resource or false on error.
     * Author: mthorn.net
     */
    /*
    protected function generatethumbnail($inputFileName, $maxSize = 100)
    {
        $this->myDebugLog("generatethumbnail -> 1");
        $info = getimagesize($inputFileName);
        $this->myDebugLog("generatethumbnail -> 2");

        $type = isset($info['type']) ? $info['type'] : $info[2];

        $this->myDebugLog("generatethumbnail -> 3");

 $this->myDebugLog("type -> ". $type . '  imagetype '.imagetypes());
        // Check support of file type
        //if ( !(imagetypes() & $type) )

        if ( !(imagetypes() &  (IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP | IMG_XPM ) ))
        {
            $this->myDebugLog("generatethumbnail -> 4");
            // Server does not support file type
            return false;
        }

        $this->myDebugLog("generatethumbnail -> 5");

        $width  = isset($info['width'])  ? $info['width']  : $info[0];
        $height = isset($info['height']) ? $info['height'] : $info[1];

$this->myDebugLog("generatethumbnail -> 6 ::: w: " . $width . ' - h: '. $height);
        // Calculate aspect ratio
        $wRatio = $maxSize / $width;
        $hRatio = $maxSize / $height;

$this->myDebugLog("generatethumbnail -> 7 ::: ratio -> w: " . $wRatio . ' - h: '.$hRatio);


$this->myDebugLog("$inputFileName : ". $inputFileName);

        // Using imagecreatefromstring will automatically detect the file type
        $sourceImage = imagecreatefromstring(file_get_contents($inputFileName));


$this->myDebugLog("$sourceImage : ". $sourceImage);


$this->myDebugLog("generatethumbnail -> 8");

        // Calculate a proportional width and height no larger than the max size.
        if ( ($width <= $maxSize) && ($height <= $maxSize) )
        {
            // Input is smaller than thumbnail, do nothing
            return $sourceImage;
        }
        elseif ( ($wRatio * $height) < $maxSize )
        {
            // Image is horizontal
            $tHeight = ceil($wRatio * $height);
            $tWidth  = $maxSize;
        }
        else
        {
            // Image is vertical
            $tWidth  = ceil($hRatio * $width);
            $tHeight = $maxSize;
        }

$this->myDebugLog("generatethumbnail -> X 1");

        $thumb = imagecreatetruecolor($tWidth, $tHeight);

$this->myDebugLog("generatethumbnail -> X 2");

        if ( $sourceImage === false )
        {
            // Could not load image
            return false;
        }

        // Copy resampled makes a smooth thumbnail
        imagecopyresampled($thumb, $sourceImage, 0, 0, 0, 0, $tWidth, $tHeight, $width, $height);
        imagedestroy($sourceImage);

$this->myDebugLog("generatethumbnail -> XXX");
        return $thumb;
    }
    */
    /**
     * Save the image to a file. Type is determined from the extension.
     * $quality is only used for jpegs.
     * Author: mthorn.net
     */
    /*
    protected function imageToFile($im, $fileName, $quality = 80)
    {
        if ( !$im || file_exists($fileName) )
        {
           return false;
        }

        $ext = strtolower(substr($fileName, strrpos($fileName, '.')));

        switch ( $ext )
        {
            case '.gif':
                imagegif($im, $fileName);
                break;
            case '.jpg':
            case '.jpeg':
                imagejpeg($im, $fileName, $quality);
                break;
            case '.png':
                imagepng($im, $fileName);
                break;
            case '.bmp':
                imagewbmp($im, $fileName);
                break;
            default:
                return false;
        }

        return true;
    }
    

    //$im = generatethumbnail('temp.jpg', 100);
    //imageToFile($im, 'temp-thumbnail.jpg');
    */
}

?>