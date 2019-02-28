<?php
/*
The Abstract Aromatherapie
===============
*/
?>

<div class="row">
  <?php if(have_posts()): while(have_posts()): the_post(); ?>
    <div class="col-md-4">
      <article role="article" id="post_<?php the_ID()?>" <?php post_class()?>>
        <div class="card mb-4">
          <header class="card-header">
            <?php echo get_the_post_thumbnail(get_the_ID(), [68, 68], ['class' => 'border rounded float-right']) ?>
            <h5 class="mb-0">
              <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
            </h5>
            <h6 class="text-muted font-italic mb-0">
              <?php $loop = new PharmaLoop_aromatherapie(get_the_content());
              echo $loop->get_the_subtitle(); ?>
            </h6>
          </header>
          <div class="card-body">
            <main>
              <?php
                the_content();
              ?>
            </main>
          </div>
        </div>

      </article>
    </div>
  <?php
    endwhile; else :
      get_template_part('loops/404');
    endif;
  ?>
</div>
