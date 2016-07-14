<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {

	/**
	 * 不可以被批量賦值的屬性。
	 *
	 * @var array
	 */
	protected $guarded = [''];
	
    public static function getEnumValues($name, $fieldTye = 'enum')
    {
    	$instance = new static; // create an instance of the model to be able to get the table name
    	$type = \DB::select("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = '{$name}' AND TABLE_NAME='".$instance->getTable()."'")[0]->COLUMN_TYPE;
    	$matches = array();
        preg_match('/^'.$fieldTye.'\((.*)\)$/', $type, $matches);
        $values = array();
        foreach(explode(',', $matches[1]) as $value){
            $values[trim($value, "'")] = trim($value, "'");
        }
        return $values;
    }
    public static function getSetValues($name)
    {
    	return self::getEnumValues($name, 'set');
    }
    
    public static function getImplodeEnumValues($name, $glue = ',')
    {
    	return implode($glue, self::getEnumValues($name));
    }
    
    public static function setEnumNewValues($name, $data)
    {
    	array_walk($data, function (&$value, $key, $dot){
    		$value = $dot.$value.$dot;
    	}, "'");
    	$instance = new static; // create an instance of the model to be able to get the table name
    	\DB::statement("ALTER TABLE ".$instance->getTable()." MODIFY {$name} enum(".implode(",", $data).")");
    }
}
