<?php
class Controller_Portegreffe extends Controller_Base {
	
	public function list() {
		$this->entities = Gestionnaire::getGestionnaire('Portegreffe')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	public function edit() {
		$this->entity = new Model_Portegreffe();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$this->entity = new Model_Portegreffe(intval($_GET['id']));
		}
		$this->types = Gestionnaire::getGestionnaire('Type')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	public function apply() {
		// treat null values
	    $post = array_map(function($value) {return $value === '' ? null : $value; }, $_POST);
		$entity = new Model_Portegreffe();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$entity = new Model_Portegreffe(intval($_GET['id']));
		}
		$entity->hydrater($post);
		$entity->enregistrer();
		
		header("Location: " . $_SESSION['referrer'] ?? "?controller=portegreffe&action=list");
		die();
	}
	
	public function view() {
		$this->entity = new Model_Portegreffe();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$this->entity = new Model_Portegreffe(intval($_GET['id']));
		}
	}
}