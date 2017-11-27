<?php

namespace ON;

class DBO extends Models
{
    private $_host = null;
    private $_username = null;
    private $_password = null;
    private $_squema = null;

    // Contrutor
    public function __construct($model)
    {
        parent::__construct($model);
    }

    public static function execSQL($sql, $showsql = false)
    {
        if ($showsql):
                echo '<br />SQL: <pre>'.$sql.'</pre>';
        endif;

        try {
            return self::$connection->query($sql);
        } catch (\PDOException $e) {
            throw new Exception('PDO Connection Error: '.$e->getMessage());
        }
    }

    public function getInsertId()
    {
        try {
            return self::$connection->lastInsertId();
        } catch (\PDOException $e) {
            throw new Exception('PDO Connection Error: '.$e->getMessage());
        }
    }

    public function start()
    {
        $this->execSQL('begin');
    }

    public function commit()
    {
        $this->execSQL('commit');
    }

    public function rollback()
    {
        $this->execSQL('rollback');
    }

    public static function dados($query)
    {
        return $query->fetchAll();
    }

    public function linhas($query)
    {
        return $query->rowCount();
        //return $query->query('SELECT FOUND_ROWS()')->fetchColumn();
    }
}
