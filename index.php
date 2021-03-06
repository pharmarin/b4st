<?php
    get_header();
    b4st_main_before();
?>

<main id="main" class="container <?php echo isset($_GET['_app']) ? null : "mt-5" ?>">
  <div class="row">

    <div class="col-sm">
      <div id="content" role="main">
        <div class="row">
          <?php get_template_part('loops/index-loop'); ?>
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
