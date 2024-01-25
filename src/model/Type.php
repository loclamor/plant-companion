<?php
class Model_Type extends Model_PrivateEntite {
	
	public $id;
	public $name;
	public $parent;
	public $parentObj;
	public $utilisateur;
	
	public $DB_table = 'type';
    public $DB_equiv = array(
        'id' => 'id',
        'name' => 'name',
        'parent' => 'parent_id',
        'utilisateur' => 'utilisateur_id'
		);
	
	 
    // DBAttr => DBtype ()
    public $DB_type = array(
        'id' => 'int(11)',
        'utilisateur_id' => 'int(11) default 0',
        'name' => 'varchar(255) NOT NULL',
        'parent_id' => 'int(11)'
        );
        
    public $donotSyncDatabase = array( 'parentObj');
    public $donotSerialize = array();
    
    public function getId() {
    	return $this->id;
    }
    public function setId( $id ) {
    	$this->id = $id;
    }
    
    public function getName() {
    	return $this->name;
    }
    public function setName( $name ) {
    	$this->name = $name;
    }
    
    public function getParent() {
    	if($this->parentObj === null) {
    		$this->parentObj =  Gestionnaire::getGestionnaire(__CLASS__)->getOne($this->parent);
    	}
    	return $this->parentObj;
    }
    public function setParent( $parent_id ) {
    	$this->parent = $parent_id;
    }
}
