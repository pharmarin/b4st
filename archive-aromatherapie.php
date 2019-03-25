<?php
    get_header();
    b4st_main_before();
?>

<!-- archive-aroma -->
<main id="archive-aromatherapie" class="container mt-5">
</main><!-- /.container -->

<?php
  $data = [];
  if (have_posts()) : while (have_posts()) : the_post();
    $data[] = [
      "id" => get_the_ID(),
      "title" => [
        "rendered" => get_the_title()
      ],
    ];
  endwhile; endif;
?>

<script>
  var postsData = <?php echo json_encode($data) ?>
</script>

<?php
    b4st_main_after();
    get_footer();
?>
