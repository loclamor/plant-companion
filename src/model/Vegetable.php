<?php

class Model_Vegetable extends Model_PrivateEntite {
	
	public $id;
	public $name;
	public $creation_date;
	public $add_date;
	public $type;
	public $typeObj;
	public $group;
	public $groupObj;
	public $parent;
	public $parentObj;
	public $type_origine;
	public $porte_greffe;
	public $porte_greffeObj;
	public $lieu_origine;
	public $lieu_origineObj;
	public $nom_latin;
	public $rusticite;
	public $mois_fleur_debut;
	public $mois_fleur_fin;
	public $p_fleur;
	public $mois_fructi_debut; // enfait c'est récolte
	public $mois_fructi_fin; // enfait c'est récolte
	public $p_fructi; // enfait c'est récolte
	public $default_photo;
	public $default_photoObj;
	public $utilisateur;
	
	public $DB_table = 'vegetable';
    public $DB_equiv = array(
        'id' => 'id',
        'name' => 'name',
        'creation_date' => 'creation_date',
        'add_date' => 'add_date',
        'type' => 'type_id',
        'group' => 'group_id',
        'parent' => 'parent_id',
        'type_origine' => 'type_origine',
		'porte_greffe' => 'porte_greffe',
		'lieu_origine' => 'lieu_origine',
		'nom_latin' => 'nom_latin',
		'rusticite' => 'rusticite',
		'mois_fleur_debut' => 'mois_fleur_debut',
		'mois_fleur_fin' => 'mois_fleur_fin',
		'mois_fructi_debut' => 'mois_fructi_debut',
		'mois_fructi_fin' => 'mois_fructi_fin',
		'p_fleur' => 'p_fleur',
		'p_fructi' => 'p_fructi',
		'default_photo' => 'default_photo',
		'utilisateur' => 'utilisateur_id'
    );
    
    // DBAttr => DBtype ()
    public $DB_type = array(
        'id' => 'int(11)',
        'name' => 'varchar(255) NOT NULL',
        'creation_date' => 'datetime',
        'add_date' => 'datetime',
        'type_id' => 'int(11)',
        'group_id' => 'int(11)',
        'parent_id' => 'int(11)',
        'type_origine' => 'varchar(255) DEFAULT NULL',
		'porte_greffe' => 'int(11) DEFAULT NULL',
		'lieu_origine' => 'int(11) DEFAULT NULL',
		'nom_latin' => 'varchar(255) DEFAULT NULL',
		'rusticite' => 'int(11) DEFAULT NULL',
		'mois_fleur_debut' => 'int(11) DEFAULT NULL',
		'mois_fleur_fin' => 'int(11) DEFAULT NULL',
		'mois_fructi_debut' => 'int(11) DEFAULT NULL',
		'mois_fructi_fin' => 'int(11) DEFAULT NULL',
		'p_fleur' => 'varchar(255) DEFAULT NULL',
		'p_fructi' => 'varchar(255) DEFAULT NULL',
		'default_photo' => 'int(11) DEFAULT NULL',
		'utilisateur_id' => 'int(11) default 0',
    );
    
    public $TYPES_ORIGNE = array(
    		'bouture' => 'Bouture',
    		'semis' => 'Semis',
    		'greffe' => 'Greffe',
    		'marcottage' => 'Marcottage'
    	);
    	
    public $PERIODES = array(
    		'Printemps',
    		'Eté',
    		'Automne',
    		'Hivers',
    		'4 Saisons',
    		'Printemps - Automne'
    	);
	public $PERIODES_MOIS = array( // arrays [mois_debut, mois_fin]
		'Printemps' => [3, 5],
		'Eté' => [6, 8],
		'Automne' => [9, 11],
		'Hivers' => [12, 2],
		'4 Saisons' => [8, 7],
		'Printemps - Automne' => [3, 5] // WARN : need special case here
	);
    
    public $donotSyncDatabase = array( 'typeObj', 'groupObj', 'parentObj', 'porte_greffeObj', 'lieu_origineObj', 'default_photoObj', 'TYPES_ORIGNE', 'PERIODES', 'PERIODES_MOIS');
    public $donotSerialize = array('typeObj', 'groupObj', 'parentObj', 'porte_greffeObj', 'lieu_origineObj', 'default_photoObj');
    
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
    
    public function getCreationDate() {
    	return $this->creation_date;
    }
    public function setCreationDate( $creation_date ) {
    	$this->creation_date = $creation_date;
    }
    
