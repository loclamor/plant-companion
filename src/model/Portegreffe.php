<?php
class Model_Portegreffe extends Model_PrivateEntite {
	
	public $id;
	public $name;
	public $type;
	public $typeObj;
	public $utilisateur;
	
	public $DB_table = 'porte_greffe';
    public $DB_equiv = array(
        'id' => 'id',
        'name' => 'name',
        'type' => 'type_id',
        'utilisateur' => 'utilisateur_id'
		);
	
	 
    // DBAttr => DBtype ()
    public $DB_type = array(
        'id' => 'int(11)',
        'utilisateur_id' => 'int(11) default 0',
        'name' => 'varchar(255) NOT NULL',
        'type_id' => 'int(11)'
        );
        
    public $donotSyncDatabase = array('typeObj');
    public $donotSerialize = array('typeObj');
    
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
    
    public function getType() {
    	if($this->typeObj === null) {
    		$this->typeObj =  Gestionnaire::getGestionnaire('Type')->getOne($this->type);
    	}
    	return $this->typeObj;
    }
    public function setType( $type_id ) {
    	$this->type = $type_id;
    }
    
}