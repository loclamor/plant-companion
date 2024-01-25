<?php
class Controller_Base extends Controller {
	
	
	public function getAction( $_action ) {
		$referrers = ['list', 'view'];
        $_SESSION['referrer'] = null;
		// if (in_array($_action, $referrers)) {
		// 	$_SESSION['referrer'] = $_SERVER['HTTP_REFERER'];
		// }
		

		if (!isset($_SESSION['utilisateur_id'])) {
			$_SESSION['baseListGroup'] = [];
		} else {
			$_SESSION['baseListGroup'] = Gestionnaire::getGestionnaire(Model_Group::class)->getOf(['parent' => ['is', null], 'utilisateur' => $_SESSION['utilisateur_id']]);
            if (!isset($_SESSION['selectedBaseListGroup'])) {
                $_SESSION['selectedBaseListGroup'] = $_SESSION['baseListGroup'][0]->getId();
            }
		}


		
		return parent::getAction( $_action );
	}
	
	public function applyGroup() {
		
		$_SESSION['selectedBaseListGroup'] = (int) $_POST['selectedBaseListGroup'];
		
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
}
