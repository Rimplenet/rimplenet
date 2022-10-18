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
        $authorization = explode(' ', $header['Authorization']);
        $name = $authorization[0];
        $token = end($authorization);
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
    public function decodeBasic($token, $isApi = true)
    {
        $decrypted = \base64_decode($token);
        $decData = explode(':', $decrypted);
        $username = $decData[0];
        $key = end($decData);
        $user = get_user_by('login', $username);
        # chek if user exists
        if (!$user) return Res::error([
            'credentials' => 'Authorization Denied',
            'recommendation' => [
                'username' => 'Re-visit the specified username',
                'password' => 'Confirm password matches',
            ]
        ], 'Invalid Token', 401);
        $isAdministrator = $user->caps['administrator'] ?? false;

        # chek if user is Administrator
        if (!$isAdministrator) return Res::error([
            'unauthorized' => "Authoriation denied",
            'recommendation' => 'Ensure API Key is authorized by an administrator'
        ], 'Unauthorized', 403);
        $posts = self::getPostByKey($key);
        if (!$posts) return Res::error([
            'invalid' => 'Invalid Token',
            'recommendation' => [
                'username' => 'Re-visit the specified username',
                'password' => 'Confirm password matches'
            ]
        ], 'Invalid Token');
        # format API key data
        $formatted = $this->formatKey($posts);
        if(!$isApi) return $formatted;
        Res::success($formatted, 'o');
        return false;
    }

    /**
     * Validate token type provided is valid
     * @param string $tokenType
     * @return bool
     */
    protected static function isValidPermission(string $tokenType)
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
     * @param string $permission > permission set to API key
     * @return
     */
    public function getPermission(string $actions, string $permission)
    {
        # confirm permission is valid
        if(!$this->isValidPermission($permission)) 
        return Res::error(['permissions' => [self::$apiKeyTypes], "Invalid Permission Type $permission"]);

        # is the action is not empty
        if ($actions !== '') :
            $this->permission = $permission;
            # convert action string to an array
            $actions = explode(',', str_replace(' ', '',$actions));
            $actions = array_map([$this, 'applyAffix'], $actions);
            return $actions;
        endif;
    }

    /**
     * 
     */
    public function applyAffix($action)
    {
        $affix = '';
        $permission = $this->permission;
        if($permission == 'read-only') $affix = 'get';
        if($permission == 'write-only') $affix = 'create';
        if($permission == 'read-write') $affix = ['get', 'create', 'update', 'delete'];
        if(is_array($affix)) {
            $data = [];
            foreach ($affix as $key) {
                $data[] = str_replace('_', '_'.$key.'_' ,$action);
            }
            return $data;
        }
        $action = str_replace('_', '_'.$affix.'_' ,$action);
        return $action;
    }

    public function formatKey($key)
    {
        $postId = $key[0]->post_id;
        return [
            'allowedIpDomain' => get_post_meta($postId, 'allowed_ip_domain', true),
            'action'    => get_post_meta($postId, 'action', true),
            'allowedActions' => get_post_meta($postId, 'allowed_action', true),
            'permission'  => get_post_meta($postId, 'key_type', true),
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
