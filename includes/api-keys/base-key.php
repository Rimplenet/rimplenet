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

    static $actionType = [
        'rimplenet_wallets', 'rimplenet_credits', 'rimplenet_debits',
        'rimplenet_transfers', 'rimplenet_withdrawals', 'rimplenet_users'
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
            # execute if initiated as a method
            if ($argc[1]) return call_user_func_array([$this, $method], $argc);
            # execute if initiated as from api
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

    /**
     * Decode Bearer token
     * @param string $token authoization bearer token
     * @return bool
     */
    public function decodeBearer($token)
    {
        # Verify authorization token
        $authorization = (new RimplenetAuthorization)->authorization($token);
        if (!$authorization) exit;

        # get user from decoded data
        $this->user = $authorization['data']->user;
        # verify user from token is admin
        if (!self::isAdministrator($this->user->roles)) :
            Res::error(['unauthorized' => 'You are not allowed to perform operation'], 'Authorization Denied', 401);
            return false;
        endif;
        return true;
    }

    /**
     * Decode Basic token
     * @param string $token authoization bearer token
     * @return bool
     */
    public function decodeBasic($token)
    {
        $decrypted = \base64_decode($token);
        [$username, $key] = explode(':', $decrypted);
        $user = get_user_by('login', $username);
        # chek if user exists
        if (!$user) return Res::error(['authorization' => 'Authorization Denied'], 'Invalid Token', 401);
        $isAdministrator = $user->caps['administrator'];

        # chek if user is Administrator
        if (!$isAdministrator) return $this->error(['unauthorized' => "Authoriation denied"], 'Unauthorized', 401);
        $posts = self::getPostByKey($key);
        if (!$posts) return Res::error(['invalid' => 'Invalid Token'], 'Invalid Token');
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
     * Validate action type provided is valid
     * @param string $actionType
     * @return bool
     */
    protected static function isValidActionType(string $actionType)
    {
        // if($actionType == '') return;
        return in_array(strtolower(trim($actionType)), self::$actionType);
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
        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'key' AND meta_value = '" . $key . "' "));
        return $data;
    }

    /**
     * Set API key Permission
     * @param string $actions > actions to be allowed on API key
     * @param string $permission > Permisson set to API key
     * @return
     */
    public function approveKey(string $actions, string $permisson)
    {
        if ($actions !== '') :
            # convert action string to an array
            $actions = explode(',', str_replace(' ', '',$actions));

        endif;
    }

    /**
     * 
     */
    public function applyAction($action)
    {
        // $actions = [];
        // if(in_array(strtolower($action), self::$actionType)){

        // }
    }

    public function formatKey($key)
    {
        $postId = $key[0]->post_id;
        return [
            'action'    => get_post_meta($postId, 'action', true),
            'allowedAction'    => get_post_meta($postId, 'allowed_action', true),
            'keyType'  => get_post_meta($postId, 'key_type', true),
            'userId'   => get_post_meta($postId, 'user_id', true),
            'uuid'      => get_post_meta($postId, 'uuid', true),
            'appId'    => get_post_meta($postId, 'app_id', true),
            'name'      => get_post_meta($postId, 'name', true),
            'hash'      => get_post_meta($postId, 'hash', true),
            'key'       => get_post_meta($postId, 'key', true),
            'created'   => get_post_meta($postId, 'created', true)
        ];
    }
}
