<?php

namespace bootstrap\Framework\Database;

use Exception;
use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;
    private $statement;

    private string $table = '';
    private array $whereClauses = [];
    private string $limitClause = '';
    private string $orderByClause = '';
    private array $bindValues = []; // Store bound values

    private function __construct(?array $config = null, string $username = 'root', string $password = '')
    {
        if (is_null($config)) {
            $config = config('database.database');
        }

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;port=%d;charset=%s',
            $config['host'] ?? '127.0.0.1',
            $config['dbname'] ?? '',
            $config['port'] ?? 3306,
            $config['charset'] ?? 'utf8mb4'
        );

        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    // Singleton Instance
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    // Set the table
    public static function from(string $table): self
    {
        $instance = self::getInstance();
        $instance->table = $table;
        return $instance;
    }

    // Add where clause
    public function where(string $column, $operator = null, $value = null): self
    {
        // Handle the case where only a column and value are provided (with implicit '=' operator)
        if (is_null($value)) {
            $value = $operator; // Treat $operator as $value
            $operator = '=';     // Default operator is '='
        }

        $this->whereClauses[] = "$column $operator :$column"; // Add where clause
        $this->bindValues[$column] = $value; // Save value for binding
        return $this;
    }

    // Set limit
    public function limit(int $limit): self
    {
        $this->limitClause = "LIMIT $limit";
        return $this;
    }

    // Set order by
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderByClause = "ORDER BY $column $direction";
        return $this;
    }

    // Execute the query and fetch the results
    public function get(array $columns = ['*'], int $offset = 0, int $limit = 10)
    {
        $columnsList = implode(', ', $columns);
        $sql = "SELECT $columnsList FROM $this->table";

        // Append WHERE clause if exists
        if (!empty($this->whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereClauses);
        }

        // Append ORDER BY clause if exists
        if (!empty($this->orderByClause)) {
            $sql .= ' ' . $this->orderByClause;
        }

        // Append LIMIT and OFFSET for pagination
        $sql .= " LIMIT :limit OFFSET :offset";

        // Prepare and execute the query
        $statement = $this->connection->prepare($sql);

        // Bind the values for where clauses
        foreach ($this->bindValues as $column => $value) {
            $statement->bindValue(":$column", $value);
        }

        // Bind limit and offset
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);

        $statement->execute();

        $results = $statement->fetchAll();

        if (count($results) === 1) {
            return $results[0];
        }

        return $results;
    }

    public function first(array $columns = ['*'])
    {
        $columnsList = implode(', ', $columns);
        $sql = "SELECT $columnsList FROM $this->table";

        // Append WHERE clause if exists
        if (!empty($this->whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereClauses);
        }

        // Append ORDER BY clause if exists
        if (!empty($this->orderByClause)) {
            $sql .= ' ' . $this->orderByClause;
        }

        // Limit to 1 record
        $sql .= " LIMIT 1";

        // Prepare and execute the query
        $statement = $this->connection->prepare($sql);

        // Bind the values for where clauses
        foreach ($this->bindValues as $column => $value) {
            $statement->bindValue(":$column", $value);
        }

        $statement->execute();

        // Fetch the first result
        $result = $statement->fetch();

        return $result ?: null; // Return the first result or null if no result
    }



    // Method to count total products
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM $this->table";

        // Append WHERE clause if exists
        if (!empty($this->whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereClauses);
        }

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        return (int)$statement->fetchColumn();
    }

    // INSERT Method (Create)
    public function insert(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";

        $statement = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        return $statement->execute();
    }

    // UPDATE Method (Update)
    public function update(array $data): bool
    {
        $updates = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE $this->table SET $updates";

        if (!empty($this->whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereClauses);
        }

        $statement = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        foreach ($this->bindValues as $column => $value) {
            $statement->bindValue(":$column", $value);
        }

        return $statement->execute();
    }

    // DELETE Method (Delete)
    public function delete(): bool
    {
        $sql = "DELETE FROM $this->table";

        if (!empty($this->whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereClauses);
        }

        $statement = $this->connection->prepare($sql);

        foreach ($this->bindValues as $column => $value) {
            $statement->bindValue(":$column", $value);
        }

        return $statement->execute();
    }


    public function generateSlug($name, $uniqueCode)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))) . '-' . $uniqueCode;

        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if the slug already exists in the database.
     *
     * @param string $slug The slug to check for uniqueness.
     * @return bool True if exists, otherwise false.
     */
    protected function slugExists($slug)
    {
        $result = $this->where('slug', '=', $slug)->get(['slug']);
        return !empty($result);
    }

    public function insertWithSlug($data)
    {
        if (empty($data['name'])) {
            throw new Exception('Name field is required.');
        }

        $uniqueCode = uniqid();
        $data['slug'] = $this->generateSlug($data['name'], $uniqueCode);

        return $this->insert($data);
    }
}
