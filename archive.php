<?php
    get_header();
    b4st_main_before();
?>

<!-- archive -->
<main id="main" class="container mt-5">
  <div class="row">

    <div class="col-sm">
      <div id="content" role="main">
        <header class="mb-4 border-bottom">
          <h1>
            <?php echo get_taxonomy(get_query_var( 'taxonomy' ))->label ?> :
            <?php
              $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
              $parent = get_term_by( 'id', $term->parent, get_query_var( 'taxonomy' ) );
              if($parent):
                  echo '<a href="' . get_term_link($parent->name, get_query_var( 'taxonomy' )) . '">' . $parent->name . '</a> > ';
              endif;
            ?>
            <?php echo single_term_title(); ?>
          </h1>
        </header>
        <div class="row">
          <?php if(have_posts()): while(have_posts()): the_post();?>
            <?php get_template_part('loops/index-post'); ?>
          <?php endwhile; endif; ?>
        </div>
      </div><!-- /#content -->
    </div>

    <?php get_sidebar(); ?>

  </div><!-- /.row -->
</main><!-- /.container -->

<?php
    b4st_main_after();
    get_footer();
?>
