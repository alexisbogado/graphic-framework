<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Models;

use \PDO;
use \Throwable;
use \Exception;

class Model
{
    /**
     * Model table name variable
     *
     * @var string $table
     */
    protected $table;

    /**
     * Model table primary key variable
     *
     * @var string $primaryKey
     */
    protected $primaryKey;

    /**
     * Hidden model columns variable
     *
     * @var array $hidden
     */
    protected $hidden;

    /**
     * Model variables
     *
     * @var array $data
     */
    private $data;

    /**
     * Data to save
     *
     * @var array $toSave
     */
    protected $toSave;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $functionsToShow;

    /**
     * Needs to be inserted
     *
     * @var bool
     */
    private $insert;

    /**
     * Excluded query operators
     *
     * @param array
     */
    private static $excludedOperators = [ 'IS', 'IN', 'NOT IN' ];
    
    /**
     * Set model values
     *
     * @param array $data
     */
    public function __construct($data = null)
    {
        $this->insert = is_null($data);

        $model_name_array = explode('\\', get_called_class());
        $model_name = strtolower($model_name_array[count($model_name_array) - 1]);

        if (is_null($this->table)):
            $class_name_array = preg_split('/(?=[A-Z])/', $model_name);
            $class_name_array = array_map(function($item) { return strtolower($item); }, $class_name_array);
            $table_name = implode('_', $class_name_array);
            if (substr($table_name, 0, 1) == '_') $table_name = substr($table_name, 1);

            $this->table = $table_name . 's';
        endif;

        if (is_null($this->primaryKey)) $this->primaryKey = self::getPrimaryKey();
        if (is_null($this->hidden)) $this->hidden = [ ];
        if (is_null($this->functionsToShow)) $this->functionsToShow = [ ];

        $this->data = [ ];

        if (!is_null($data) && is_array($data))
            foreach ($data as $key => $value) $this->{$key} = $value;

        $this->toSave = [ ];
    }

    /**
     * Set model variable
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        if (!is_null($this->toSave)) $this->toSave[$key] = $value;
        
        $this->data[$key] = $value;
    }

    /**
     * Get model variable
     *
     * @param string $key
     * 
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function __toArray()
    {
        return json_decode($this->__toString(), true);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function __toString()
    {
        $result = $this->data;
        foreach ($this->hidden as $hidden) unset($result[$hidden]);
        foreach ($this->functionsToShow as $function):
            try {
                $result[$function] = json_decode(api_data($this->{$function}()));
            } catch (Throwable $e) {
                $result[$function] = $this->{$function}();
            } catch (Exception $e) {
                $result[$function] = $this->{$function}();
            }
        endforeach;

        return json_encode($result);
    }

    /**
     * Get class primary key
     *
     * @return string|null
     */
    private function getPrimaryKey()
    {
        $result = database()->fetchResult('SHOW KEYS FROM ' . $this->table . ' WHERE Key_name = "PRIMARY"');

        return ($result['Column_name'] ?? null);
    }

    /**
     * Get all data if key is equals to model primary key
     *
     * @param object $class
     * @param string $key
     * 
     * @return array
     */
    public function hasMany($class, $key)
    {
        $class = new $class([]);
        $items = [ ];

        $result = database()->fetchAll('SELECT * FROM ' . $class->table . ' WHERE ' . $key . ' = ?', [ $this->{$this->primaryKey} ]);
        foreach ($result as $data) $items[] = new $class($data);

        unset($class);
        return $items;
    }

    /**
     * Get data if called class primary key is equals to model requested key
     *
     * @param object $class
     * @param string $key
     * 
     * @return object|null
     */
    public function belongsTo($class, $key)
    {
        $class = new $class([]);

        $result = database()->fetchResult('SELECT * FROM ' . $class->table . ' WHERE ' . $class->primaryKey . ' = ?', [ $this->{$key} ]);
        $result = ($result ? new $class($result) : null);

        unset($class);
        return $result;
    }

    /**
     * Undocumented function
     * 
     * @param mixed $key
     * 
     * @return array
     */
    public static function find($key)
    {
        $class = new static([ ]);
        $result = database()->fetchResult('SELECT * FROM ' . $class->table . ' WHERE ' . $class->primaryKey . ' = ?', [ $key ]);
        $result = ($result ? new $class($result) : null);

        unset($class);
		return $result;
    }

    /**
     * Call methods function
     *
     * @param object $method
     * @param mixed $arguments
     * 
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        switch ($method):
            case 'all':
                if (count($arguments) == 1) return self::allOrderBy($arguments[0]);
                else return self::allWhere(($arguments[0] ?? null), ($arguments[1] ?? null));
            break;
        endswitch;
    }

    /**
     * Undocumented function
     * 
     * @return void
     */
    public static function allWhere($where = null, $order = null)
    {
        if (!is_null($where) && is_array($where)) self::parseWhere($where, $where_str, $parameters);
        if (!is_null($order) && is_array($order)) self::parseOrder($order, $order_str);
        
        $class = new static([ ]);
        $classes = [ ];
        
        $result = database()->fetchAll('SELECT * FROM ' . $class->table. ' ' . ($where_str ?? '') . ' ' . ($order_str ?? ''), ($parameters ?? null));
        foreach ($result as $data) $classes[] = new $class($data);

        unset($class);
		return $classes;
    }

    /**
     * Undocumented function
     * 
     * @return void
     */
    public static function allOrderBy($order)
    {
        self::parseOrder($order, $order_str);

        $class = new static([ ]);
        $classes = [ ];

        $result = database()->fetchAll('SELECT * FROM ' . $class->table . ' ' . ($order_str ?? ''));
        foreach ($result as $data) $classes[] = new $class($data);

        unset($class);
		return $classes;
    }

