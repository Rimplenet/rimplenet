<?php
class RimplenetCreateMlmMatrix extends Utils
{

    /**
     * MLM CreateMatrix
     * @param array 
     * @return bool
     */
    public function createMlmMatrix(array $params = [])
    {
        # Required Fields
        if ($this->checkEmpty($params)) return;
        extract($params);

        # Verify width and depth are int
        if (!$this->isInt([
            'matrix_width' => (int) $matrix_width,
            'matrix_depth' => (int) $matrix_depth,
        ])) return;

        # check is mlm has already been created
        if (MLM::mlm_exists(ucwords($matrix_name))) return;

        # initiate MLM
        $mlm = $this->createMLM($params);
        return Res::success($mlm, 'you have created a new matrix plan');
    }

    public function createMLM(array $params)
    {
        extract($params);
        $post = wp_insert_post([
            'post_author'   => 1,
            'post_title'    => ucwords($matrix_name),
            'post_content'  => "",
            'post_status'   => 'publish',
            'post_type'     => self::POST_TYPE
        ]);

        wp_set_object_terms($post, MLM::MLM_MATRIX, self::TAXONOMY);

        $resp = [
            'matrix_id'  => $post,
            'matrix_name' => ucwords($matrix_name),
            'matrix_description' => $matrix_description,
            'matrix_width' => (int) $matrix_width,
            'matrix_depth' => (int) $matrix_depth,
        ];

        foreach ($resp as $key => $val) {
            update_post_meta($post, $key, $val);
        }

        return $resp;
    }
}
