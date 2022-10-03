<?php
class RimplenetGetApiKeys extends ApiKey
{

    public function _getKeys($hash)
    {
        return $this->queryKeys();
    }

    public function queryKeys()
    {
        $query = new WP_Query(
            array(
                'post_type' => 'rimplenettransaction',
                'post_status' => 'any',
                'author' => 'any',
                'posts_per_page' => -1,
                'paged' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'rimplenettransaction_type',
                        'field'    => 'name',
                        'terms'    =>  self::API_KEYS
                    ),
                ),
            )
        );

        if ($query->have_posts()) :
            $apiKeys = $query->get_posts();
            foreach ($apiKeys as $keys => $value) :
                $this->id = $value->ID;
                $apiKeys[$keys] = [
                    'action'    => $this->api_key_metas('action'),
                    'allowedAction' => $this->api_key_metas('allowed_action'),
                    'keyType'  => $this->api_key_metas('key_type'),
                    'userId'   => $this->api_key_metas('user_id'),
                    'uuid'      => $this->api_key_metas('uuid'),
                    'appId'    => $this->api_key_metas('app_id'),
                    'name'      => $this->api_key_metas('name'),
                    'hash'      => $this->api_key_metas('hash'),
                    'key'       => $this->api_key_metas('key'),
                    'created'   => $this->api_key_metas('created')
                ];
            endforeach;
            Res::success($apiKeys, "Api keys retrieved");
            return $apiKeys;
        else :
            return Res::error("No api key was found", "Api key not found", 404);
        endif;
    }

    public function api_key_metas($param)
    {
        return  get_post_meta($this->id, $param, true);
    }
}
