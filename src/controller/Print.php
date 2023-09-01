<?php
class Controller_Print extends Controller_Base {
	
	public function bytype() {
		$this->type = intval($_POST['type'] ?? 1);
		$join = '';
		if (isset($_SESSION['selectedBaseListGroup'])) {
			$join = "INNER JOIN `" . TABLE_PREFIX . "group` gr ON te.group_id = gr.id AND (gr.id = " . $_SESSION['selectedBaseListGroup'] . " OR gr.parent_id = " . $_SESSION['selectedBaseListGroup'] . ")";
		}
		$this->entities = Gestionnaire::getGestionnaire('Vegetable')->getOf(['type' => $this->type, 'utilisateur' => $_SESSION['utiliateur_id']], 'name', false, 0, 0, $join);
		$this->types = Gestionnaire::getGestionnaire('Type')->getOf(['utilisateur' => $_SESSION['utiliateur_id']]);
	}
	
}