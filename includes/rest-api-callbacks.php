<?php


function STILTS_FILTER_STANDARD($request){

  // Access arguments associated with REST Route
  // Defined in rest-api-endpoints.php
  $attributes = $request->get_attributes();
  
  // Extract needed data
  $args = $attributes['args'];
  $postType = $args['postType'][0];
  $queryArgs = $args['queryArgs'];
  $allFilterParameters = $queryArgs['filter_parameters'];
  $outputTemplate = $args['template'];

  // return $outputTemplate;
  
  // Pagination
  $per_page = $args['per_page'];
  $page_num = intval($request->get_param('page'));
  $offset = $per_page * $page_num;

  $response = '';



  // Generate WP Query arguments
  $taxQuery = array('relation' => 'AND');
  
  foreach($allFilterParameters as $filterParameter){

    // Check URL parameter to see if it's one we filter based on
    $param = $request->get_param($filterParameter->rest_parameter);

    if($param){
      // For each query term, add to $taxQuery array
        array_push($taxQuery, 
          array(
            'taxonomy' => $filterParameter->wp_filter_field_slug,
            'field' => 'slug',
            'terms' => $param
          )
        );
    } 

  }


  $WP_QUERY_order = 'ASC'; //default
  $WP_QUERY_orderby = 'date'; //default

  // Run WP Query based on above data
  global $post;
	$filteredPosts = get_posts(
    array(
		  'post_type' => $postType,
      'posts_per_page' => $per_page,
      'offset' => $offset,
      'tax_query' => $taxQuery,
      // 'meta_query' => $metaQuery, //includes searching ACF meta fields
      // 's' => $search,
			'order' => $WP_QUERY_order		
    )
  );


  // For each returned CPT post, output the HTML template
	foreach($filteredPosts as $post){
		ob_start();
    get_template_part( 'template-parts/' . $outputTemplate );
		$response .= ob_get_clean();
  }

  wp_reset_query();

	return $response;
}