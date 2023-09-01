<?php

class Model_VegetableHistory extends Model_History {
	
	public $DB_table = 'vegetable_history';
	
	protected function formatValue($value) {
		if ($value === null) {
			return '- Sans valeur -';
		}
    	switch ($this->key) {
    		case 'type':
    			return (new Model_Type($value))->getName();
			case 'group':
    			return (new Model_Group($value))->getName();
    		case 'parent':
    			return (new Model_Vegetable($value))->getName();
    		case 'porte_greffe':
    			return (new Model_Portegreffe($value))->getName();
    		case 'lieu_origine':
    			return (new Model_Lieu($value))->getName();
    		case 'default_photo':
    			return (new Model_Photo($value))->getPath();
    		default:
    			return parent::formatValue($value);
    	}
    }
	
}