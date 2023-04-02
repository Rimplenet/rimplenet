<?php

class RimplenetSearchUserApi
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/users/search',
            [
                'methods' => 'POST',
                'callback' => [$this, 'search_users']
            ]
        );
    }

    public function search_users(WP_REST_Request $request)
    {
        $user_id = sanitize_text_field($request->get_param('rimplenet_search_user'));

        $users = new WP_User_Query( array(
            'search'         => '*'.esc_attr( $user_id ).'*',
            'search_columns' => array(
                'user_login',
                'user_nicename',
                'user_email',
                'user_url',
            ),
        ),
    
        array(
            'meta_query' => array(
                'relation' => 'AND',
                    array(
                        'key'     => 'first_name',
                        'value'   => $user_id,
                         'compare' => 'LIKE' // any value that contains developer
                    ),
                    array(
                        'key'     => 'last_name',
                        'value'   => $user_id,
                         'compare' => 'LIKE'
                    ),
                    // array(
                    //     'key'     => 'experience',,
                    //     'value'   => '5',
                    //      'compare' => '>='
                    // )
            )) );
        $users_found = $users->get_results();
        // var_dump($users_found);
        
        return new WP_REST_Response($users_found);

    }
}

$RimplenetSearchUserApi = new RimplenetSearchUserApi();