    /**
     * Undocumented function
     * 
     * @param string $columns
     * @param array $where
     * 
     * @return array
     */
    public static function first($columns, $where = null, $orderBy = null)
    {
        $result = self::select($columns, $where, $orderBy);
        return ($result[0] ?? null);
    }

    /**
     * Undocumented function
     * 
     * @param string $columns
     * @param array $where
     * 
     * @return array
     */
    public static function select($columns, $where = null, $order = null)
    {
        if (!is_null($where) && is_array($where)) self::parseWhere($where, $where_str, $parameters);
        if (!is_null($order) && is_array($order)) self::parseOrder($order, $order_str);

        $class = new static([ ]);
        $classes = [ ];
        
        $result = database()->fetchAll('SELECT ' . $columns . ' FROM ' . $class->table . ' ' . ($where_str ?? '') . ' ' . ($order_str ?? ''), ($parameters ?? null));
        foreach ($result as $data) $classes[] = new $class($data);

        unset($class);
        return $classes;
    }

    /**
     * Get order by string
     * 
     * @param array $order
     * @param string $order_str
     * 
     * @return void
     */
    public static function parseOrder($order, &$order_str)
    {
        $order_str = 'ORDER BY ';
        $orders = [ ];

        foreach ($order as $column) $orders[] = ($column[0] . ' ' . ($column[1] ?? ''));

        $order_str .=  implode(', ', $orders);
    }

    /**
     * Get where string and parameters
     *
     * @param array $where
     * @param string $where_str
     * @param array $parameters
     * 
     * @return void
     */
    public static function parseWhere($where, &$where_str, &$parameters)
    {
        $where_str = 'WHERE ';
        $parameters = [ ];

        foreach ($where as $key => $item):
            self::getWhereString($item, $key, $where_str, $parameters);
        endforeach;
    }

    /**
     * Write where string by item
     *
     * @param array $item
     * 
     * @return void
     */
    private static function getWhereString($item, $key, &$where_str, &$parameters, $or = false)
    {
        if (count($item) < 2 || count($item) > 4) return;

        if ($or) $where_str .= ' OR ';
        elseif ($key != 0) $where_str .= ' AND ';
        
        if (count($item) < 3):
            $where_str .= "{$item[0]} = ?";
            $parameters[] = $item[1];
        else:
            if ((count($item) > 3) && is_array($item[3])):
                $where_str .= ("{$item[0]} {$item[1]} " . (in_array($item[1], self::$excludedOperators) ? $item[2] : '?'));
                
                if (!in_array($item[1], self::$excludedOperators))
                    $parameters[] = $item[2];

                self::getWhereString($item[3], null, $where_str, $parameters, true);
            elseif (is_array($item[2])):
                $where_str .= "{$item[0]} = ?";
                $parameters[] = $item[1];

                self::getWhereString($item[2], null, $where_str, $parameters, true);
            else:
                $where_str .= ("{$item[0]} {$item[1]} " . (in_array($item[1], self::$excludedOperators) ? $item[2] : '?'));
                
                if (!in_array($item[1], self::$excludedOperators))
                    $parameters[] = $item[2];
            endif;
        endif;
    }

    /**
	 * Get row count from current model records
     * 
     * @return int
     */
    public static function count($where = null)
    {
        if (!is_null($where) && is_array($where)) self::parseWhere($where, $where_str, $parameters);

        $class = new static([ ]);
        $result = database()->rowCount('SELECT * FROM ' . $class->table . ' ' . ($where_str ?? ''), ($parameters ?? null));

        unset($class);
        return $result;
    }

    /**
     * Save all changed data
     *
     * @return void
     */
    public function save()
    {
        if (count($this->toSave) <= 0)
            return;
        
        $columns = [ ];
        $params = [ ];
        $to_save = [ ];
        array_walk($this->toSave, function($value, $key) use (&$columns, &$to_save, &$params) {
            if ($this->insert):
                $columns[] = $key;
                $to_save[] = "?";
            else:
                $to_save[] = "{$key} = ?";
            endif;

            $params[] = $value;
        });
        $to_save = implode(', ', $to_save);

        if ($this->insert):
            $columns = implode(', ', $columns);
            $primary_key = database()->run('INSERT INTO ' . $this->table . '(' . $columns . ') VALUES(' . $to_save . ')', $params);
            
            $this->{$this->primaryKey} = $primary_key;
            $this->insert = false;
        else:
            $params[] = $this->{$this->primaryKey};
            database()->run('UPDATE ' . $this->table . ' SET ' . $to_save . ' WHERE ' . $this->primaryKey . ' = ?', $params);
        endif;
        
        $this->toSave = [ ];
        return $this;
    }

    /**
     * Remove all model data
     *
     * @return void
     */
    public function remove()
    {
        database()->run('DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = ?', [ $this->{$this->primaryKey} ]);
        $this->__destruct();
    }

    /**
     * Get model column names
     *
     * @return array
     */
    public static function getColumnNames()
    {
        $class = new static([ ]);
        $result = database()->run('DESCRIBE ' . $class->table);

        $columns = [ ];
        foreach ($result->fetchAll(PDO::FETCH_COLUMN) as $column):
            if (in_array($column, $class->hidden)) continue;

            $columns[] = $column;
        endforeach;

        foreach ($class->functionsToShow as $function)
            $columns[] = $function;
        
        unset($class);
        return $columns;
    }

	/**
	 * Destroy model
	 */
    public function __destruct()
    {
        $this->table = null;
        $this->primaryKey = null;
        $this->hidden = null;
        $this->data = null;
        $this->toSave = null;
    }
}
