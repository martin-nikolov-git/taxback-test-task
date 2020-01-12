<?php

namespace App\Http;

/**
 * Class which handles the Requests, currently working only with GET
 */
abstract class ARequest {
    /**
     * The extracted params
     */
    protected $_params;

    /**
     * Extracts the get params, and calls the abstract validate_request method
     */
    public function __construct()
    {
        $this->_params = $_GET;
        if(!$this->validate_request($this->_params)) {
            throw new \Exception("Invalid values");
        }
    }

    /**
     * Make each request implementation be responsible for it's validation.
     */
    abstract public function validate_request(array $params):bool;

    /**
     * Gets the valiable or returns the passed default value
     * @param string $name
     * @param mixed $default The value to be returned if none exists
     */
    public function get(string $name, $default = null)
    {
        return $this->_params[$name] ?? $default;
    }

    /**
     * Gets the url without the query params
     */
    public function get_url():string
    {
        return strtok($_SERVER["REQUEST_URI"],'?');
    }
}