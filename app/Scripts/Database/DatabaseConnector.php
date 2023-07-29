<?php

namespace Scripts\Database;

use Generator;
use http\Exception\BadQueryStringException;
use mysqli;
use mysqli_stmt;

final class DatabaseConnector {
    private static DatabaseConnector $singleton;
    private mysqli $database;

    private function __construct() {
        $config = parse_ini_file("config.ini", true)['Database'];

        $this->database = @new mysqli(
            hostname: $config['host'],
            username: $config['username'],
            password: $config['password'],
        );
    }

    public static function getSingleton(): DatabaseConnector {
        if(!isset(DatabaseConnector::$singleton))
            DatabaseConnector::$singleton = new DatabaseConnector();

        return DatabaseConnector::$singleton;
    }

    /**
     * @param string $query SQL query which will be used, with '?' characters as parameters to be bound
     * @param mixed ...$params A variadic parameter containing every parameter which will be bound to the query <br>
     *                         It should contain at least the same number of elements as the count of '?' in the query <br>
     *                         If there happened to be more, they would be discarded
     * @throws BadQueryStringException If the provided query is not an updating one
     * @throws BadQueryStringException If the provided query is invalid
     * @return Generator Values retrieved from the database
     */
    public function getQuery(string $query, mixed ...$params): Generator {
        $stmt = $this->prepareQueryWithParams($query, ...$params);

        if (!$stmt->execute())
            throw new BadQueryStringException("The provided query was invalid");

        $fields = $stmt->result_metadata()->fetch_fields();
        $columnNames = array_column($fields, 'name');
        $bindParams = [];

        $row = [];
        foreach ($columnNames as $columnName) {
            $bindParams[] = &$row[$columnName];
        }

        $stmt->store_result();
        $stmt->bind_result(...$bindParams);

        while ($stmt->fetch())
            yield $row;

    }


    private function prepareQueryWithParams(string $query, mixed ...$params): mysqli_stmt {
        $stmt = $this->database->prepare($query);

        if(!$stmt) throw new BadQueryStringException("The provided query is invalid");

        if(count($params) == 0) return $stmt;

        $types = "";
        $bindParams = [];

        foreach ($params as $param) {
            $types .= $this->getType($param);
            $bindParams[] = $param;
        }

        $stmt->bind_param($types, ...$bindParams);

        return $stmt;
    }

    /**
     * @param mixed $var variable to be checked
     * @return string The type of the parameter in the format required in SQL queries
     */
    private function getType(mixed $var): string {
        if (is_int($var))
            return 'i';

        if (is_float($var))
            return 'd';

        if (is_string($var))
            return 's';

        return 'b';
    }

    /**
     * @param string $query SQL query which will be used, with '?' characters as parameters to be bound
     * @param mixed ...$params A variadic parameter containing every parameter which will be bound to the query <br>
     *                         It should contain at least the same number of elements as the count of '?' in the query <br>
     *                         If there happened to be more, they would be discarded
     * @throws BadQueryStringException If the provided query is not an updating one
     * @throws BadQueryStringException If the provided query is invalid
    */
    public function updateQuery(string $query, mixed ...$params): void {
        $this->prepareQueryWithParams($query, ...$params)->execute();
    }

    /**
     * @param string $query SQL query to be used with '?' characters as parameters to be bound
     * @param mixed ...$params A variadic parameter containing every parameter which will be bound to the query <br>
     *                         It should contain at least the same number of elements as the count of '?' in the query <br>
     *                         If there happened to be more, they would be discarded
     * @throws BadQueryStringException If the provided query is not an inserting one
     * @throws BadQueryStringException If the provided query is invalid
     */
    public function insertQuery(string $query, mixed ...$params): void {
        $this->prepareQueryWithParams($query, ...$params)->execute();
    }

    /**
     * @param string $query SQL query to be used with '?' characters as parameters to be bound
     * @param mixed ...$params A variadic parameter containing every parameter which will be bound to the query <br>
     *                         It should contain at least the same number of elements as the count of '?' in the query <br>
     *                         If there happened to be more, they would be discarded
     * @throws BadQueryStringException If the provided query is not a deleting one
     * @throws BadQueryStringException If the provided query is invalid
     */
    public function deleteQuery(string $query, mixed ...$params): void {
        $this->prepareQueryWithParams($query, ...$params)->execute();
    }

    public function __destruct() {
        $this->database->close();
    }

    public function __tostring(): string {
        return $this->database->error? "Connected successfully" : "Connection error: " . $this->database->error;
    }

}
