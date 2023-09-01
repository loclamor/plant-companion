<?php

class Controller_Vegetable extends Controller_Base {
	
	public function list() {
		$this->getSite()->addTitle('Liste des plantes');
		$this->page = intval($_GET['p'] ?? 1);
		$this->length = intval($_GET['l'] ?? DEFAULT_PAGE_LENGTH);
		$filtres = ['utilisateur' => $_SESSION['utiliateur_id']];
		$join = '';
		// reset session page/length to parameters one
		$_SESSION['filters']['p'] = $this->page;
		$_SESSION['filters']['l'] = $this->length;
		if (isset($_GET['search-name']) && !empty($_GET['search-name'])) {
			$filtres['name'] = ['LIKE', "%" . $_GET['search-name'] . "%"];
			$_SESSION['filters']['search-name'] = $_GET['search-name'];
		}
		if (isset($_GET['search-type']) && !empty($_GET['search-type'])) {
			$filtres['type'] = $_GET['search-type'];
			$_SESSION['filters']['search-type'] = $_GET['search-type'];
		}
		
		if (isset($_SESSION['selectedBaseListGroup'])) {
			$join = "INNER JOIN `" . TABLE_PREFIX . "group` gr ON te.group_id = gr.id AND (gr.id = " . $_SESSION['selectedBaseListGroup'] . " OR gr.parent_id = " . $_SESSION['selectedBaseListGroup'] . ")";
		}
		$this->vegetables = Gestionnaire::getGestionnaire('Vegetable')->getPaginate($this->page, $this->length, 'name', false, $filtres, $join);
		$this->nb_vegetables = Gestionnaire::getGestionnaire('Vegetable')->countOf($filtres, $join);
		$this->types = Gestionnaire::getGestionnaire('Type')->getAll();
	}
	
	public function edit() {
		$this->entity = new Model_Vegetable();
		if (isset($_SESSION['selectedBaseListGroup'])) {
			$this->entity->setGroup($_SESSION['selectedBaseListGroup'] . '');
		}
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$this->entity = new Model_Vegetable(intval($_GET['id']));
		}
		$this->types = Gestionnaire::getGestionnaire('Type')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
		$this->vegetables = Gestionnaire::getGestionnaire('Vegetable')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
		$this->groups = Gestionnaire::getGestionnaire('Group')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
		$this->lieux = Gestionnaire::getGestionnaire('Lieu')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
		$this->portegreffes = Gestionnaire::getGestionnaire('Portegreffe')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	public function apply() {
		// treat null values
	    $post = array_map(function($value) {return $value === '' ? null : $value; }, $_POST);
	    $entity = new Model_Vegetable();
	    $entity->setCreationDate(date('Y-m-d H:i:s'));
	    $new = true;
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$entity = new Model_Vegetable(intval($_GET['id']));
			$new = false;
		}
		if ($post['porte_greffe'] === '-new-') {
			$pg = new Model_Portegreffe();
			$pg->setName($post['new_porte_greffe']);
			$pg->setType($post['type']);
			$pg->enregistrer();
			
			$post['porte_greffe'] = $pg->getId();
		}
		unset($post['new_porte_greffe']);
		$modified = $entity->hydrater($post);
		$entity->enregistrer();
		
		if (false === $new) {
			foreach($modified as $m) {
				$history = new Model_VegetableHistory();
				$history->setHistory($entity->getId(), $m);
				$history->enregistrer();
			}
		}
		header("Location: ?controller=vegetable&action=list");
		die();
	}
	
	public function view() {
		$this->entity = new Model_Vegetable();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$this->entity = new Model_Vegetable(intval($_GET['id']));
		}
	}
	
	public function setdefaultphoto() {
		$vegetable = new Model_Vegetable(intval($_GET['vegetable']));
		$photo = new Model_Photo(intval($_GET['photo']));
		$vegetable->setDefaultPhoto($photo->getId());
		$vegetable->enregistrer();
		header("Location: ?controller=vegetable&action=view&id=" . $vegetable->getId());
		die();
	}
	
	protected function getPhotos($idVegetable) {
		// mixedConditions [var: value] or [var: [op, value]] (WHERE var = value AND ...)
		return Gestionnaire::getGestionnaire('Photo')->getOf(['vegetable' => $idVegetable, 'utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	protected function getPhotosAction($idAction) {
		return Gestionnaire::getGestionnaire('Photo')->getOf(['action' => $idAction, 'utilisateur' => $_SESSION['utiliateur_id']]);
	}
}