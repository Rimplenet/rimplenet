<?php

class RimplenetDeleteAPIKey extends RimplenetGetApiKeys
{
    public function delete(string $key_id){
        $APIKey = $this->getKeyById($key_id);

        # check if APIKey is valid
        if(!$APIKey):
            return Res::error(['Operation cannot be completed', 'APIKey not found', 404]);
        else:
            // trash APIKey
            wp_delete_post($APIKey->post_id, true );
            return Res::success(['Operation completed'], $APIKey->name." APIKey deleted");
        endif;
        return true;
    }
}