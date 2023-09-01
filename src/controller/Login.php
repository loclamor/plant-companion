<?php
class Controller_Login extends Controller_Base {
	
	public function index() {
		
	}
	
	public function submitlogin() {
		$login = $_POST['login'];
		$pwd = $_POST['password'];
		$hash = password_hash($pwd, PASSWORD_BCRYPT );
		
		$user = Gestionnaire::getGestionnaire('Utilisateur')->getOneOf(['name' => $login]);
		if (password_verify($pwd, $user->getPassword())) {
			$_SESSION['utiliateur_id'] = $user->getId();
		}
		header("Location: ?controller=home&action=index");
		die();
	}
	
	public function logOut() {
		unset($_SESSION['utiliateur_id']);
		
		header("Location: ?controller=login&action=index");
		die();
	}
	
	public function create() {
		
	}
	
	public function submitcreate() {
		$login = $_POST['login'];
		$user = Gestionnaire::getGestionnaire('Utilisateur')->getOneOf(['name' => $login]);
		if ($user) {
			// TODO : erreur !
			
			header("Location: ?controller=login&action=create");
			die();
		}
		
		$pwd = $_POST['password'];
		$hash = password_hash($pwd, PASSWORD_BCRYPT );
		
		$utilisateur = new Model_Utilisateur();
		$utilisateur->setName($login);
		$utilisateur->setPassword($hash);
		$utilisateur->enregistrer();
		$_SESSION['utiliateur_id'] = $utilisateur->getId();
		header("Location: ?controller=home&action=index");
		die();
	}
	
}