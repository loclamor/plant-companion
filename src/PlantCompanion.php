<?php

class PlantCompanion extends Site {
	
	
	protected function loadCurrentUser() {
		if ($this->controller !== 'login' && !isset($_SESSION['utiliateur_id'])) {
		//	$_SESSION['utiliateur_id'] = 0;
		//	return;
			$this->controller = 'login';
			$this->action = 'index';
		}
	}
	
	/**
	 * The aim of this function is to get the current connected user.
	 * @return false if no current user, or the user object if there is one.
	 **/
	public function getCurrentUser() {
		return $_SESSION['utiliateur_id'] ?? false;
	}
	
}