<?php
class RimplenetApiKeys extends ApiKey
{

    /**
     * Generate Api key
     * @param array key params
     * @return bool|array|object
     */
    public function _genkey( array $params)
    {
        # Check for required Fields
        if ($this->checkEmpty($params)) return;
        # Validate api key type provided by user
        if(!self::isValidTokenType((string) $params['key_type'])) 
        return self::error(['api_key_types' => self::$apiKeyTypes], "Invalid ApiKey Type");
        # Set the required user information gotten from JWT token
        $id = $this->user->ID;
         # Generate apiKey>application Password using WP function
        $key = WP_Application_Passwords::create_new_application_password($id, $params);
        # Throw error error occurs
        if (isset($key->errors)) return ApiKey::error($key, "Error", 409);
        # return array of jey generated and save in DB
        $key = self::createKey(array_merge($key[1], $params));
        return ApiKey::success($key, "Api Ky Generated");
    }
    
    protected function createKey(array $data)
    {
        // return ApiKey::success($this->user, "Api Ky Generated");

        $keyId = wp_insert_post([
            'post_author'   => $this->data->ID,
            'post_title'    => $data['name'],
            'post_content'  => "",
            'post_status'   => 'publish',
            'post_type'     => self::POST_TYPE
        ]);

        $username = $this->user->user_login;
        $app_password = WP_Application_Passwords::chunk_password($data['password']);

        $hash = base64_encode("$username:$app_password");

        wp_set_object_terms($keyId, self::API_KEYS, self::TAXONOMY);


        $response = [
            'action'     => $data['action'],
            'key_type' => $data['key_type'],
            'user_id'   => $this->user->ID,
            'uuid'      => $data['uuid'],
            'app_id'    => $data['app_id'],
            'name'      => $data['name'],
            'hash'      => $hash,
            'key'       => $app_password,
            'created'   => $data['created']
        ];
        foreach ($response as $key => $value) {
            update_post_meta($keyId, $key, $value);
        }
        return $response;

    }
}
