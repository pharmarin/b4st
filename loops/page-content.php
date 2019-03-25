<?php
/**!
 * The Page Content Loop
 */
?>

<?php if(have_posts()): while(have_posts()): the_post(); ?>
<?php
  $the_loop = new PharmaLoop(get_the_content());
  echo $the_loop->get_article();
?>
<?php
  endwhile;
  else :
    get_template_part('loops/404');
  endif;
?>
