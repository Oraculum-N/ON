<?php

namespace ON;

class Models
{
    private $_dsn = null;
    private $_dsntype = 1;
    private $_user = null;
    private $_pass = null;
    private $_host = null;
    private $_driver = null;
    private $_database = null;
    private $_driveroptions = [];
    private $_model = null;
    public static $connection = null;

    public function __construct($model = null)
    {
        if (!defined('MODEL_DIR')):
                define('MODEL_DIR', 'models');
        endif;

        return (!is_null($model)) ? $this->loadModel($model) : $this;
    }

    public function loadModel($model = null)
    {
        if ((!is_null($model))):
                $model = strtolower($model);
        $modelfile = MODEL_DIR.'/'.$model.'.php';
        if (file_exists($modelfile)):
                    include $modelfile; else:
                    throw new Exception('[Error '.__METHOD__.'] Modelo nao encontrado ('.$modelfile.') ');
        endif;
        if ($this->_dsntype == 1):
                    $dsn = preg_split('[://|:|@|/]', $this->_dsn);
        $this->_driver = strtolower($dsn[0]);
        if ($this->_driver == 'sqlite'):
                        $this->_user = '';
        $this->_pass = '';
        $this->_host = '';
        $this->_database = $dsn[2];
        $this->_driveroptions = null; else:
                        $this->_user = $dsn[1];
        $this->_pass = $dsn[2];
        $this->_host = $dsn[3];
        $this->_database = $dsn[4];
        $this->_driveroptions = isset($dsn[5]) ? $dsn[5] : null;
        $this->_dsn = $this->_driver.
                            ':host='.$this->_host.';dbname='.$this->_database;
        endif;
        endif;
        $this->_model = $model;
        endif;
        if ((!isset(self::$connection)) || (!is_null(self::$connection))):
                $this->PDO();
        endif;

        return $this;
    }

    public static function closeModel()
    {
        self::$connection = null;
    }

    public function loadTable($model = null, $key = 'id')
    {
        if (!is_null($model)):
			$model = strtolower($model);
			$class = ucwords($model);
			$modelfile = MODEL_DIR.'/tables/'.$model.'.php';
			if (file_exists($modelfile)):
				include $modelfile;
			else:
                $class = ucwords($model);
				if (!class_exists($class)):
					$eval = 'namespace ON\Tables;';
					$eval .= 'use ON\ActiveRecord;';
					$eval .= ' class '.$class.' extends ActiveRecord {';
					$eval .= ' public function __construct(){';
					$eval .= '     parent::__construct(get_class($this))';
					$eval .= '     ->setKey(array(\''.$key.'\'));';
					$eval .= ' }';
					$eval .= '}';
					eval($eval);
				endif;
			endif;
			return true;
		else:
			throw new Exception('[Error '.__METHOD__.'] Modelo nao informado ('.$model.') ');
		endif;
        return $this;
    }

    public function PDO()
    {
        $this->_driveroptions = [];
        if (extension_loaded('pdo')):
                if (in_array($this->_driver, \PDO::getAvailableDrivers())):
                    try {
                        self::$connection = new \PDO($this->_dsn, $this->_user, (!$this->_pass ? '' : $this->_pass), $this->_driveroptions);
                        self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                    } catch (\PDOException $e) {
                        throw new Exception('[Error '.__METHOD__.'] PDO Connection Error: '.$e->getMessage());
                    }

        return self::$connection; else:
                    throw new Exception('[Error '.__METHOD__.'] Nao ha driver disponivel para \''.$this->_driver.'\'');
        endif; else:
                throw new Exception('[Error '.__METHOD__.'] Extensao PDO nao carregada');
        endif;
    }

    public function generateAR($table = 'all', $create = true)
    {
        if ($table == 'all'):
			if ($this->_driver == 'sqlite'):
                $tables = self::$connection->query('SELECT name FROM sqlite_master WHERE type=\'table\';')->fetchAll();
			else:
                $tables = self::$connection->query('SHOW TABLES')->fetchAll();
			endif;
        foreach ($tables as $table):
                    $this->generateAR($table[0], $create);
        endforeach; else:
                $table = strtolower($table);
        $classear = ucwords($table);
        $class = 'class '.$classear." extends ON\ActiveRecord{\n";
        $class .= "\tpublic function __construct(){\n";
        $class .= "\t\tparent::__construct(get_class(\$this));\n";
        $class .= "\t}\n";
        $class .= "}\n";
        if ($create):
                    eval($class); else:
                    return "<?php \n".$class;
        endif;
        endif;
    }

    public function getTable($table = null)
    {
        if (is_null($table)):
                throw new Exception('[Error '.__METHOD__.'] Tabela nao informada'); else:
                return $this->loadTable($table);
        endif;
    }

    public function setDsn($dsn = null)
    {
        if (is_null($dsn)):
                throw new Exception('[Error '.__METHOD__.'] DSN nao informado'); else:
                $this->_dsn = $dsn;
        $dsn = preg_split('[://|:|@|/]', $this->_dsn);
        $this->_driver = strtolower($dsn[0]);
        if ($this->_driver == 'sqlite'):
                    $this->_user = '';
        $this->_pass = '';
        $this->_host = '';
        $this->_database = $dsn[1];
        $this->_driveroptions = null; else:
                    $this->_user = $dsn[1];
        $this->_pass = $dsn[2];
        $this->_host = $dsn[3];
        $this->_database = $dsn[4];
        $this->_driveroptions = isset($dsn[5]) ? $dsn[5] : null;
        endif;
        endif;
    }

    public function getModelName()
    {
        return $this->_model;
    }
}
