# WordPress Custom Filter/Sort Plugin

Background: For an existing WordPress site with hundreds of pre-existing posts stored across numerous custom post types and with numerous custom meta fields & taxonomies, I built out functionality to allow site visitors to dynamically search & filter results across multiple parameters. I accomplished this by building out a series of custom REST API endpoints on the WordPress backend, and then implementing an AJAX-based filter on the front-end. Solution was built to scale, with the ability to handle multiple different post types, meta fields, as well as account for future client additions.

*This repo* is a work-in-progress of splitting out the above functionality into its own standalone plugin.

Quick basic overview:
  - [config.json] config.json acts as single-source-of-truth, containing all desired filters & configurations
  - [rest-api-endpoints.php] Plugin generates a custom REST API endpoint for each endpoint specified in config.json
  - [theme files, not included] Need to manually create <form> component on the front end, with certain dataset attributes.
  - [filter.js] Plugin javascript triggers event on <form> change. Dynamically generates a request URL based on dataset attributes of <form>, then sends a fetch() request
  - [rest-api-callbacks.php] If everything goes right, the request URL should match one of our plugin-generated REST endpoints. Callback function then runs a WP_QUERY() based on the parameters passed in via the fetch() request.
  - Results are then returned via the wordpress template file specified in config.json, and dynamically populated on the page without triggering a page reload.
  - Also includes support for "Load More Results" pagination button