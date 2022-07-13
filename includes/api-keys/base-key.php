<?php
class ApiKey
{

    /**
     * Types of keys / key modes
     * @property array
     */
    static $apiKeyTypes = [
        'read-only',
        'read-write',
        'write-only'
    ];

    const API_KEYS = "API-KEYS";

    public function __construct()
    {
        // add_action('rimplenet_api_request_started', array($this, 'decodeBasic'), 1, 3);
    }

    /**
     * Run before other methods run ...
     * check if user is an admin before running method
     * @return boolean
     */
    public function __call($method, $argc)
    {
        $method = "_" . $method;
        if (method_exists($this, $method)) :
            if ($this->pre() !== false) call_user_func_array([$this, $method], $argc);
        endif;
    }

    protected function pre()
    {
        return $this->requireAdmin();
    }

    /**
     * Confirm user is an admin
     * @return bool
     */
    protected function requireAdmin()
    {
        # Get headers
        $header = apache_request_headers();
        # seperate Authorization name from token
        [$name, $token] = explode(' ', $header['Authorization']);
        if ($name == 'Bearer') :
            return $this->decodeBearer($token);
        elseif ($name == "Basic") :
            return $this->decodeBasic($token);
        endif;
    }

    public function decodeBearer($token)
    {
        # Verify authorization token
        $authorization = (new RimplenetAuthorization)->authorization($token);
        # get user from decoded data
        $this->user = $authorization['data']->user;
        # verify user from token is admin
        if (!self::isAdministrator($this->user->roles)) :
           Res::error(['unauthorized' => 'You are not allowed to perform operation'], 'Authorization Denied', 401);
            return false;
        endif;
        return true;
    }

    public function decodeBasic($token)
    {
        $decrypted = \base64_decode($token);
        [$username, $key] = explode(':', $decrypted);
        $user = get_user_by('login', $username);
        $isAdministrator = $user->caps['administrator'];
        echo json_encode($decrypted); exit;
        if (!$isAdministrator) return $this->error(['unauthorized' => "Authoriation denied"], 'Unauthorized', 401);
        $posts = self::getPostByKey($key);
        if(!$posts) return Res::error(['invalid' => 'Invalid Token'], 'Invalid Token');
        Res::success($this->formatKey($posts), 'o');
        return false;
    }

    /**
     * Validate token type provided is valid
     * @param string $tokenType
     * @return bool
     */
    protected static function isValidTokenType(string $tokenType)
    {
        return in_array($tokenType, self::$apiKeyTypes);
    }

    /**
     * Check if user role is administrator
     * @param array $roles
     * @return bool
     */
    protected static function isAdministrator(array $roles)
    {
        return in_array('administrator', $roles);
    }

    protected static function getPostByKey($key)
    {
        $key = htmlspecialchars(trim($key));
        global $wpdb;
        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'key' AND meta_value = '".$key."' "));
        return $data;
    }

    public function formatKey($key)
    {
        $postId = $key[0]->post_id;
        return [
            'action'    => get_post_meta($postId, 'action', true),
            'key_type'  => get_post_meta($postId, 'key_type', true),
            'user_id'   => get_post_meta($postId, 'user_id', true),
            'uuid'      => get_post_meta($postId, 'uuid', true),
            'app_id'    => get_post_meta($postId, 'app_id', true),
            'name'      => get_post_meta($postId, 'name', true),
            'hash'      => get_post_meta($postId, 'hash', true),
            'key'       => get_post_meta($postId, 'key', true),
            'created'   => get_post_meta($postId, 'created', true)
        ];
    }
}
