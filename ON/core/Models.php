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
        if ($this->_dsntype == 2):
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

    public function saveModel($table = 'all', $debug = true)
    {
        $table = strtolower($table);
        if ($table == 'all'):
                $tables = self::$connection->query('SHOW TABLES')->fetchAll();
        foreach ($tables as $table):
                    $dtodir = MODEL_DIR.'/dto/';
        $daodir = MODEL_DIR.'/dao/';
        if (!file_exists($dtodir)):
                        if (is_writable(MODEL_DIR)):
                            mkdir($dtodir); else:
                            throw new Exception('[Error '.__METHOD__.'] Sem permissao para criar diretorio DTO');
        endif;
        endif;
        if (!file_exists($daodir)):
                        if (is_writable(MODEL_DIR)):
                            mkdir($daodir); else:
                            throw new Exception('[Error '.__METHOD__.'] Sem permissao para criar diretorio DAO');
        endif;
        endif;

        $modelfile = $dtodir.$table[0].'.php';
        if (is_writable($dtodir)):
                        $model = $this->GenerateDTO($table[0], false);
        $mf = fopen($modelfile, 'w');
        fwrite($mf, $model);
        fclose($mf); else:
                        throw new Exception('[Error '.__METHOD__.'] O arquivo nao pode ser gravado ('.$modelfile.')');
        endif;

        $modelfile = $daodir.$table[0].'.php';
        if (is_writable($daodir)):
                        $model = $this->GenerateDAO($table[0], false);
        $mf = fopen($modelfile, 'w');
        fwrite($mf, $model);
        fclose($mf); else:
                        throw new Exception('[Error '.__METHOD__.'] O arquivo nao pode ser gravado ('.$modelfile.')');
        endif;
        endforeach;
        endif;
//            if($debug):
//                echo 'Classes geradas com sucesso!<br />';
//                echo 'Para carregar as classes geradas utilize o seguinte c&oacute;digo:<br />';
//                echo '<pre>';
//                highlight_string("<?php\n\tOraculum::Load('Models');\n\t\$db=new Oraculum_Models('".$this->_model."');\n\t\$db->LoadModelClass();");
//                echo '</pre>';
//            endif;
    }

    public function loadModelClass($model = 'all', $type = 'AR', $key = 'id')
    {
        if (!is_null($model)):
                if ($model == 'all'):
                    if ($type == 'DO'):
                        foreach (glob(MODEL_DIR.'/dto/*.php') as $filename):
                            include_once $filename;
        endforeach;
        foreach (glob(MODEL_DIR.'/dao/*.php') as $filename):
                            include_once $filename;
        endforeach; else:
                        foreach (glob(MODEL_DIR.'/ar/*.php') as $filename):
                            include_once $filename;
        endforeach;
        endif; else:
                    $model = strtolower($model);
        if ($type == 'DO'):
                        $modelfile = MODEL_DIR.'/dto/'.$model.'.php';
        if (file_exists($modelfile)) :
                            include($modelfile); else:
                            throw new Exception('[Error '.__METHOD__.'] Modelo nao encontrado ('.$modelfile.') ');
        endif;
        $modelfile = MODEL_DIR.'/dao/'.$model.'.php';
        if (file_exists($modelfile)) :
                            include($modelfile); else:
                            throw new Exception('[Error '.__METHOD__.'] Modelo nao encontrado ('.$modelfile.') ');
        endif; else:
                        $modelfile = MODEL_DIR.'/ar/'.$model.'.php';
        if (file_exists($modelfile)):
                            include_once $modelfile; else:
                            if (!$this->loadTable($model, $key)):
                                throw new Exception('[Error '.__METHOD__.'] Modelo nao encontrado ('.$modelfile.') ');
        endif;
        endif;
        endif;
        endif;
        endif;

        return $this;
    }

    public function loadDynamicModelClass($model = null, $key = 'id')
    {
        return $this->loadTable($model = null, $key = 'id');
    }

    public function loadTable($model = null, $key = 'id')
    {
        if (!is_null($model)):
                $class = ucwords($model);
        if (!class_exists($class)):
                    $eval = 'namespace ON;';
        $eval .= 'use ON\DB;';
        $eval .= ' class '.$class.' extends DB {';
        $eval .= ' public function __construct(){';
        $eval .= '     parent::__construct(get_class($this))';
        $eval .= '     ->setKey(array(\''.$key.'\'));';
        $eval .= ' }';
        $eval .= '}';
        eval($eval);
        endif;

        return true; else:
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

    public function generateClass($table = 'all', $create = true)
    {
        $table = strtolower($table);
        if ($table == 'all'):
                $tables = self::$connection->query('SHOW TABLES')->fetchAll();
        foreach ($tables as $table):
                    $this->generateDTO($table[0], $create);
        $this->generateDAO($table[0], $create);
        endforeach; else:
                try {
                    $desc = self::$connection->query('DESC '.$table)->fetchAll();
                    $classedto = ucwords($table).'DTO';
                    $class = '  class '.$classedto.' extends DBO'."\n";
                    $class .= "  {\n";
                    $contador = 0;
                    foreach ($desc as $d):
                        $campo[$contador] = $d['Field'];
                    $tipo[$contador] = $d['Type'];
                    $null[$contador] = $d['Null'];
                    $key[$contador] = $d['Key'];
                    $default[$contador] = $d['Default'];
                    $extra[$contador] = $d['Extra'];
                    if (is_null($default[$contador])):
                            $default[$contador] = 'NULL'; else:
                            $default[$contador] = "'".$default[$contador]."'";
                    endif;
                    if (strpos($tipo[$contador], 'int') === false):
                            $tiposql[$contador] = '%s'; else:
                            $tiposql[$contador] = '%u';
                    endif;
                    $contador++;
                    endforeach;
                    for ($c = 0; $c < $contador; $c++):
                        $class .= '    public $'.$campo[$c].'='.$default[$c].";\n";
                    endfor;
                    $class .= "\n    public function ".$classedto."(){}\n";

                    for ($c = 0; $c < $contador; $c++):
                        $name = ucwords($campo[$c]);
                    if ($key[$c] != ''):
                            $class .= "\n    // ".$name.' ('.$key[$c].' '.$tipo[$c].")\n"; else:
                            $class .= "\n    // ".$name.' ('.$tipo[$c].")\n";
                    endif;
                    $class .= '    public function get'.$name."()\n";
                    $class .= "    {\n";
                    $class .= '      return $this->'.$campo[$c].";\n";
                    $class .= "    }\n";
                    $class .= '    public function set'.$name.'($'.$campo[$c].")\n";
                    $class .= "    {\n";
                    $class .= '      $this->'.$campo[$c].'=$'.$campo[$c].";\n";
                    $class .= "    }\n";
                    endfor;
                    $class .= "  }\n";
                    if ($create):
                        eval($class); else:
                        return "<?php \n".$class;
                    endif;
                } catch (PDOException $e) {
                    throw new Exception('[Error '.__METHOD__.'] PDO Connection Error: '.$e->getMessage());
                }
        endif;
    }

    public function generateDTO($table = 'all', $create = true)
    {
        $table = strtolower($table);
        if ($table == 'all'):
                $tables = self::$connection->query('SHOW TABLES')->fetchAll();
        foreach ($tables as $table):
                    $this->generateDTO($table[0], $create);
        endforeach; else:
                try {
                    $desc = self::$connection->query('DESC '.$table)->fetchAll();
                    $classedto = ucwords($table).'DTO';
                    $class = '  class '.$classedto."\n";
                    $class .= "  {\n";
                    $contador = 0;
                    foreach ($desc as $d):
                        $campo[$contador] = $d['Field'];
                    $tipo[$contador] = $d['Type'];
                    $null[$contador] = $d['Null'];
                    $key[$contador] = $d['Key'];
                    $default[$contador] = $d['Default'];
                    $extra[$contador] = $d['Extra'];
                    if (is_null($default[$contador])):
                            $default[$contador] = 'NULL'; else:
                            $default[$contador] = '\''.$default[$contador].'\'';
                    endif;
                    if (strpos($tipo[$contador], 'int') === false):
                            $tiposql[$contador] = '%s'; else:
                            $tiposql[$contador] = '%u';
                    endif;
                    $contador++;
                    endforeach;
                    for ($c = 0; $c < $contador; $c++):
                            $class .= '    private $'.$campo[$c].'='.$default[$c].";\n";
                    endfor;
                    $class .= "\n    public function ".$classedto."(){}\n";

                    for ($c = 0; $c < $contador; $c++):
                            $name = ucwords($campo[$c]);
                    if ($key[$c] != ''):
                                $class .= "\n    // ".$name.' ('.$key[$c].' '.$tipo[$c].")\n"; else:
                                $class .= "\n    // ".$name.' ('.$tipo[$c].")\n";
                    endif;
                    $class .= '    public function get'.$name."()\n";
                    $class .= "    {\n";
                    $class .= '      return $this->'.$campo[$c].";\n";
                    $class .= "    }\n";
                    $class .= '    public function set'.$name.'($'.$campo[$c].")\n";
                    $class .= "    {\n";
                    $class .= '      $this->'.$campo[$c].'=$'.$campo[$c].";\n";
                    $class .= "    }\n";
                    endfor;
                    $class .= "  }\n";
                    if ($create):
                            eval($class); else:
                            return "<?php \n".$class;
                    endif;
                } catch (\PDOException $e) {
                    throw new Exception('[Error '.__METHOD__.'] PDO Connection Error: '.$e->getMessage());
                }
        endif;
    }

    public function generateDAO($table = 'all', $create = true)
    {
        $table = strtolower($table);
        if ($table == 'all'):
                $tables = self::$connection->query('SHOW TABLES')->fetchAll();
        foreach ($tables as $table):
                    $this->generateDAO($table[0], $create);
        endforeach; else:
                try {
                    $desc = self::$connection->query('DESC '.$table)->fetchAll();
                    $classedao = ucwords($table).'DAO';
                    $classedto = ucwords($table).'DTO';
                    $classdao = '  class '.$classedao." extends DBO\n";
                    $classdao .= "  {\n\n";
                    //foreach ($desc as $d) {

                    $contador = count($desc);
                    if ($contador > 0):
                        $classdao .= "    // Select All\n";
                    $classdao .= "    public function getAll(\$limit=10)\n";
                    $classdao .= "    {\n";
                    $classdao .= '      $objDto=new '.$classedto."();\n";
                    $classdao .= "      \$return=array();\n";
                    $classdao .= "      if(floor(\$limit)!=0)\n";
                    $classdao .= "      {\n";
                    $classdao .= "      	\$sqllimit=\"LIMIT \".floor(\$limit);\n";
                    $classdao .= "      }\n";
                    $classdao .= "      else\n";
                    $classdao .= "      {\n";
                    $classdao .= "      	\$sqllimit=\"\";\n";
                    $classdao .= "      }\n";
                    $classdao .= '      $sql="SELECT * FROM '.$table." \".\$sqllimit;\n";
                    $classdao .= "      \$resultado=\$this->execSQL(\$sql);\n";
                    $classdao .= "      \$dados=\$this->dados(\$resultado);\n";
                    $classdao .= "      foreach(\$dados as \$d) {\n";
                    //$classdao.="      while(\$dados=\$this->dados(\$resultado))\n";
                    //$classdao.="      {\n";
                    foreach ($desc as $d):
                            $name = ucwords($d[0]);
                    //$classdao.="var_dump(\$dados);";
                    $classdao .= '        $objDto->set'.$name."(stripslashes(\$d['".$d[0]."']));\n";
                    //$classdao.="        \$objDto->set".$name."(stripslashes(\$dados['".$d[0]."']));\n";
                    endforeach;
                    $classdao .= "        \$return[]=clone \$objDto;\n";
                    $classdao .= "      }\n";
                    $classdao .= "      return \$return;\n";
                    $classdao .= "    }\n\n";

                    $classdao .= "    // Select by Id\n";
                    $classdao .= "    public function getById(\$id)\n";
                    $classdao .= "    {\n";
                    $classdao .= '      $objDto=new '.$classedto."();\n";
                    $classdao .= "      \$sql=sprintf('SELECT * FROM ".$table." WHERE codigo=\"%u\"',\$this->secsql(\$id));\n";
                    $classdao .= "      \$resultado=\$this->execSQL(\$sql);\n";
                    $classdao .= "      if(\$this->linhas(\$resultado)==1)\n";
                    $classdao .= "      {\n";
                    $classdao .= "      \$dados=\$this->dados(\$resultado);\n";
                    $classdao .= "      foreach(\$dados as \$d) {\n";
                    foreach ($desc as $d):
                            $name = ucwords($d[0]);
                    $classdao .= '        $objDto->set'.$name."(stripslashes(\$d['".$d[0]."']));\n";
                    endforeach;
                    $classdao .= "        \$return=clone \$objDto;\n";
                    $classdao .= "      }\n";
                    $classdao .= "      }\n";
                    $classdao .= "      else\n";
                    $classdao .= "      {\n";
                    $classdao .= "        \$return=NULL;\n";
                    $classdao .= "      }\n";
                    $classdao .= "      return \$return;\n";
                    $classdao .= "    }\n\n";

                    $classdao .= "    // Insert\n";
                    $classdao .= '    public function insert('.$classedto." \$objDto)\n";
                    $classdao .= "    {\n";
                    $classdao .= "      \$sql=sprintf('INSERT INTO ".$table.' (';
                    $c = 0;
                    foreach ($desc as $d):
                            $classdao .= $d[0];
                    if ($c < $contador - 1):
                                $classdao .= ',';
                    endif;
                    $c++;
                    endforeach;
                    $classdao .= ') VALUES (';
                    $c = 0;
                    foreach ($desc as $d):
                            if (strpos($d[1], 'int') === false):
                                $classdao .= '"%s"'; else:
                                $classdao .= '"%u"';
                    endif;
                    if ($c < $contador - 1):
                                $classdao .= ',';
                    endif;
                    $c++;
                    endforeach;
                    $classdao .= ")',\n";
                    $c = 0;
                    foreach ($desc as $d):
                            $name = ucwords($d[0]);
                    $classdao .= '               $this->secsql($objDto->get'.$name.'())';
                    if ($c < $contador - 1):
                                $classdao .= ",\n";
                    endif;
                    $c++;
                    endforeach;
                    $classdao .= "              );\n";
                    $classdao .= "      \$this->execSQL(\$sql);\n";
                    $classdao .= "      \$objDto->setCodigo(mysql_insert_id());\n";
                    $classdao .= "      return \$objDto;\n";
                    $classdao .= "    }\n\n";
                    $classdao .= "    // Update\n";
                    $classdao .= '    public function update('.$classedto." \$objDto)\n";
                    $classdao .= "    {\n";
                    $classdao .= "      if(!\$objDto->getCodigo())\n";
                    $classdao .= "        throw new Exception('Valor da chave primaria invalido');\n";
                    $classdao .= "      \$sql=sprintf('UPDATE ".$table.' SET ';
                    $c = 0;
                    foreach ($desc as $d):
                            if (strpos($d[1], 'int') === false):
                                $classdao .= $d[0].'="%s"'; else:
                                $classdao .= $d[0].'="%u"';
                    endif;
                    if ($c < $contador - 1):
                                $classdao .= ', ';
                    endif;
                    $c++;
                    endforeach;
                    $classdao .= " WHERE codigo=\"%u\"',\n";
                    $c = 0;
                    foreach ($desc as $d):
                            $name = ucwords($d[0]);
                    $classdao .= '               $this->secsql($objDto->get'.$name.'())';
                    if ($c < $contador - 1):
                                $classdao .= ",\n";
                    endif;
                    $c++;
                    endforeach;
                    $classdao .= "              );\n";
                    $classdao .= "      \$this->execSQL(\$sql);\n";
                    $classdao .= "    }\n\n";
                    $classdao .= "    // Delete\n";
                    $classdao .= '    public function delete('.$classedto." \$objDto)\n";
                    $classdao .= "    {\n";
                    $classdao .= "      if(\$objDto->getCodigo()==NULL)\n";
                    $classdao .= "          throw new Exception('Valor da chave primaria invalido.');\n";
                    $classdao .= "      \$sql=sprintf('DELETE FROM ".$table." WHERE codigo=\"%u\"',\$this->secsql(\$objDto->getCodigo()));\n";
                    $classdao .= "      \$this->execSQL(\$sql);\n";
                    $classdao .= "    }\n\n";
                    $classdao .= "    // Save\n";
                    $classdao .= '    public function save('.$classedto." &\$objDto)\n";
                    $classdao .= "    {\n";
                    $classdao .= "      if(\$objDto->getCodigo()!== null)\n";
                    $classdao .= "      {\n";
                    $classdao .= "        \$this->update(\$objDto);\n";
                    $classdao .= "      }\n";
                    $classdao .= "      else\n";
                    $classdao .= "      {\n";
                    $classdao .= "        \$this->insert(\$objDto);\n";
                    $classdao .= "      }\n";
                    $classdao .= "    }\n\n";

                    $classdao .= "    // SecSQL\n";
                    $classdao .= "    public function secsql(\$string)\n";
                    $classdao .= "    {\n";
                    $classdao .= "      \$string=mysql_real_escape_string(\$string);\n";
                    $classdao .= "      return \$string;\n";
                    $classdao .= "    }\n";
                    $classdao .= "  }\n";
                    if ($create):
                            eval($classdao); else:
                            return "<?php \n".$classdao;
                    endif;
                    endif;
                } catch (PDOException $e) {
                    throw new Exception('[Error '.__METHOD__.'] PDO Connection Error: '.$e->getMessage());
                }
        endif;
    }

    public function generateAR($table = 'all', $create = true)
    {
        if ($table == 'all'):
                $tables = self::$connection->query('SHOW TABLES')->fetchAll();
        foreach ($tables as $table):
                    $this->generateAR($table[0], $create);
        endforeach; else:
                $table = strtolower($table);
        $classear = ucwords($table);
        $class = 'class '.$classear." extends ON\DB{\n";
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
