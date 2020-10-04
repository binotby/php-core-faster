<?php namespace binotby\phpcorefaster\db;

use binotby\phpcorefaster\Application;
use binotby\phpcorefaster\Model;


abstract class DbModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);

        if ($this->id) {
            $query_string = '';
            foreach ($attributes as $attribute ) {
                $query_string .= "$attribute=:$attribute,";
            }
            $query_string = rtrim($query_string, ',');
            $statement = self::prepare("UPDATE $tableName SET $query_string WHERE id=$this->id;");
    
            foreach ($attributes as $attribute) {
                $statement->bindValue(":$attribute", $this->{$attribute});
            }
        } else {
            $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).")
                VALUES(".implode(',', $params).")"
            );
    
            foreach ($attributes as $attribute) {
                $statement->bindValue(":$attribute", $this->{$attribute});
            }
        }
        
        $statement->execute();
        return true;
    }

    public function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    public function get($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");

        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public function all()
    {
        $tableName = static::tableName();
        $sql = "SELECT * FROM $tableName";
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }
}