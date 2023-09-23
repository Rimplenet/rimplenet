<?php

class RimplenetDeleteMlmMatrix extends Utils
{

    public function deleteMlmMatrix($id)
    {
        if (!MLM::mlm_by_id($id)) return Res::error(['not_found' => "MLM Matrix couldn't be reached"], "MLM Matrix not found", 404);
        wp_delete_post($id);
        return Res::success(["Operation Executed"], 'MLM Matrix Deleted'); 
    }
}