    public function getAddDate() {
    	$dateTime = new DateTime($this->add_date);
    	return $dateTime->format('d/m/Y');
    }
    public function setAddDate( $add_date ) {
    	$this->add_date = $add_date;
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
    
    public function getGroup() {
    	if($this->groupObj === null) {
    		$this->groupObj =  Gestionnaire::getGestionnaire('Group')->getOne($this->group);
    	}
    	return $this->groupObj;
    }
    public function setGroup( $group_id ) {
    	$this->group = $group_id;
    }
    
    public function getParent() {
    	if($this->parentObj === null) {
    		$this->parentObj =  Gestionnaire::getGestionnaire('Vegetable')->getOne($this->parent);
    	}
    	return $this->parentObj;
    }
    public function setParent( $parent_id ) {
    	$this->parent = $parent_id;
    }
    
    public function getTypeOrigine() {
    	return $this->type_origine;
    }
    public function setTypeOrigine( $type_origine ) {
    	$this->type_origine = $type_origine;
    }
    
    public function getPorteGreffe() {
    	if($this->porte_greffeObj === null) {
    		$this->porte_greffeObj =  Gestionnaire::getGestionnaire('Portegreffe')->getOne($this->porte_greffe);
    	}
    	return $this->porte_greffeObj;
    }
    public function setPorteGreffe( $porte_greffe_id ) {
    	$this->porte_greffe = $porte_greffe_id;
    }
    
    public function getLieuOrigine() {
    	if($this->lieu_origineObj === null) {
    		$this->lieu_origineObj =  Gestionnaire::getGestionnaire('Lieu')->getOne($this->lieu_origine);
    	}
    	return $this->lieu_origineObj;
    }
    public function setLieuOrigine( $lieu_origine_id ) {
    	$this->lieu_origine = $lieu_origine_id;
    }
    
    public function getNomLatin() {
    	return $this->nom_latin;
    }
    public function setNomLatin( $nom_latin ) {
    	$this->nom_latin = $nom_latin;
    }
    
    public function getRusticite() {
    	return $this->rusticite;
    }
    public function setRusticite( $rusticite ) {
    	$this->rusticite = $rusticite;
    }
    
    public function getMoisFleurDebut() {
    	return $this->mois_fleur_debut;
    }
    public function setMoisFleurDebut( $mois_fleur_debut ) {
    	$this->mois_fleur_debut = $mois_fleur_debut;
    }
    
    public function getMoisFleurFin() {
    	return $this->mois_fleur_fin;
    }
    public function setMoisFleurFin( $mois_fleur_fin ) {
    	$this->mois_fleur_fin = $moi_fleur_fin;
    }
    
    public function getMoisFructiDebut() {
    	return $this->mois_fructi_debut;
    }
    public function setMoisFructiDebut( $mois_fructi_debut ) {
    	$this->mois_fructi_debut = $mois_fructi_debut;
    }
    
    public function getMoisFructiFin() {
    	return $this->mois_fructi_fin;
    }
    public function setMoisFructiFin( $mois_fructi_fin ) {
    	$this->mois_fructi_fin = $moi_fructi_fin;
    }
    
    public function getPFleur() {
    	return $this->p_fleur;
    }
    public function setPFleur( $p_fleur ) {
    	$this->p_fleur = $p_fleur;
    }
    
    public function getPFructi() {
    	return $this->p_fructi;
    }
    public function setPFructi( $p_fructi ) {
    	$this->p_fructi = $p_fructi;
    }
    
    public function getFructiDebut() {
    	if ($this->mois_fructi_debut) {
    		return $this->mois_fructi_debut;
    	}
    	if ($this->p_fructi) {
    		return $this->PERIODES_MOIS[$this->p_fructi][0];
    	}
    	return null;
    }
    
    public function getFructiFin() {
    	if ($this->mois_fructi_fin) {
    		return $this->mois_fructi_fin;
    	}
    	if ($this->p_fructi) {
    		return $this->PERIODES_MOIS[$this->p_fructi][1];
    	}
    	return null;
    }
    
    public function getFleurDebut() {
    	if ($this->mois_fleur_debut) {
    		return $this->mois_fleur_debut;
    	}
    	if ($this->p_fleur) {
    		return $this->PERIODES_MOIS[$this->p_fleur][0];
    	}
    	return null;
    }
    
    public function getFleurFin() {
    	if ($this->mois_fleur_fin) {
    		return $this->mois_fleur_fin;
    	}
    	if ($this->p_fleur) {
    		return $this->PERIODES_MOIS[$this->p_fleur][1];
    	}
    	return null;
    }
    
    public function getDefaultPhoto() {
    	if($this->default_photoObj === null) {
    		$photo = Gestionnaire::getGestionnaire('Photo')->getOne($this->default_photo);
    		if ($photo->getId() === null) {
    			$photos = Gestionnaire::getGestionnaire('Photo')->getOf(['vegetable' => $this->getId()]);
    			if (is_array($photos) && count($photos) > 0) {
    				$photo = $photos[0];
    			}
    			else {
    				$photo->setPath("./plante.png");
    			}
    		}
    		$this->default_photoObj =  $photo;
    	}
    	return $this->default_photoObj;
    }
    public function setDefaultPhoto( $photo_id ) {
    	$this->default_photo = $photo_id;
    }
    
        
    public function getActions() {
    	return Gestionnaire::getGestionnaire('Action')->getOf(['vegetable' => $this->getId()], 'date', true);
    }
}