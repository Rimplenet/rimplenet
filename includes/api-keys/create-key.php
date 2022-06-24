<?php

use ApiKey\ApiKey;

class RimplenetApiKeys extends ApiKey
{
    public function _genkey($params)
    {
        # Check for required Params
        if($this->checkEmpty($params)) return;

        # Set User details
        $administrator = $this->data->role->administrator;
        $id = $this->data->ID;

        # Check if is administrator
        if(!$administrator) return ApiKey::error(['You are not allowed to perform operation'], 'Authorization Denied', 401);
        $key = WP_Application_Passwords::create_new_application_password($id, $params);
        // $key = wp_generate_password();
        // $key = WP_Application_Passwords_List_Table::column_created();
        if(isset($key->errors)) return ApiKey::error($key, "Error", 409);
        // $key = ApiKey::apiKey();
        return ApiKey::success($key, "Api Ky Generated");
    }
    public function _decKey($params)
    {
        # Check for required Params
        if($this->checkEmpty(['api_key' => $params])) return;

        # Set User details
        $administrator = $this->data->role->administrator;
        $id = $this->data->ID;

        # Check if is administrator
        if(!$administrator) return ApiKey::error(['You are not allowed to perform operation'], 'Authorization Denied', 401);
        // $key = ApiKey::apiKey($params);
        $key = WP_Application_Passwords::get_user_application_passwords($id);
        $key = WP_Application_Passwords::chunk_password('$P$BxEQtyfOrHX1hql/ouNgIyEbtslANs0');
        // foreach($key as $k => $val){
        //     $key[$k]->chunk =  WP_Application_Passwords::chunk_password($val->password);
        // }
        return ApiKey::success($key, "Api Ky Generated");
    }

    protected static function getPassword($pass)
    {
        return WP_Application_Passwords::chunk_password($pass);
    }
}
