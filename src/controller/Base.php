<?php
class Controller_Base extends Controller {
	
	
	public function getAction( $_action ) {
		$referrers = ['list', 'view'];
		// if (in_array($_action, $referrers)) {
		// 	$_SESSION['referrer'] = $_SERVER['HTTP_REFERER'];
		// }
		
		if (!isset($_SESSION['selectedBaseListGroup'])) {
			$_SESSION['selectedBaseListGroup'] = 1;
		}
		if (!isset($_SESSION['utiliateur_id'])) {
			$_SESSION['baseListGroup'] = [];
		} else {
			$_SESSION['baseListGroup'] = Gestionnaire::getGestionnaire('Group')->getOf(['parent' => ['is', null], 'utilisateur' => $_SESSION['utiliateur_id']]);
		}
		
		return parent::getAction( $_action );
	}
	
	public function applyGroup() {
		
		$_SESSION['selectedBaseListGroup'] = (int) $_POST['selectedBaseListGroup'];
		
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
}