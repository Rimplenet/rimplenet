<?php
class RimplenetApiKeys extends ApiKey
{

    /**
     * Generate Api key
     * @param array key params
     * @return bool|array|object
     */
    public function _genkey(array $params, $isFunction = false)
    {
        if ($isFunction)  $this->user = wp_get_current_user();
        # Check for required Fields
        if ((new Utils)->checkEmpty($params)) return;
        # Validate api key type provided by user
        if (!self::isValidPermission((string) $params['key_type']))
            return Res::error(['api_key_types' => self::$apiKeyTypes], "Invalid API Key Type");

        # Validate tokenType type provided by user
        $actionType = explode(',', $params['allowed_actions']);
        foreach ($actionType as $action) {
            if (!self::isValidActionType((string) $action))
                return Res::error(['action_types' => self::$actionType], "Invalid Action Type " . trim($action));
            continue;
        }

        # Set the required user information gotten from JWT token
        $id = (int) $this->user->ID;
        # Generate apiKey>application Password using WP function

        $key = WP_Application_Passwords::create_new_application_password($id, $params);
        # Throw error error occurs
        if (isset($key->errors)) return Res::error($key, "Error", 409);
        # return array of jey generated and save in DB
        $key = $this->createKey(array_merge($key[1], $params));
        return Res::success($key, "API Key Generated");
    }

    protected function createKey(array $data)
    {
        // return ApiKey::success($this->user, "Api Ky Generated");

        $keyId = wp_insert_post([
            'post_author'   => (int) $this->user->ID,
            'post_title'    => $data['name'],
            'post_content'  => "",
            'post_status'   => 'publish',
            'post_type'     => Utils::POST_TYPE
        ]);

        $username = $this->user->user_login;
        $app_password = WP_Application_Passwords::chunk_password($data['password']);

        $hash = base64_encode("$username:$app_password");

        wp_set_object_terms($keyId, self::API_KEYS, Utils::TAXONOMY);


        $response = [
            'api_key_id'        => $keyId,
            'key_type'          => $data['key_type'],
            'user_id'           => $this->user->ID,
            'uuid'              => $data['uuid'],
            'app_id'            => $data['app_id'],
            'name'              => $data['name'],
            'hash'              => $hash,
            'key'               => $app_password,
            'created'           => $data['created'],
            'allowed_action'    => $data['allowed_actions'],
            'allowed_ip_domain' => $data['allowed_ip_domain']
        ];
        foreach ($response as $key => $value) {
            update_post_meta($keyId, $key, $value);
        }
        return $response;
    }
}
