<?php
class Model_Lieu extends Model_PrivateEntite {
	
	public $id;
	public $name;
	public $utilisateur;
	
	public $DB_table = 'lieu';
    public $DB_equiv = array(
        'id' => 'id',
        'name' => 'name',
        'utilisateur' => 'utilisateur_id'
		);
	
	 
    // DBAttr => DBtype ()
    public $DB_type = array(
        'id' => 'int(11)',
        'utilisateur_id' => 'int(11) default 0',
        'name' => 'varchar(255) NOT NULL'
        );
        
    public $donotSyncDatabase = array();
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
    
}