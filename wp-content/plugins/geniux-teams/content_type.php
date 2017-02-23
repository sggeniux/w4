<?php 



/* NOUVEAU TYPE DE CONTENU */

function create_teams() {

    $labels = array(
        'name' => __( 'Team' ),
        'singular_name' => __( 'Teams' ),
        'add_new' => 'Add New Team',
        'add_new_item' => 'Add New Team',
        'edit_item' => 'Edit Team',
        'new_item' => 'New Team',
        'all_items' => 'All Teams',
        'view_item' => 'View Team',
        'search_items' => 'Search Team',
        'not_found' =>  'No Teams Found',
        'not_found_in_trash' => 'No Teams found in Trash', 
        'parent_item_colon' => '',
        'menu_name' => 'Teams',
    );

    $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'teams'),
            'menu_position'       => 8,
            'menu_icon'           => 'dashicons-groups',
            'taxonomies'          => array( 'teams' )
        );

    register_post_type( 'teams', $args );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_teams' );


function add_teams_meta_boxes() {
    //add_meta_box("teams_contact_meta", "Teams &uArr;", "add_shortcode_teams_meta_box", "teams", "normal", "low");
}

function add_shortcode_teams_meta_box()
{
    /*
    global $post;
    $custom = get_post_custom( $post->ID );
    ?>
    <p>
        <h2>Shortcode pour ce type de notification:</h2>
        <input type="text" name="shortcode" value="<?= @$custom["shortcode"][0] ?>">
    </p>
    <?php
    */
}

function save_teams_custom_fields(){
  global $post;
 
  if ( $post )
  {
    update_post_meta($post->ID, "shortcode", @$_POST["shortcode"]);
  }
}

add_action( 'admin_init', 'add_teams_meta_boxes' );
add_action( 'save_post', 'save_teams_custom_fields' );