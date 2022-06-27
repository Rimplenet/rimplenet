<?php

use ApiKey\ApiKey;

class RimplenetGetApiKeys extends ApiKey
{

    public function getKeys($hash)
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
                    'uuid'      => $this->postMeta('uuid'),
                    'app_id'    => $this->postMeta('app_id'),
                    'name'      => $this->postMeta('name'),
                    'hash'      => $this->postMeta('hash'),
                    'key'       => $this->postMeta('key'),
                    'password'  => $this->postMeta('password'),
                    'created'   => $this->postMeta('created')
                ];
            endforeach;
                $this->success($apiKeys, "Api keys retrieved");
            return $apiKeys;
        else:
            return $this->error("No api key was found", "Api key not found", 404);
        endif;
    }

    // public function api_key_metas($param)
    // {
    //     return 
    // }
}
