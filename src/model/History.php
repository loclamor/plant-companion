<?php

class Model_History extends Model_PrivateEntite {
	
	public $id;
	public $key;
	public $entity_id;
	public $oldValue;
	public $newValue;
	public $date;
	public $utilisateur;
	
	public $DB_table = 'history';
    public $DB_equiv = array(
        'id' => 'id',
        'entity_id' => 'entity_id',
        'key' => 'key',
        'oldValue' => 'oldValue',
        'newValue' => 'newValue',
        'date' => 'date',
        'utilisateur' => 'utilisateur_id'
		);
	
	 
    // DBAttr => DBtype ()
    public $DB_type = array(
        'id' => 'int(11)',
        'utilisateur_id' => 'int(11) default 0',
        'entity_id' => 'int(11)',
        'key' => 'varchar(255) NOT NULL',
        'oldValue' => 'varchar(255) NOT NULL',
        'newValue' => 'varchar(255) NOT NULL',
        'date' => 'datetime',
        );
        
    public $donotSyncDatabase = array();
    public $donotSerialize = array();
    
    /**
     * @param $history array{key, old, new}
     **/
    public function setHistory($entity_id, array $history) {
    	$this->entity_id = $entity_id;
    	$this->key = $history[0];
    	$this->oldValue = $this->formatValue($history[1]);
    	$this->newValue = $this->formatValue($history[2]);
    	$this->date = date('Y-m-d H:i:s');
    	$this->utilisateur = $_SESSION['utiliateur_id'];
    }
    
    protected function formatValue($value) {
    	if ($value === null || $value === '') {
    		return '- Sans valeur -';
    	}
    	if ($value === true || $value === false) {
    		return $value ? 'Oui' : 'Non';
    	}
    	return "$value";
    }
    
}