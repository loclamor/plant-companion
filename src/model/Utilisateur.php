<?php

class Model_Utilisateur extends Entite {
	
	public $id;
	public $name;
	public $password;
	
	public $DB_table = 'utilisateur';
    public $DB_equiv = array(
        'id' => 'id',
        'name' => 'name',
        'password' => 'password'
		);
	
	 
    // DBAttr => DBtype ()
    public $DB_type = array(
        'id' => 'int(11)',
        'name' => 'varchar(255) NOT NULL',
        'password' => 'varchar(255) NOT NULL'
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
    
    public function getPassword() {
    	return $this->password;
    }
    public function setPassword( $password ) {
    	$this->password = $password;
    }
}