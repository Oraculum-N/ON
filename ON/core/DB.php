<?php

namespace ON;

class DB extends DBO
{
    private $_fields = [];
    protected $_className = null;
    protected $_tableName = null;
    protected $_key = ['id'];
    protected $_keyvalue = [];
    protected $_filterQuery = [];
    protected $_validateMsg = null;
    protected $_types = ['bigint', 'binary', 'bit', 'blob', 'boolean',
                                'char', 'date', 'datetime', 'decimal',
                                'double', 'enum', 'float', 'geometry',
                                'geometrycollection', 'int', 'linestring',
                                'longblob', 'longtext', 'mediumblob',
                                'mediumint', 'mediumtext', 'multilinestring',
                                'multipoint', 'multipolygon', 'point',
                                'polygon', 'real', 'serial', 'set', 'smallint',
                                'text', 'time', 'timestamp', 'tinyblob',
                                'tinyint', 'tinytext', 'varbinary', 'varchar',
                                'year', ];

    public function __construct($class = null)
    {
        //parent::__construct($class);
        if (!is_null($class)):
                $this->_className = $class;
        if (strpos($class, '\\')):
                    $table = explode('\\', $class);
        $this->_tableName = strtolower($table[count($table) - 1]); else:
                    $this->_tableName = $table;
        endif;
        endif;

        return $this;
    }

    public function getLines($limit = null)
    {
        $limit = (int) $limit;
        $query = 'SELECT count(*) as \'count\' FROM '.$this->_tableName;
        if ($limit > 0):
                $query .= ' LIMIT '.$limit;
        endif;
        $rows = $this->execSQL($query);
        $lines = $this->fetch($rows);

        return (int) $lines[0]->count;
    }

    public function getAll($limit = null, $offset = null, $orderby = null, $order = 'ASC')
    {
        $limit = (int) $limit;
        $offset = (int) $offset;
        $query = 'SELECT * FROM '.$this->_tableName;
        if (count($this->_filterQuery) > 0):
                $query .= ' WHERE '.implode(' AND ', $this->_filterQuery);
        endif;
        if (!is_null($orderby)):
                $query .= ' ORDER BY '.$orderby.' '.$order;
        endif;
        if ($limit > 0):
                $query .= ' LIMIT '.$limit;
        if ($offset > 0):
                    $query .= ' OFFSET '.$offset;
        endif;
        endif;
        $rows = $this->execSQL($query);

        return $this->fetch($rows);
    }

    public function getFirst($debug = false)
    {
        $this->updateKeyValue();
        $fields = null;
        $eval = '$query=sprintf(\'SELECT * FROM '.$this->_tableName.' ';
        $eval .= 'ORDER BY '.$this->_key[0].' ASC\');';
        if ($debug):
                echo $eval;
        endif;
        eval($eval);
        $rows = $this->execSQL($query);
        $rows = $this->fetch($rows);
        if (count($rows) > 0):
                return $rows[0]; else:
                return;
        endif;
    }

    public function getLast($debug = false)
    {
        $this->updateKeyValue();
        $fields = null;
        $eval = '$query=sprintf(\'SELECT * FROM '.$this->_tableName.' ';
        $eval .= 'ORDER BY '.$this->_key[0].' DESC\');';
        if ($debug):
                echo $eval;
        endif;
        eval($eval);
        $rows = $this->execSQL($query);
        $rows = $this->fetch($rows);
        if (count($rows) > 0):
                return $rows[0]; else:
                return;
        endif;
    }

    public function getByTableField($field, $value, $type = '%u')
    {
        if (count($this->_filterQuery) > 0):
                $filter = ' AND '.implode(' AND ', $this->_filterQuery); else:
                $filter = '';
        endif;
        $query = sprintf('SELECT * FROM '.$this->_tableName.' WHERE '.$field.'='.($type == '%u' ? $type : '"'.$type.'"').' '.$filter.' LIMIT 1', $this->secsql($value));
        $rows = $this->execSQL($query);
        $rows = $this->fetch($rows);
        if (count($rows) > 0):
                return $rows[0]; else:
                return;
        endif;
    }

    public function getAllByTableField($field, $value, $type = '%u', $limit = null, $offset = null)
    {
        $filter = '';
        $limit = (int) $limit;
        $offset = (int) $offset;
        $sqllimit = null;
        if ($limit > 0):
                $sqllimit = 'LIMIT '.$limit;
        if ($offset > 0):
                    $sqllimit .= ' OFFSET '.$offset;
        endif;
        endif;
        if (count($this->_filterQuery) > 0):
                $filter .= ' AND '.implode(' AND ', $this->_filterQuery);
        endif;
        if (is_array($value)):
                $value = 'IN ('.implode(',', $value).')';
        $query = sprintf('SELECT * FROM '.$this->_tableName.' WHERE '.$field.' '.$type.' '.$sqllimit, $this->secsql($value)); else:
                if (stripos($value, '%') !== false):
                    $query = sprintf('SELECT * FROM '.$this->_tableName.' WHERE '.$field.' LIKE "'.$type.'" '.$filter.' '.$sqllimit, $this->secsql($value)); else:
                    $query = sprintf('SELECT * FROM '.$this->_tableName.' WHERE '.$field.'='.$type == '%u' ? $type : '"'.$type.'"'.' '.$filter.' '.$sqllimit, $this->secsql($value));
        endif;
        endif;
        $rows = $this->execSQL($query);
        $rows = $this->fetch($rows);

        return $rows;
    }

