<?php

class Controller_Admin extends Controller_Base {
	
	public function index() {
		return "maintain photos : action=maintainPhoto [&delete=true] <br>";
	}
	
	public function maintainPhoto() {
		$allPhotos = Gestionnaire::getGestionnaire(Model_Photo::class)->getOf(['utilisateur' => $_SESSION['utilisateur_id']]);
		$ret = '<ul>';
		foreach ($allPhotos as $photo) {
			if (!file_exists($photo->getPath()) || !is_file($photo->getPath())) {
				$ret .= '<li>#' . $photo->getId() . " - " . $photo->getPath() . " n'existe pas !</li>";
				if (isset($_GET['delete']) && $_GET['delete'] === 'true') {
					if ($photo->supprimer()) {
						$ret .= '<li> >>>> #' . $photo->getId() . " supprimé !</li>";
					}
				}
			}
		}
		$ret .= '</ul>';
		$ret .= '>>>> Terminé ! <<<<';
		return $ret;
	}
	
}
