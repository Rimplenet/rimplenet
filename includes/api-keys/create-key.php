<?php

use ApiKey\ApiKey;

class RimplenetApiKeys extends ApiKey
{

    public function _genkey($params)
    {
        # Check for required Params
        if ($this->checkEmpty($params)) return;
        # Set User details
        $administrator = $this->data->role->administrator;
        $id = $this->data->ID;
        # Check if is administrator
        if (!$administrator) return ApiKey::error(['You are not allowed to perform operation'], 'Authorization Denied', 401);
        $key = WP_Application_Passwords::create_new_application_password($id, $params);

        if (isset($key->errors)) return ApiKey::error($key, "Error", 409);
        $key = self::createKey($key[1]);
        return ApiKey::success($key, "Api Ky Generated");
    }

    protected function createKey($data)
    {
        $keyId = wp_insert_post([
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
            'uuid'      => $data['uuid'],
            'app_id'    => $data['app_id'],
            'name'      => $data['name'],
            'hash'      => $hash,
            'key'       => $app_password,
            'password'  => $data['password'],
            'created'   => $data['created']
        ];
        foreach ($response as $key => $value) {
            update_post_meta($keyId, $key, $value);
        }
        return $response;

    }
}
