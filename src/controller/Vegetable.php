<?php

class Controller_Vegetable extends Controller_Base {
	
	public function list() {
		$this->getSite()->addTitle('Liste des plantes');
		$this->page = (int)($_GET['p'] ?? 1);
		$this->length = (int)($_GET['l'] ?? DEFAULT_PAGE_LENGTH);
		$filtres = ['utilisateur' => $_SESSION['utilisateur_id']];
		$join = '';
        if (isset($_GET['filterapply'])) {
            // reset filters
            $_SESSION['filters'] = [];
        }
		// reset session page/length to parameters one
		$_SESSION['filters']['p'] = $this->page;
		$_SESSION['filters']['l'] = $this->length;
		if (!empty($_GET['search-name'])) {
			$filtres['name'] = ['LIKE', "%" . $_GET['search-name'] . "%"];
			$_SESSION['filters']['search-name'] = $_GET['search-name'];
		}
		if (!empty($_GET['search-type'])) {
			$filtres['type'] = $_GET['search-type'];
			$_SESSION['filters']['search-type'] = $_GET['search-type'];
		}
        $orderBy = 'name';
        if (!empty($_GET['order-by'])) {
            $orderBy = $_GET['order-by'];
            $_SESSION['filters']['order-by'] = $_GET['order-by'];
        }

        $desc = false;
        if (!empty($_GET['order-way'])) {
            $desc = $_GET['order-way'] === 'desc';
            $_SESSION['filters']['order-way'] = $_GET['order-way'];
        }
		
		if (isset($_SESSION['selectedBaseListGroup'])) {
			$join = "INNER JOIN `" . TABLE_PREFIX . "group` gr ON te.group_id = gr.id AND (gr.id = " . $_SESSION['selectedBaseListGroup'] . " OR gr.parent_id = " . $_SESSION['selectedBaseListGroup'] . ")";
		}
		$this->vegetables = Gestionnaire::getGestionnaire(Model_Vegetable::class)->getPaginate($this->page, $this->length, $orderBy, $desc, $filtres, $join);
		$this->nb_entities = Gestionnaire::getGestionnaire(Model_Vegetable::class)->countOf($filtres, $join);
		$this->types = Gestionnaire::getGestionnaire(Model_Type::class)->getAll();
	}
	
	public function edit() {
		$this->entity = new Model_Vegetable();
		if (isset($_SESSION['selectedBaseListGroup'])) {
			$this->entity->setGroup($_SESSION['selectedBaseListGroup'] . '');
		}
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$this->entity = new Model_Vegetable((int)$_GET['id']);
		}
		$this->types = Gestionnaire::getGestionnaire(Model_Type::class)->getOf(['utilisateur' => $_SESSION['utilisateur_id']]);
		$this->vegetables = Gestionnaire::getGestionnaire(Model_Vegetable::class)->getOf(['utilisateur' => $_SESSION['utilisateur_id']]);
		$this->groups = Gestionnaire::getGestionnaire(Model_Group::class)->getOf(['utilisateur' => $_SESSION['utilisateur_id']]);
		$this->lieux = Gestionnaire::getGestionnaire(Model_Lieu::class)->getOf(['utilisateur' => $_SESSION['utilisateur_id']]);
		$this->portegreffes = Gestionnaire::getGestionnaire(Model_Portegreffe::class)->getOf(['utilisateur' => $_SESSION['utilisateur_id']]);
	}
	
	public function apply() {
		// treat null values
	    $post = array_map(function($value) {return $value === '' ? null : $value; }, $_POST);
	    $entity = new Model_Vegetable();
	    $entity->setCreationDate(date('Y-m-d H:i:s'));
	    $new = true;
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$entity = new Model_Vegetable((int)$_GET['id']);
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
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$this->entity = new Model_Vegetable((int)$_GET['id']);
		}
	}
	
	public function setdefaultphoto() {
		$vegetable = new Model_Vegetable((int)$_GET['vegetable']);
		$photo = new Model_Photo((int)$_GET['photo']);
		$vegetable->setDefaultPhoto($photo->getId());
		$vegetable->enregistrer(['default_photo']);
		header("Location: ?controller=vegetable&action=view&id=" . $vegetable->getId());
		die();
	}
	
	protected function getPhotos($idVegetable) {
		// mixedConditions [var: value] or [var: [op, value]] (WHERE var = value AND ...)
		return Gestionnaire::getGestionnaire(Model_Photo::class)->getOf(['vegetable' => $idVegetable, 'utilisateur' => $_SESSION['utilisateur_id']]);
	}
	
	protected function getPhotosAction($idAction) {
		return Gestionnaire::getGestionnaire(Model_Photo::class)->getOf(['action' => $idAction, 'utilisateur' => $_SESSION['utilisateur_id']]);
	}
}
