<?php

class Model_Photo extends Model_PrivateEntite {
	
	public $id;
	public $path;
	public $vegetable;
	public $vegetableObj;
	public $action;
	public $actionObj;
	public $utilisateur;
	
	public $DB_table = 'photo';
    public $DB_equiv = array(
        'id' => 'id',
        'path' => 'path',
        'vegetable' => 'vegetable_id',
        'action' => 'action_id',
        'utilisateur' => 'utilisateur_id'
    );
    
    // DBAttr => DBtype ()
    public $DB_type = array(
        'id' => 'int(11)',
        'utilisateur_id' => 'int(11) default 0',
        'path' => 'varchar(255) NOT NULL default \'\'',
        'vegetable_id' => 'int(11)',
        'action_id' => 'int(11)'
    );
    
    public $donotSyncDatabase = array( 'vegetableObj', 'actionObj');
    public $donotSerialize = array();
    
    public function getId() {
    	return $this->id;
    }
    public function setId( $id ) {
    	$this->id = $id;
    }
    
    public function getPath() {
    	return $this->path;
    }
    public function setPath( $path ) {
    	$this->path = $path;
    }
    
    public function getVegetable() {
    	if($this->vegetableObj === null) {
    		$this->vegetableObj =  Gestionnaire::getGestionnaire('Vegetable')->getOne($this->vegetable);
    	}
    	return $this->vegetableObj;
    }
    public function setVegetable( $vegetable_id ) {
    	$this->vegetable = $vegetable_id;
    }
    
    public function getAction() {
    	if($this->actionObj === null) {
    		$this->actionObj =  Gestionnaire::getGestionnaire('Action')->getOne($this->action);
    	}
    	return $this->actionObj;
    }
    public function setAction( $action_id ) {
    	$this->action = $action_id;
    }
    
    static function manageUpload($tmpFile, $originalName) {
    	$uiniq = uniqid('', true);
    	$uploaddir = './uploads/' . substr($uniq, 0, 2) . '/';
		mkdir($uploaddir, 0777, true);
		$uploadfile = $uploaddir . $uiniq . '.' . array_pop(explode('.', $originalName));
		move_uploaded_file($tmp_path, $uploadfile);
		return $uploadfile;
    }
    
    public function getThumbPath($type = 'H', $size = 120, $generate = true) {
    	$filePath = $this->getPath();
    	if (empty($filePath) || !file_exists($filePath) || is_dir($filePath)) {
    		$filePath = './plante.png';
    	}
    	$path_parts = pathinfo($filePath);
    	$minUrl = $path_parts['dirname'].'/'.$path_parts['filename'].'.min'.$type.$size.'.'.$path_parts['extension'];
    	if(!file_exists($minUrl) && $generate) {
			return redimJPEG($filePath, $size, $type, $minUrl);
		}
		else {
			return $minUrl;
		}
    }
}