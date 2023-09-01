<?php

class Model_PrivateEntite extends Entite {
	
	public $utilisateur;
	
	
	public function enregistrer($toUpdate = null) {
		
		$this->utilisateur = $_SESSION['utiliateur_id'];
		
		parent::enregistrer($toUpdate = null);
		
	}
	
	
}