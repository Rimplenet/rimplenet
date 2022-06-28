<?php

use ApiKey\ApiKey;

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
        $administrator = $this->data->roles;
        $id = $this->data->ID;
        # Confirm user creating ApiKey is an administrator
        if (!self::isAdministrator($administrator)) return ApiKey::error(['unauthorized' => 'You are not allowed to perform operation', 'user' => $this->data], 'Authorization Denied', 401);
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
        $keyId = wp_insert_post([
            'post_author'   => $this->data->ID,
            'post_title'    => $data['name'],
            'post_content'  => "",
            'post_status'   => 'publish',
            'post_type'     => self::POST_TYPE
        ]);

        $username = $this->data->user_login;
        $app_password = WP_Application_Passwords::chunk_password($data['password']);

        $hash = base64_encode("$username:$app_password");

        wp_set_object_terms($keyId, self::API_KEYS, self::TAXONOMY);


        $response = [
            'action'     => $data['action'],
            'key_type' => $data['key_type'],
            'user_id'   => $this->data->ID,
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
