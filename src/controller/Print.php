<?php
class Controller_Print extends Controller_Base {
	
	public function bytype() {
		$this->type = (int)($_POST['type'] ?? 1);
		$join = '';
		if (isset($_SESSION['selectedBaseListGroup'])) {
			$join = "INNER JOIN `" . TABLE_PREFIX . "group` gr ON te.group_id = gr.id AND (gr.id = " . $_SESSION['selectedBaseListGroup'] . " OR gr.parent_id = " . $_SESSION['selectedBaseListGroup'] . ")";
		}
		$this->entities = Gestionnaire::getGestionnaire(Model_Vegetable::class)->getOf(['type' => $this->type, 'utilisateur' => $_SESSION['utilisateur_id']], 'name', false, 0, 0, $join);
		$this->types = Gestionnaire::getGestionnaire(Model_Type::class)->getOf(['utilisateur' => $_SESSION['utilisateur_id']]);
	}
	
}
