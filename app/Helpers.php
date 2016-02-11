<?php

if (! function_exists('pr')) {
    /**
     * Similar function of dd
     *
     * @param      $data
     * @param bool $break
     *
     * @return array
     */
    function pr($data, $break = true)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        if ($break) {
            die();
        }
    }
}

if (! function_exists('my_encode')) {
    /**
     * Check route uri and return active string
     *
     * @param string $str
     *
     * @return string
     */
    function my_encode($str)
    {
        return base64_encode(str_rot13(base64_encode(trim($str))));
    }
}

if (! function_exists('my_decode')) {
    /**
     * Check route uri and return active string
     *
     * @param string $str
     *
     * @return string
     */
    function my_decode($str)
    {
        return base64_decode(str_rot13(base64_decode(trim($str))));
    }
}

if (! function_exists('get_token')) {
    /**
     * Check route uri and return active string
     *
     * @param object $request
     *
     * @return string
     */
    function get_token($request)
    {
        $token = explode(' ', $request->header('Authorization'));

        return end($token);
    }
}