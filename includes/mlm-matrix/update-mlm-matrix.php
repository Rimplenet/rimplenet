<?php

class RimplenetUpdateMlmMatrix extends Utils
{

    public function updateMlmMatrix(array $params = [])
    {
        extract($params);
        if ($this->checkEmpty(['matrix_id' => $matrix_id])) return;
        if (!$this->isInt(['matrix_id' => (int) $matrix_id])) return;

        if (!MLM::mlm_by_id($matrix_id)) return Res::error(['not_found' => "MLM Matrix couldn't be reached"], "MLM Matrix not found", 404);
        foreach ($params as $key => $value):
            if($key == 'matrix_id') continue;
            if($value == '') continue;
            update_post_meta($matrix_id, $key, $value);
        endforeach;
        return Res::success($params, 'MLM Matrix Updated'); 
    }
}
