<?php

use Credits\Credits;
use Res\Res;

Class RimplenetDeleteCredits extends Credits
{
    public function deleteCredits(int $id, string $type = ''){

        if($credits = $this->creditsExists($id, $type)):
            wp_delete_post($credits->post_id);
            return Res::success(["Transaction $credits->post_id Deleted"], "Credits Action Completed");
        else:
            return Res::success(["Operation cannot be completed"], "Credit not Found", 404);
        endif;
        return false;
        
    }
}