    public function filterByTableField($field, $value, $type = '%u')
    {
        if ($type == '%u'):
                $this->_filterQuery[] = sprintf(''.$field.'='.$type.'', $this->secsql($value)); else:
                $this->_filterQuery[] = sprintf(''.$field.'="'.$type.'"', $this->secsql($value));
        endif;

        return $this;
    }

    public function __set($name, $value)
    {
        if (!is_null($value)):
                $this->_fields[$name] = $value; else:
                $this->_fields[$name] = null;
        endif;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_fields)):
                return $this->_fields[$name]; else:
                throw new Exception('[Erro CGAR168] Campo \''.$name.'\' inexistente');
        endif;
    }

    public function __call($name, $values)
    {
        if (stripos($name, 'getBy') !== false):
                $field = strtolower(str_replace('getBy', '', $name));
        $value = (isset($values[0])) ? $values[0] : null;
        $type = (isset($values[1])) ? $values[1] : ((gettype($values[0]) == 'integer') ? '%u' : '%s');

        return $this->getByTableField($field, $value, $type); elseif (stripos($name, 'getAllBy') !== false):
                $field = strtolower(str_replace('getAllBy', '', $name));
        $value = (isset($values[0])) ? $values[0] : null;
        $type = (isset($values[1])) ? $values[1] : ((gettype($values[0]) == 'integer') ? '%u' : '%s');
        $limit = (isset($values[2])) ? $values[2] : null;
        $offset = (isset($values[3])) ? $values[3] : null;

        return $this->getAllByTableField($field, $value, $type, $limit, $offset); elseif (stripos($name, 'filterBy') !== false):
                $field = strtolower(str_replace('filterBy', '', $name));
        if (isset($values[0])):
                    if (!is_null($values[0])):
                        $value = (isset($values[0])) ? $values[0] : null;
        $type = (isset($values[1])) ? $values[1] : ((gettype($values[0]) == 'integer') ? '%u' : '%s');

        return $this->filterByTableField($field, $value, $type); else:
                        return $this;
        endif; else:
                  return $this;
        endif;
        endif;
    }

    public function fetch($rows)
    {
        $return = [];
        foreach ($rows as $row):
                $obj = new self($this->_className);
        if (!empty($this->_key)):
                    $obj->setKey($this->_key);
        endif;
        foreach ($row as $field=>$value):
                    if (!is_int($field)):
                        $obj->$field = $value;
        endif;
        endforeach;
        $return[] = clone $obj;
        endforeach;

        return $return;
    }

    public function insert($debug = false)
    {
        $values = null;
        $fields = null;
        $eval = '$q=sprintf(\'INSERT INTO '.$this->_tableName.' ';
        if (count($this->_fields) > 0):
                foreach ($this->_fields as $field=>$value):
                    if (is_null($values)):
                        $eval .= '('; else:
                        $eval .= ',';
        $values .= ',';
        $fields .= ',';
        endif;
        $eval .= $field;
        $values .= '"%s"';
        $fields .= '$this->secsql($this->'.$field.')';
        endforeach;
        endif;
        $eval .= ')';
        $eval .= ' VALUES ('.$values.')\','.$fields.');';
        if ($debug):
                echo $eval;
        endif;
        eval($eval);
        $this->execSQL($q, $debug);
        $this->_keyvalue = [$this->getInsertId()];

        return $this;
    }

    public function update($debug = false)
    {
        $fields = null;
        $eval = '$q=sprintf(\'UPDATE '.$this->_tableName.' SET ';
        if (count($this->_fields) > 0):
                foreach ($this->_fields as $field=>$value):
                    if (!is_null($fields)):
                        $eval .= ',';
        endif;
        if (is_null($value)):
                        $eval .= $field.'=NULL ';
        $fields .= ''; else:
                        $eval .= $field.'="%s" ';
        $fields .= '$this->secsql($this->'.$field.'),';
        endif;
        endforeach;
        endif;
        $eval .= 'WHERE '.$this->_key[0].'="%u"';
        $fields .= '$this->secsql('.$this->_keyvalue[0].')';
        $eval .= '\','.$fields.');';
        if ($debug):
                echo $eval;
        endif;
        eval($eval);
        $this->execSQL($q, $debug);
        $this->_keyvalue = [$this->getInsertId()];

        return $this;
    }

    public function delete($debug = false)
    {
        $this->updateKeyValue();
        $fields = null;
        $eval = '$q=sprintf(\'DELETE FROM '.$this->_tableName.' ';
        $eval .= 'WHERE '.$this->_key[0].'="%u"';
        $fields .= '$this->secsql('.$this->_keyvalue[0].')';
        $eval .= '\','.$fields.');';
        if ($debug):
                echo $eval;
        endif;
        eval($eval);
        $this->execSQL($q);

        return $this;
    }

    public function save($validate = true, $exception = true, $debug = false)
    {
        $valid = true;
        if ($validate):
                $valid = $this->validate($exception);
        endif;
        if ($valid):
                $this->updateKeyValue();
        if (!empty($this->_keyvalue)):
                    return $this->update($debug); else:
                    return $this->insert($debug);
        endif; else:
                return false;
        endif;
    }

    public function setKey($key = ['id'])
    {
        $this->_key = $key;
        $this->updateKeyValue();
    }

    public function updateKeyValue()
    {
        $this->_keyvalue = [];
        foreach ($this->_key as $key):
                if (isset($this->_fields[$key])):
                    $this->_keyvalue[] = $this->_fields[$key];
        endif;
        endforeach;
    }

    public function getKeyValue($index = null)
    {
        if (!is_null($index)):
                return $this->_keyvalue[$index]; else:
                return $this->_keyvalue;
        endif;
    }

    public function getFieldList()
    {
        return $this->_fields;
    }

    public function getValidateMsg()
    {
        return $this->_validateMsg;
    }

    public function secsql($string)
    {
        //$string=mysql_real_escape_string($string, self::$connection);
        $string = addslashes($string);

        return $string;
    }

    public function validate($exception = true)
    {
        $valid = true;
        $realfieldlist = [];
        $fields = self::$connection->query('DESC '.$this->_tableName)->fetchAll();
        foreach ($fields as $field):
                $realfieldlist[] = $field['Field'];
        $emptyfield = ((!isset($this->_fields[$field['Field']])) || (is_null($this->_fields[$field['Field']])) || ($this->_fields[$field['Field']] == ''));

        // Validando NOT NULLs
        if (($field['Extra'] != 'auto_increment') && ($field['Default'] == '') && (($field['Null'] == 'NO'))):
                    if ($emptyfield):
                        $valid = false;
        $this->_validateMsg = 'Campo \''.$field['Field'].'\' n&atilde;o foi preenchido';
        if ($exception):
                            throw new Exception('Campo \''.$field['Field'].'\' n&atilde;o foi preenchido');
        endif;
        endif;
        endif;

        if (!$emptyfield):
                    // Validando Tipos
                    $types = '['.implode('|', $this->_types).']';
        preg_match($types, $field['Type'], $type);
        if ($type[0] != 'enum'):
                        preg_match('/[0-9]+/', $field['Type'], $size); else:
                        $size[0] = 0;
        endif;
        switch ($type):
                        case ($type == 'int') || ($type == 'smallint') || ($type == 'mediumint') || ($type == 'bigint') || ($type == 'bit') || ($type == 'timestamp'):
                            $this->_fields[$field['Field']] = (int) $this->_fields[$field['Field']];
        break;
        case ($type == 'float') || ($type == 'decimal') || ($type == 'double'):
                            $this->_fields[$field['Field']] = (float) $this->_fields[$field['Field']];
        break;
        endswitch;

        // Validando Tamanho. Caso exceda o limite o valor é truncado
        if ((isset($size[0])) && ($size[0] > 0)):
                        if ($field['Field'] == 'TAMANHO'):
                            alert($size[0]);
        endif;
        if ((strlen($this->_fields[$field['Field']])) > $size[0]):
                            $this->_fields[$field['Field']] = substr($this->_fields[$field['Field']], 0, $size[0]);
        endif;
        endif;

        // Validando chave estrangeira
        //if ($field['Key']=='MUL'):
        /*
          Por motivo de desempenho, a validacao de chave estrangeira deve ser realizada
          pelo proprio banco e apenas tratada a excessao, e nao pela linguagem para evitar
          verificacoes desnecessarias que ja sao tratadas pelos gerenciadores de banco de dados */
        //endif;
        endif;
        endforeach;
        foreach ($this->_fields as $field=>$value):
                if (!in_array($field, $realfieldlist)):
                    $valid = false;
        $this->_validateMsg = 'Campo \''.$field.'\' n&atilde;o encontrado na entidade \''.$this->_tableName.'\'';
        if ($exception):
                        throw new Exception('Campo \''.$field.'\' n&atilde;o encontrado na entidade \''.$this->_tableName.'\'');
        endif;
        endif;
        endforeach;

        return $valid;
    }
}
