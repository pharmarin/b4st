<?php
/*
The Index Post (or excerpt)
===========================
Used by index.php, category.php and author.php
*/
?>

<article role="article" id="post_<?php the_ID()?>" <?php post_class("mb-5 col-sm-4"); ?> >
  <div class="card">
    <?php the_post_thumbnail('post-thumbnail', ['class' => 'card-img-top', 'title' => 'Feature image']); ?>
    <div class="card-body">
      <h5 class="card-title <?php echo has_excerpt( $post->ID ) ? null : "mb-0" ?>">
        <a href="<?php the_permalink(); ?>">
          <?php the_title()?>
        </a>
      </h5>

      <?php if ( has_excerpt( $post->ID ) ) {
        the_excerpt();
      } ?>
    </div>
    <div class="card-footer text-muted">
      <small>
        <p class="mb-0">
          <i class="far fa-calendar-alt"></i>&nbsp;<?php
            $post_date = get_the_date();
            $post_date_modified = get_the_modified_date();
            echo $post_date;
            if ($post_date != $post_date_modified) {
              echo " (" . translate('mise à jour le') . " " . $post_date_modified .")";
            }
           ?>
       </p>
       <p class="mb-0">
         <i class="far fa-user"></i>&nbsp; <?php _e('By ', 'b4st'); the_author_posts_link(); ?>
       </p>
       <p class="mb-0">
         <i class="far fa-comment"></i>&nbsp;<a href="<?php comments_link(); ?>"><?php comments_number( translate('Pas de commentaire', 'b4st'), translate('Un commentaire', 'b4st'), translate('% commentaires', 'b4st') ) ?></a>
       </p>
     </small>
    </div>
  </div>
</article>
