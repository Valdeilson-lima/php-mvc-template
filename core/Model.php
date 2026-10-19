<?php
namespace core;

use core\Database;
use ClanCats\Hydrahon\Builder;
use ClanCats\Hydrahon\Query\Sql\FetchableInterface;
use ClanCats\Hydrahon\Query\Sql\Table;

class Model {

    protected static ?Builder $_builder = null;
    protected static ?string $table = null;
    protected static string $primaryKey = 'id';

    /**
     * Retorna a instância compartilhada do Query Builder Hydrahon.
     */
    protected static function getBuilder(): Builder {
        if (self::$_builder === null) {
            $connection = Database::getInstance();

            self::$_builder = new Builder('mysql', function($query, $queryString, $queryParameters) use ($connection) {
                $statement = $connection->prepare($queryString);
                $statement->execute($queryParameters);

                if ($query instanceof FetchableInterface) {
                    return $statement->fetchAll(\PDO::FETCH_ASSOC);
                }

                // Para INSERT, retorna o último ID gerado se aplicável
                if ($connection->lastInsertId()) {
                    return $connection->lastInsertId();
                }

                return $statement->rowCount();
            });
        }

        return self::$_builder;
    }

    /**
     * Retorna o nome da tabela no banco de dados.
     */
    public static function getTableName(): string {
        if (!empty(static::$table)) {
            return static::$table;
        }

        $className = explode('\\', get_called_class());
        $className = end($className);
        return strtolower($className) . 's';
    }

    /**
     * Retorna o escopo da tabela para este Model.
     */
    public static function query(): Table {
        return self::getBuilder()->table(static::getTableName());
    }

    /**
     * Inicia uma consulta SELECT.
     */
    public static function select($fields = []) {
        return static::query()->select($fields);
    }

    /**
     * Inicia uma inserção.
     */
    public static function insert($fields = []) {
        return static::query()->insert($fields);
    }

    /**
     * Inicia uma atualização.
     */
    public static function update($fields = []) {
        return static::query()->update($fields);
    }

    /**
     * Inicia uma exclusão.
     */
    public static function delete() {
        return static::query()->delete();
    }

    /**
     * Retorna todos os registros da tabela.
     */
    public static function all(): array {
        return static::select()->get();
    }

    /**
     * Localiza um registro pela chave primária.
     */
    public static function find(mixed $id): ?array {
        $result = static::select()->where(static::$primaryKey, $id)->one();
        return $result ?: null;
    }

    /**
     * Adiciona cláusula WHERE à consulta.
     */
    public static function where(string $column, mixed $operatorOrValue, mixed $value = null) {
        if ($value === null) {
            return static::select()->where($column, $operatorOrValue);
        }
        return static::select()->where($column, $operatorOrValue, $value);
    }

    /**
     * Insere um registro e retorna o registro criado.
     */
    public static function create(array $attributes): ?array {
        $id = static::insert($attributes)->execute();
        if ($id) {
            return static::find($id);
        }
        return null;
    }
}