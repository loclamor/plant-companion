<?php

class Model_Action extends Model_PrivateEntite {
	
	public $id;
	public $date;
	public $vegetable;
	public $vegetableObj;
	public $title;
	public $comment;
	public $type_action;
	public $image_path;
	public $utilisateur;
	
	
	public $DB_table = 'action';
    public $DB_equiv = array(
        'id' => 'id',
        'vegetable' => 'vegetable_id',
        'date' => 'date',
        'title' => 'title',
        'comment' => 'comment',
        'type_action' => 'type_action',
        'image_path' => 'image_path',
        'utilisateur' => 'utilisateur_id'
		);
	
	 
    // DBAttr => DBtype ()
    public $DB_type = array(
        'id' => 'int(11)',
        'utilisateur_id' => 'int(11) default 0',
        'vegetable_id' => 'int(11)',
        'date' => 'datetime',
        'title' => 'varchar(255) NOT NULL',
        'comment' => 'TEXT',
        'type_action' => 'varchar(255) NOT NULL',
        'image_path' => 'varchar(255)'
        );
        
    public $TYPES_ACTION = array(
    	'observation' => 'Observation',
		'ajout_engrais' => 'Ajout d\'engrais',
		'taille' => 'Taille',
		'rempotage' => 'Rempotage',
		'ceuillette' => 'Ceuillette'
    	);
    	
    public $TITLE_OBSERVATION = array(
    	'Fleurs' => 'Fleurs',
		'Fruits' => 'Fruits',
		'Maladie' => 'Maladie',
		'Auxiliaire' => 'Auxiliaire',
		'Ravageur' => 'Ravageur',
		'nouvelle_feuille' => 'Nouvelle feuille'
    	);
        
    public $donotSyncDatabase = array( 'vegetableObj', 'TYPES_ACTION', 'TITLE_OBSERVATION');
    public $donotSerialize = array();
    
    public function getId() {
    	return $this->id;
    }
    public function setId( $id ) {
    	$this->id = $id;
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
    
    public function getDate() {
    	$dateTime = new DateTime($this->date);
    	return $dateTime->format('d/m/Y');
    }
    public function setDate( $date ) {
    	$this->date = $date;
    }
    
    public function getTitle() {
    	return $this->title;
    }
    public function setTitle( $title ) {
    	$this->title = $title;
    }
    
    public function getComment() {
    	return $this->comment;
    }
    public function setComment( $comment ) {
    	$this->comment = $comment;
    }
    
    public function getTypeAction() {
    	return $this->type_action;
    }
    public function setTypeAction( $type_action ) {
    	$this->type_action = $type_action;
    }
    
    public function getImagePath() {
    	return $this->image_path;
    }
    public function setImagePath( $image_path ) {
    	$this->image_path = $image_path;
    }
}