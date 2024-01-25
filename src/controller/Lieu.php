<?php
class Controller_Lieu extends Controller_Base {
	
	public function list() {
		$this->entities = Gestionnaire::getGestionnaire(Model_Lieu::class)->getOf(['utilisateur' => $_SESSION['utilisateur_id']]);
	}
	
	public function edit() {
		$this->entity = new Model_Lieu();
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$this->entity = new Model_Lieu((int)$_GET['id']);
		}
	}
	
	public function apply() {
		// treat null values
	    $post = array_map(function($value) {return $value === '' ? null : $value; }, $_POST);
		$entity = new Model_Lieu();
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$entity = new Model_Lieu((int)$_GET['id']);
		}
		$entity->hydrater($post);
		$entity->enregistrer();
		
		header("Location: " . $_SESSION['referrer'] ?? "?controller=lieu&action=list");
		die();
	}
	
	public function view() {
		$this->entity = new Model_Lieu();
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$this->entity = new Model_Lieu((int)$_GET['id']);
		}
	}
}
