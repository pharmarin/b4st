<?php
/*
The Single Post
===============
*/
?>

<?php if(have_posts()): while(have_posts()): the_post();

    //var_dump(get_the_content());
    $class_name = "PharmaLoop_" . get_post_type();
    if (class_exists($class_name)) {
      $the_loop = new $class_name(get_the_content());
    } else {
      $the_loop = new PharmaLoop(get_the_content());
    }
    echo $the_loop->get_article();

    if ( comments_open() || get_comments_number() ) :
      //comments_template();
      comments_template('/loops/single-post-comments.php');
		endif;
  endwhile; else :
    get_template_part('loops/404');
  endif;
?>



<div class="row mt-5 border-top pt-3">
  <div class="col">
    <?php previous_post_link('%link', '<i class="fas fa-fw fa-arrow-left"></i> ' . translate('Previous post: ', 'b4st').'%title'); ?>
  </div>
  <div class="col text-right">
    <?php next_post_link('%link', translate('Next post: ', 'b4st') .'%title' . ' <i class="fas fa-fw fa-arrow-right"></i>'); ?>
  </div>
</div>
