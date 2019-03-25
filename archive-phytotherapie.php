<?php
    get_header();
    b4st_main_before();
?>

<!-- archive-phyto -->
<main id="main archive-phytotherapie" class="container mt-5">
  <div class="row">
    <div class="col-sm">
      <div id="content" role="main">
        <?php
          $term = get_queried_object();
          $taxonomy = get_taxonomy($term->taxonomy);
          $taxonomy_title = $taxonomy->labels->singular_name;
          if ($title = single_cat_title($taxonomy_title . " : ", false)) :
        ?>
          <header class="mb-4 border-bottom">
            <h1>
              <?php echo $title ?>
            </h1>
          </header>
        <?php
          endif;
        ?>
        <div class="row">
          <?php get_template_part('loops/abstract-phytotherapie'); ?>
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
