<?php

namespace bootstrap\Framework;


class Request
{
    private array $data;
    private array $files;
    private array $headers;

    public function __construct()
    {
        $this->data = $_REQUEST; // Merged input data (GET and POST)
        $this->files = $_FILES;  // Uploaded files
        $this->headers = $this->getAllHeaders();
    }

    /**
     * Get a field from the request data.
     *
     * @param string $key
     * @return mixed
     */
    public function field(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Check if the request is an AJAX request.
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        return isset($this->headers['X-Requested-With']) && strtolower($this->headers['X-Requested-With']) === 'xmlhttprequest';
    }

    /**
     * Get all request data.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get all uploaded files.
     *
     * @return array
     */
    public function files(): array
    {
        return $this->files;
    }

    /**
     * Get a specific file from the uploaded files.
     *
     * @param string $key
     * @return array|null
     */
    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get all headers from the request.
     *
     * @return array
     */
    private function getAllHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $headers[str_replace('_', '-', substr($key, 5))] = $value;
            }
        }
        return $headers;
    }

    public function getMethod()
    {
        if (isset($_POST['_method']) && in_array($_POST['_method'], ['GET', 'POST', 'PUT', 'DELETE'])) {
            return $_POST['_method'];
        }
        return $_SERVER['REQUEST_METHOD'];
    }
}
