<?php
class Controller_Calendar extends Controller_Base {
	
	public function fructification() {
		$join = '';
		if (isset($_SESSION['selectedBaseListGroup'])) {
			$join = "INNER JOIN `" . TABLE_PREFIX . "group` gr ON te.group_id = gr.id AND (gr.id = " . $_SESSION['selectedBaseListGroup'] . " OR gr.parent_id = " . $_SESSION['selectedBaseListGroup'] . ")";
		}
		$this->vegetables = Gestionnaire::getGestionnaire('Vegetable')->getOf(['utilisateur' => $_SESSION['utiliateur_id']], 'name', false, 0, 0, $join);
	}
	
	protected function getMonthActions($vegetable, $month, $type) {
		$sqlStr = "SELECT DAY(`date`) as day FROM ".TABLE_PREFIX."action WHERE vegetable_id = {{vegetable_id}} AND type_action = 'observation' AND title = '{{type}}' AND MONTH(`date`) = {{month}} group by day order by day asc";
		$params = [
			'vegetable_id' => $vegetable->getId(),
			'month' => $month,
			'type' => $type
			];
		$res = SQL::getInstance()->exec($sqlStr, false, $params);
		$res = is_array($res) ? $res : [];
		$ret = [];
		foreach ($res as $v) {
			$ret []= $v['day'];
		}
		return $ret;
	}
	
}