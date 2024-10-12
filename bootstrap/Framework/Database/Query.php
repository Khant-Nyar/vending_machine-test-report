<?php

namespace bootstrap\Framework\Database;

use Exception;
use PDO;

/**
 * Query Class
 *
 * This class extends the `Database` class and provides a fluent interface
 * for executing CRUD operations on a database table.
 *
 * @uses Query::from('table_name')->insert($data) Insert a new record into the database.
 * 
 * Example:
 * Database::from('users')->insert([
 *    'username' => 'john_doe',
 *    'email'    => 'john@example.com',
 * ]);
 *
 * @uses Query::from('table_name')->where($column, $operator, $value)->update($data) Update records in the database.
 * 
 * Example:
 * Database::from('users')
 *    ->where('username', '=', 'john_doe')
 *    ->update([
 *        'email' => 'john_new@example.com',
 *    ]);
 *
 * @uses Query::from('table_name')->where($column, $operator, $value)->delete() Delete records from the database.
 * 
 * Example:
 * Database::from('users')
 *    ->where('username', '=', 'john_doe')
 *    ->delete();
 *
 * @uses Query::from('table_name')->where($column, $operator, $value)->get($columns = ['*']) Fetch records from the database.
 * 
 * Example:
 * $results = Database::from('users')
 *    ->where('email', '=', 'john@example.com')
 *    ->get();
 *
 * @uses Query::from('table_name')->where($column, $operator, $value)->limit($number)->orderBy($column, $direction)->get($columns = ['*']) Fetch filtered, limited, and ordered records.
 * 
 * Example:
 * $results = Database::from('users')
 *    ->where('active', '=', 1)
 *    ->limit(10)
 *    ->orderBy('created_at', 'DESC')
 *    ->get();
 */
class Query extends Database
{

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
