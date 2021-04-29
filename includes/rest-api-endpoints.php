<?php

  // Register custom wordpress REST API endpoints

 
  function create_endpoints() {

    // Pull necessary info from config file
    $input = file_get_contents("wp-content\plugins\stilts-rest-api\config.json");
    $config = json_decode($input);
    $endpoints = $config->endpoints;



    // Generate REST routes
    foreach((array) $endpoints as $endpoint){

      register_rest_route($endpoint->rest_route_name, 'all', [
        'methods' => 'GET',
        'callback' => $endpoint->callback_fn,
        'args' => array(
          'postType' => [$endpoint->wp_post_type_slug],
          'per_page' => $endpoint->per_page,
          'queryArgs' => (array)$endpoint->query_args,
          'template' => $endpoint->result_template
        ),
        'permission_callback' => '__return_true'
      ]);

    }

  }


  add_action('rest_api_init','create_endpoints');

?>