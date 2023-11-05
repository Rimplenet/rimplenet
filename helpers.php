<?php
const CONFIG_FILE = __DIR__."/rimplenetconfig.json";
// $file = plugin_dir_path(dirname(__FILE__)) . '/rimplenetconfig.json';


if (!function_exists('rimplenetGetConfigData')) {
    /**
     * Get specific data from json file
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function rimplenetGetConfigData($key)
    {
        if (file_exists(CONFIG_FILE)) {
            $jsonData = json_decode(file_get_contents(CONFIG_FILE), true);

            if ($jsonData !== null) {
                if (array_key_exists($key, $jsonData)) {
                    return $jsonData[$key];
                }
                return null;
            }
            return null;
        }
    }
}


if (!function_exists('rimplenetCheckIfAdminTransactionsAreEnabled')) {
    /**
     * Get specific data from json file
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function rimplenetCheckIfAdminTransactionsAreEnabled($key)
    {
        $enabled = rimplenetGetConfigData($key);
        if(is_null($enabled)){
            return false;
        }
        return $enabled;
    }
}


if (!function_exists('rimplenet_dd')) {
    /**
     * Get specific data from json file
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function rimplenet_dd(...$vars)
    {
        foreach ($vars as $var) {
            echo "<div style='background:black; color: green'>".var_dump($var)."<div> <br>";
        }
        die;   
    }
}