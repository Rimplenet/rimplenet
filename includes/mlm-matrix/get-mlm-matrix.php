<?php

class RimplenetGetMlmMatrix extends Utils
{
    public function getMlmMatrix()
    {
       $wpQuery = new WP_Query(
            array(
                'post_type' => self::POST_TYPE,
                'post_status' => 'any',
                'author' => 'any',
                'posts_per_page' => -1,
                'paged' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => self::TAXONOMY,
                        'field'    => 'name',
                        'terms'    =>  MLM::MLM_MATRIX
                    ),
                ),
            )
        );

        if($wpQuery->have_posts()):
            $posts = $wpQuery->get_posts();
            foreach($posts as $post => $value):
                $this->id = $value->ID;
                $posts[$post] = [
                    'matrix_id' => $this->postMeta('matrix_id'),
                    'matrix_name' => $this->postMeta('matrix_name'),
                    'matrix_description' => $this->postMeta('matrix_description'),
                    'matrix_width' => $this->postMeta('matrix_width'),
                    'matrix_depth' => $this->postMeta('matrix_depth'),
                ];
            endforeach;
            return Res::success($posts, "MLM Matrix");
        else:
            return Res::error(['not_found' => "OOPS... Looks like there's nothing here"], 'Not Found', 404);
        endif;
    }
}