<?php
class Controller_Type extends Controller_Base {
	
	public function list() {
		$this->types = Gestionnaire::getGestionnaire('Type')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	public function edit() {
		$this->entity = new Model_Type();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$this->entity = new Model_Type(intval($_GET['id']));
		}
		$this->types = Gestionnaire::getGestionnaire('Type')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	public function apply() {
		// treat null values
	    $post = array_map(function($value) {return $value === '' ? null : $value; }, $_POST);
		$type = new Model_Type();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$type = new Model_Type(intval($_GET['id']));
		}
		$type->hydrater($post);
		$type->enregistrer();
		
		header("Location: " . $_SESSION['referrer'] ?? "?controller=type&action=list");
		die();
	}
	
	public function view() {
		$this->entity = new Model_Type();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$this->entity = new Model_Type(intval($_GET['id']));
		}
	}
	
}