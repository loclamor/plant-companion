<?php
class Controller_Action extends Controller_Base {
	
	public function list() {
		$this->getSite()->addTitle('Liste des actions');
        $this->page = (int)($_GET['p'] ?? 1);
        $this->length = (int)($_GET['l'] ?? DEFAULT_PAGE_LENGTH);
		$filtres = ['utilisateur' => $_SESSION['utilisateur_id']];
		$this->entities = Gestionnaire::getGestionnaire(Model_Action::class)->getPaginate($this->page, $this->length, 'id', true, $filtres);
		$this->nb_entities = Gestionnaire::getGestionnaire(Model_Action::class)->countOf($filtres);
	}
	
	public function edit() {
		$this->entity = new Model_Action();
		$this->isGroup = false;
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$this->entity = new Model_Action((int)$_GET['id']);
		}
		elseif (isset($_GET['vegetable']) && (int)$_GET['vegetable'] > 0) {
			$this->entity->setVegetable($_GET['vegetable']);
		}
		elseif (isset($_POST['vegetables']) && is_array($_POST['vegetables']) && count($_POST['vegetables']) > 0) {
			$this->isGroup = true;
			$this->vegetables = [];
			foreach ($_POST['vegetables'] as $v_id) {
				$this->vegetables[] = new Model_Vegetable($v_id);
			}
			return;
		}
		$this->vegetables = Gestionnaire::getGestionnaire(Model_Vegetable::class)->getOf(['utilisateur' => $_SESSION['utilisateur_id']]);
	}
	
	public function apply() {
		
		if (isset($_POST['vegetables']) && is_array($_POST['vegetables']) && count($_POST['vegetables']) > 0) {
			
			foreach ($_POST['vegetables'] as $vegetable) {
				$action = new Model_Action();
				$action->setVegetable($vegetable);
				$this->hydrateAndSaveAction($action);
				
			}
			
			header("Location: " . $_SESSION['referrer'] ?? "?controller=action&action=list");
			die();
		}
		
		$action = new Model_Action();
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$action = new Model_Action((int)$_GET['id']);
			if ((int)$action->vegetable !== (int)$_POST['vegetable']) {
				// changer les vegetable des photos associées
				$photos = $this->getPhotos($action->getId());
				if (is_array($photos)) {
					foreach ($photos as $photo) {
						$photo->setVegetable($_POST['vegetable']);
						$photo->enregistrer();
					}
				}
			}
		}
		$this->hydrateAndSaveAction($action);
		
		$uploaddir = './uploads/' . $action->getId() . '/';
        if (!mkdir($uploaddir, 0777, true) && !is_dir($uploaddir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $uploaddir));
        }
		
		foreach ($_FILES['photo']['tmp_name'] as $id => $tmp_path) {
			$uploadfile = $uploaddir . basename($_FILES['photo']['name'][$id]);
			move_uploaded_file($tmp_path, $uploadfile);
			$photo = new Model_Photo();
			$photo->setVegetable($_POST['vegetable']);
			$photo->setAction($action->getId());
			$photo->setPath($uploadfile);
			$photo->enregistrer();
		}
		
		header("Location: " . $_SESSION['referrer'] ?? "?controller=action&action=list");
		die();
	}
	
	protected function hydrateAndSaveAction($action) {
		$action->hydrater($_POST);
		if ($_POST['type_action'] === 'observation') {
			$action->setTitle($_POST['observation']);
		} else if (empty($_POST['title'])) {
			$type = new Model_Type($_POST['type_action']);
			$action->setTitle($type->getName());
		}
		$action->enregistrer();
	}
	
	public function view() {
		$this->entity = new Model_Action();
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$this->entity = new Model_Action((int)$_GET['id']);
		}
	}
	
	protected function getPhotos($idAction) {
		// mixedConditions [var: value] or [var: [op, value]] (WHERE var = value AND ...)
		return Gestionnaire::getGestionnaire(Model_Photo::class)->getOf(['action' => $idAction, 'utilisateur' => $_SESSION['utilisateur_id']]);
	}
}
