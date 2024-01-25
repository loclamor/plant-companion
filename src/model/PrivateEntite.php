<?php

class Model_PrivateEntite extends Entite {
	
	public $utilisateur;
	
	
	public function enregistrer($toUpdate = null) {
		
		$this->utilisateur = $_SESSION['utilisateur_id'];
		
		parent::enregistrer($toUpdate = null);
		
	}
	
	
}
