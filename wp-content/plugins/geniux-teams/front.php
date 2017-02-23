<?php

class WPSubmitFromFront {

      protected $pluginPath;  
      protected $pluginUrl;  

      public function __construct() {  

        // Set Plugin Path  
        $this->pluginPath = dirname(__FILE__);  
        // Set Plugin URL  
        $this->pluginUrl = WP_PLUGIN_URL . '/submitfromfront';  

        add_shortcode('POST_FROM_FRONT', array($this, 'handleFrontEndForm'));
      }

    public function handleFrontEndForm() {
      //Check if the user has permission to publish the post.
      if ( !current_user_can('publish_posts') ) {
        echo "<h2>Please Login to post links.</h2>";
        return;
      }


      if($this->isFormSubmitted() && $this->isNonceSet()) {

        if($this->isFormValid()) {
          $this->createPost();
        } else {
          $this->displayForm();
        }
      } else {
        $this->displayForm();
      }

    }

  //This function displays the HTML form.
  public function displayForm() {
      ?>
          <div id ="frontpostform">
            <form action="" id="formpost" method="POST" enctype="multipart/form-data">

              <fieldset>
                  <label for="postTitle"><?php _e("Nom de l'équipe") ?></label>
                  <input type="text" name="postTitle" id="postTitle" />
              </fieldset>
           
              <fieldset>
                  <label for="postContent"><?php _e("Description de l'équipe") ?></label>
                  <textarea name="postContent" id="postContent" rows="10" cols="35" ></textarea>
              </fieldset>

              <fieldset>
                <label><?php _e("Photo") ?></label>
                <input type="file" id="image" name="image" accept='image/*' />
              </fieldset>

              <fieldset>
                <label><?php _e("Catégorie") ?></label>
                <select><option><?php _e("Choisissez une catégorie") ?></option></select>
              </fieldset>

              <fieldset>
                  <label for="goals"><?php _e("Goals") ?></label>
                  <textarea name="goals" id="goals" rows="10" cols="35" ></textarea>
              </fieldset>

              <fieldset>
                  <a href="#TB_inline?width=600&height=550&inlineId=proj_box" class="create thickbox"><?php _e("Projects") ?></a>
                  <!--<input id="proj" type="text" name="projects" />-->
              <div id="proj_box" class="hide">
                <ul><?php echo $this->proj_box() ?></ul>
              </div>
              </fieldset>

              <fieldset>
                  <a href="#TB_inline?width=600&height=550&inlineId=members_box" class="create thickbox"><?php _e("Members") ?></a>
                  <!--<textarea name="members" id="members" ></textarea>-->
              <div id="members_box" class="hide">
                <ul><?php echo $this->members_box() ?></ul>
              </div>
              </fieldset>
           
              <fieldset>
                  <button type="submit" name="submitForm" ><?php _e('Créer') ?></button>
              </fieldset>
              <?php add_thickbox(); ?>
              <?php wp_nonce_field( 'front_end_new_post' , 'nonce_field_for_front_end_new_post'); ?>
            </form>
          </div>
        <?php
    }



public function proj_box(){
    global $wpdb;
    $user = wp_get_current_user();
    $user_posts = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_author`='.$user->ID.' AND `post_type`="ignition_product" AND `post_status` = "publish" ');
      $box = '';
      foreach($user_posts as $post):
        $box .= '<li> <label> <input type="checkbox" name="projects[]" value="'.$post->ID.'" /> '.$post->post_title.' </label></li>';
      endforeach;
    return $box;
}


public function members_box(){
    global $wpdb;    
    $user = wp_get_current_user();
    $users = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'users WHERE `user_status`=0 ');    
      $box = '';
      foreach($users as $us):
        $box .= '<li> <label> <input name="members[]" type="checkbox" value="'.$us->ID.'" /> '.$us->user_login.' </label></li>';
      endforeach;
    return $box;
}





public function isFormSubmitted() {

  if( isset( $_POST['submitForm'] ) ) return true;
  else return false;
}

public function isNonceSet() {
  if( isset( $_POST['nonce_field_for_front_end_new_post'] )  &&
    wp_verify_nonce( $_POST['nonce_field_for_front_end_new_post'], 'front_end_new_post' ) ) return true;
  else return false;
}

public function isFormValid() {
    //Check all mandatory fields are present.
    if ( trim( $_POST['postTitle'] ) === '' ) {
      $error = 'Please enter a title.';
      $hasError = true;
    } else if ( trim( $_POST['postContent'] ) === '' ) {
      $error = 'Please enter the content.';
      $hasError = true;
    } 

    //Check if any error was detected in validation.
    if($hasError == true) {
      echo $error;
      return false;
    }
    return true;
}

public function createPost() {


  //Get the ID of currently logged in user to set as post author
  $current_user = wp_get_current_user();
  $currentuserid = $current_user->ID;

  //Get the details from the form which was posted
  $postTitle = $_POST['postTitle'];
  $contentOfPost = $_POST['postContent'] ;
  $getImageFile = $_POST["image"];
  $postSatus = 'publish'; // 'pending' - in case you want to manually approve all posts;


  $goals = $_POST["goals"];

  $projects = json_encode($_POST["projects"]);
  $members = json_encode($_POST["members"]);


  //Create the post in WordPress
  $post_id = wp_insert_post( array(
    'post_type'         => 'teams',
    'post_title'        => $postTitle,
    'post_content'      => $contentOfPost,
    'post_status'       => $postSatus , 
    'post_author'       => $currentuserid
      
  ));

//      'goals' => $goals,  'projects'=>$projects,

  update_post_meta( $post_id,'_wp_page_template' , 'team' );

  update_post_meta( $post_id,'goals', $goals);
  update_post_meta( $post_id,'projects' , $projects);
  update_post_meta( $post_id,'members' , $members);

  $this->uploadTeamImage($_FILES,$post_id);
  
}


public function changeTeamDesc($postid){
      //if($this->isFormValid()) {
        $my_post = array(
            'ID'           => $postid,
            'post_title'   => $_POST["postTitle"],
            'post_content' => $_POST["postContent"],
        );
        wp_update_post($my_post);
      //}
}


  public function uploadTeamImage($files,$idpost){
      /* UPLOAD D'IMAGES */

      $upload = wp_upload_bits( $files['image']['name'], null, file_get_contents( $files['image']['tmp_name'] ) );
      $wp_filetype = wp_check_filetype( basename( $upload['file'] ), null );
      $wp_upload_dir = wp_upload_dir();
      $attachment = array(
          'guid' => $wp_upload_dir['baseurl'] . _wp_relative_upload_path( $upload['file'] ),
          'post_mime_type' => $wp_filetype['type'],
          'post_title' => preg_replace('/\.[^.]+$/', '', basename( $upload['file'] )),
          'post_content' => '',
          'post_status' => 'inherit'
      );
      
      $attach_id = wp_insert_attachment( $attachment, $upload['file'], $idpost );

      require_once(ABSPATH . 'wp-admin/includes/image.php');

      $attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
      wp_update_attachment_metadata( $attach_id, $attach_data );

      if( update_post_meta( $idpost, '_thumbnail_id', $attach_id ) ){
        return true;
      }

      /* * * * * * * * * * * * * */
  }


}

