<?php
class Controller_Photo extends Controller_Base {
	
	public function list() {
		$this->photos = Gestionnaire::getGestionnaire('Photo')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	public function listByVegetable() {
		$this->vegetable = new Model_Vegetable(intval($_GET['vegetable']));
		$this->photos = Gestionnaire::getGestionnaire('Photo')->getOf(['vegetable' => $this->vegetable->getId(), 'utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	public function edit() {
		$this->entity = new Model_Photo();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$this->entity = new Model_Photo(intval($_GET['id']));
		}
		elseif (isset($_GET['vegetable']) && intval($_GET['vegetable']) > 0) {
			$this->entity->setVegetable($_GET['vegetable']);
		}
		$this->vegetables = Gestionnaire::getGestionnaire('Vegetable')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
		$this->actions = Gestionnaire::getGestionnaire('Action')->getOf(['vegetable' => $this->entity->vegetable, 'utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
	public function apply() {
		$photo = new Model_Photo();
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$photo = new Model_Photo(intval($_GET['id']));
		}
		// treat null values
	    $post = array_map(function($value) {return $value === '' ? null : $value; }, $_POST);
		$photo->hydrater($post);
		$photo->enregistrer();
		
		$uploaddir = './uploads/' . $photo->getId() . '/';
		if ($photo->action !== null) {
			$uploaddir = './uploads/' . $photo->getAction()->getId() . '/';
		}
		
		mkdir($uploaddir, 0777, true);
		
		if (isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])) {
			
			// suppression ancienne photo
			if (file_exists($photo->getPath())) {
				unlink($photo->getPath());
			}
			
			// upload nouvelle photo
			$tmp_path = $_FILES['photo']['tmp_name'];
			$uploadfile = $uploaddir . basename($_FILES['photo']['name']);
			move_uploaded_file($tmp_path, $uploadfile);
			$photo->setPath($uploadfile);
			$photo->enregistrer();
		}
		
		header("Location: " . $_SESSION['referrer'] ?? "?controller=photo&action=list");
		die();
	}
	
	public function uploadVegetable() {
		if (isset($_FILES['photo']) && isset($_GET['vegetable'])) {
			$vegetable = new Model_Vegetable(intval($_GET['vegetable']));
			$uploaddir = './uploads/' . $vegetable->getId() . '/';
			mkdir($uploaddir, 0777, true);
			
			foreach ($_FILES['photo']['tmp_name'] as $id => $tmp_path) {
				$uploadfile = $uploaddir . basename($_FILES['photo']['name'][$id]);
				move_uploaded_file($tmp_path, $uploadfile);
				$photo = new Model_Photo();
				$photo->setVegetable($_GET['vegetable']);
				$photo->setPath($uploadfile);
				$photo->enregistrer();
			}
		}
		header("Location: " . $_SESSION['referrer'] ?? "?controller=vegetable&action=list");
		die();
	}
	
	public function uploadMultiple() {
		$this->vegetables = Gestionnaire::getGestionnaire('Vegetable')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
		$this->action = new Model_Action();
	}
	
	public function uploadMultipleV2() {
		$this->vegetables = Gestionnaire::getGestionnaire('Vegetable')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
		$this->action = new Model_Action();
	}
	
	public function handleSingleUpload() {
		$this->isJSON = true;
		
		$uploaddir = './uploads/0/';
		mkdir($uploaddir, 0777, true);
		if (isset($_FILES['file']) && !empty($_FILES['file']['tmp_name'])) {
			$uploadfile = $uploaddir . basename($_FILES['file']['name']);
			move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
			$photo = new Model_Photo();
			$photo->setPath($uploadfile);
			$photo->enregistrer();
			
			return $photo->getId();
		}
	}
	
	public function multipleAdd() {
		if (is_array($_POST['vegetable'])) {
			foreach($_POST['vegetable'] as $idPhoto => $vegetable) {
				// load photo
				$photo = new Model_Photo($idPhoto);
				
				if ($_POST['type_action'][$idPhoto] !== 'SANS_ACTION') {
				$action = new Model_Action();
					$action->setVegetable($vegetable);
					$action->setDate($_POST['date'][$idPhoto]);
					// title
					$action->setTitle($_POST['title'][$idPhoto]);
					if ($_POST['type_action'][$idPhoto] === 'observation') {
						$action->setTitle($_POST['observation'][$idPhoto]);
					}
					// comment
					$action->setComment($_POST['comment'][$idPhoto]);
					// type_action
					$action->setTypeAction($_POST['type_action'][$idPhoto]);
					$action->enregistrer();
					
					// update photo
					$photo->setAction($action->getId());
				}
				
				// update photo
				$photo->setVegetable($vegetable);
				$photo->enregistrer();
			}
			header("Location: " . $_SESSION['referrer'] ?? "?controller=action&action=list");
			die();
		}
		header("Location: " . $_SESSION['referrer'] ?? "?controller=photo&action=uploadMultiple");
		die();
	}
	
	public function setdefaultphoto() {
		$vegetable = new Model_Vegetable(intval($_GET['vegetable']));
		$photo = new Model_Photo(intval($_GET['photo']));
		$vegetable->setDefaultPhoto($photo->getId());
		$vegetable->enregistrer();
		header("Location: " . $_SESSION['referrer'] ?? ("?controller=photo&action=listByVegetable&vegetable=" . $vegetable->getId()));
		die();
	}
	
	public function delete() {
		$prevAction = $_GET['prevaction'] ?? 'list';
		$photo = new Model_Photo(intval($_GET['id']));
		$vegetable = null;
		if ($photo->vegetable) {
			$vegetable = $photo->getVegetable();
		}
		
		if (file_exists($photo->getPath()) && is_file($photo->getPath())) {
			$path_parts = pathinfo($photo->getPath());
			$minUrl = $path_parts['dirname'].'/'.$path_parts['filename'].'.min'.'[HL]*.'.$path_parts['extension'];
			$minFiles = glob($minUrl);
			foreach ($minFiles as $minfile) {
				if (file_exists($photo->getPath()) && is_file($photo->getPath())) {
					unlink($minfile);
				}
			}
			unlink($photo->getPath());
		}
		$photo->supprimer();
		if ($vegetable !== null && $prevAction === 'listByVegetable') {
			header("Location: " . $_SESSION['referrer'] ?? ("?controller=photo&action=listByVegetable&vegetable=" . $vegetable->getId()));
		} else {
			header("Location: " . $_SESSION['referrer'] ?? "?controller=photo&action=list");
		}
	}
	
	public function uploadOne() {
		$this->isJSON = true;
		/*
		photo
		hash
		date
		title
		vegetable
		type_action
		observation
		comment
		*/
		
		var_export($_POST);
		
		
		$vegetable = new Model_Vegetable(intval($_POST['vegetable']));
		$uploaddir = './uploads/' . $vegetable->getId() . '/';
		mkdir($uploaddir, 0777, true);
		
		$uploadfile = $uploaddir . basename($_FILES['photo']['name']);
		move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile);
		$photo = new Model_Photo();
		$photo->setVegetable($vegetable->getId());
		$photo->setPath($uploadfile);
		$photo->enregistrer();
				
		if ($_POST['type_action'] !== 'SANS_ACTION') {
			$action = new Model_Action();
			$action->setVegetable($vegetable->getId());
			$action->setDate($_POST['date']);
			// title
			$action->setTitle($_POST['title']);
			if ($_POST['type_action'] === 'observation') {
				$action->setTitle($_POST['observation']);
			}
			// comment
			$action->setComment($_POST['comment']);
			// type_action
			$action->setTypeAction($_POST['type_action']);
			$action->enregistrer();
			
			// update photo
			$photo->setAction($action->getId());
		}
		
		// update photo
		$photo->setVegetable($vegetable->getId());
		$photo->enregistrer();
		
		return [
				'success' => true,
				'hash' => $_POST['hash'],
			];
	}
}