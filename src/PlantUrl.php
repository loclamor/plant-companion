<?php
class PlantUrl {
	
	private static $currentParams = null;
	
	public static function get(string $controller = DEFAULT_CONTROLLER, string $action = DEFAULT_ACTION, array $params = [], bool $withFilters = true): string {
		$base = "?controller=$controller&action=$action";
		if ($withFilters && isset($_SESSION['filters']) && count($_SESSION['filters']) > 0) {
			$params = array_merge($_SESSION['filters'], $params);
		}
		if (count($params) > 0) {
			foreach ($params as $key => $val) {
				$base .= "&$key=$val";
			}
		}
		return $base;
	}
	
	public static function getApply(int $id = null, array $params = []) {
		if ($id > 0) {
			$params['id'] = $id;
		}
		return self::get(self::getCurrentController(), 'apply', $params, false);
	}
	
	public static function getEdit(int $id = null, array $params = []) {
		if ($id > 0) {
			$params['id'] = $id;
		}
		return self::get(self::getCurrentController(), 'edit', $params, false);
	}
	
	public static function getView(int $id, array $params = []) {
		$params['id'] = $id;
		return self::get(self::getCurrentController(), 'view', $params, false);
	}
	
	public static function getDelete(int $id, array $params = []) {
		$params['id'] = $id;
		return self::get(self::getCurrentController(), 'delete', $params, false);
	}
	
	public static function getList(array $params = []) {
		return self::get(self::getCurrentController(), 'list', $params, true);
	}
	
	private static function retrieveCurrentParams(): array {
		if (is_array(self::$currentParams)) {
			return self::$currentParams;
		}
		self::$currentParams = [];
		$query_parts = explode('&',$_SERVER['QUERY_STRING']);
		foreach ($query_parts as $param) {
			$parts = explode('=',$param);
			if(count($parts)>1){
				self::$currentParams[$parts[0]] = $parts[1];
			}
			else {
				self::$currentParams[$parts[0]] = '';
			}
		}
		return self::$currentParams;
	}
	
	public static function getCurrentController(): string {
		$params = self::retrieveCurrentParams();
		return $params['controller'] ?? DEFAULT_CONTROLLER;
	}
	
	public static function getCurrentAction(): string {
		$params = self::retrieveCurrentParams();
		return $params['action'] ?? DEFAULT_ACTION;
	}
}