<?php
class Controller_Group extends Controller_Base {
	
	public function list() {
		$this->groups = Gestionnaire::getGestionnaire('Group')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	public function edit() {
		$this->entity = new Model_Group();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$this->entity = new Model_Group(intval($_GET['id']));
		}
		$this->groups = Gestionnaire::getGestionnaire('Group')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	public function apply() {
		// treat null values
	    $post = array_map(function($value) {return $value === '' ? null : $value; }, $_POST);
		$group = new Model_Group();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$group = new Model_Group(intval($_GET['id']));
		}
		$group->hydrater($post);
		$group->enregistrer();
		
		header("Location: " . $_SESSION['referrer'] ?? "?controller=group&action=list");
		die();
	}
	
	public function view() {
		$this->entity = new Model_Group();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$this->entity = new Model_Group(intval($_GET['id']));
		}
	}
}