<?php

namespace binotby\phpcorefaster;


class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position === FALSE) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        return $this->method() === 'get';
    }

    public function isPost()
    {
        return $this->method() === 'post';
    }

    public function post()
    {
        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $data;
    }

    public function get()
    {
        $data = [];
        foreach ($_GET as $key => $value) {
            $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $data;
    }
}