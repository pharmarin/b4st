<?php
    get_header();
    b4st_main_before();

    global $wp;
    $taxonomy_name = $wp->request;
		$taxonomy = get_taxonomy($taxonomy_name);
    $terms = get_terms( array(
      'taxonomy' => $taxonomy_name
    ) );
?>

<!-- taxonomy template -->
<main id="main taxonomy" class="container mt-5">
  <div class="row">

    <div class="col-sm">
      <div id="content" role="main">
        <header class="mb-4 border-bottom">
          <h1>
            <?php echo $taxonomy->labels->name; ?>
          </h1>
        </header>

        <?php if ($taxonomy->hierarchical) : ?>
          <div class="card-columns">
          <?php
            $hierarchy = _get_term_hierarchy($taxonomy_name);
            foreach($terms as $term) :
              if($term->parent) {
                continue;
              }
          ?>
                <div class="card mb-4">
                  <div class="card-header">
                    <h2 class="mb-0">
                      <?php echo $term->name ?>
                    </h2>
                  </div>
                  <ul class="list-group list-group-flush">
                    <?php
                      if($hierarchy[$term->term_id]) {
                        foreach($hierarchy[$term->term_id] as $child) :
                          $child = get_term($child, $taxonomy_name);
                          if ($child->count > 0):
                    ?>
                          <a href="<?php echo get_term_link($child) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <?php echo $child->name ?>
                            <span class="badge badge-light badge-pill"><?php echo $child->count ?></span>
                          </a>
                    <?php
                          endif;
                        endforeach;
                      }
                    ?>
                  </ul>
                </div>
          <?php
            endforeach;
          ?>
          </div>
        <?php else : ?>
          <?php foreach ($terms as $term) : ?>
            <?php
              $initial = mb_substr($term->slug, 0, 1);
              $initial = strtoupper($initial);
              if ($initial !== $current_letter) :
                $current_letter = $initial;
              ?>
                <h2 class="mt-3"><?php echo $current_letter ?></h2>
              <?php
              endif;
            ?>
            <a href="<?php echo get_term_link($term) ?>" class="mr-5"><?php echo $term->name ?></a>
          <?php endforeach; ?>
        <?php endif; //taxonomy ?>
      </div><!-- /#content -->
    </div>

    <?php get_sidebar(); ?>

  </div><!-- /.row -->
</main><!-- /.container -->

<?php
    b4st_main_after();
    get_footer();
?>
