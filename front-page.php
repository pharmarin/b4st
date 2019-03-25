<?php
    get_header();
    b4st_main_before();
?>

<?php
    $post_types = [
      [
        "label" => "Pharmacie",
        "content" => function () {
          $posts = get_posts( 'numberposts=-1' );
          global $post;
          foreach($posts as $post) : ?>
              <?php
                setup_postdata($post);
                get_template_part('loops/index-post');
              ?>
          <?php endforeach;
        }
      ],
      [
        "label" => "Huiles essentielles",
        "content" => function () {
          set_query_var('cards', [
            [
              "title" => "Toutes les huiles essentielles",
              "link" => "aromatherapie/"
            ],
            [
              "title" => "Trier par propriété",
              "link" => "proprietes/"
            ],
            [
              "title" => "Trier par utilisation",
              "link" => "usages/"
            ],
            [
              "title" => "Trier par molécule principale",
              "link" => "principes_actifs/"
            ]
          ]);
          get_template_part('loops/frontpage-cards');
        }
      ],
      [
        "label" => "Plantes",
        "content" => function () {
          set_query_var('cards', [
            [
              "title" => "Toutes les plantes",
              "link" => "phytotherapie/"
            ],
            [
              "title" => "Trier par propriété",
              "link" => "proprietes/"
            ],
            [
              "title" => "Trier par utilisation",
              "link" => "usages/"
            ],
            [
              "title" => "Trier par molécule principale",
              "link" => "principes_actifs/"
            ]
          ]);
          get_template_part('loops/frontpage-cards');
        }
      ],
      [
        "label" => "Produits",
        "content" => function () {
          set_query_var('cards', [
            [
              "title" => "Tous les produits",
              "link" => "produits/"
            ],
            [
              "title" => "Trier par laboratoire",
              "link" => "laboratoire/"
            ]
          ]);
          get_template_part('loops/frontpage-cards');
        }
      ]
    ];
?>

<main id="main" class="container <?php echo isset($_GET['_app']) ? null : "mt-5" ?>">
  <div class="row">

    <div class="col-sm-12 d-flex justify-content-center mb-3">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <?php foreach ($post_types as $index => $post_type) : ?>
          <label class="btn btn-outline-primary <?php echo $index === 0 ? "active" : null ?>">
            <input type="radio" name="options" class="carousel-toggle" data-slide-to="<?php echo $index ?>" autocomplete="off" checked> <?php echo $post_type["label"] ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="col-sm-12 d-flex justify-content-center">
      <div class="carousel slide w-100">
        <div class="carousel-inner">
          <?php foreach ($post_types as $index => $post_type) : ?>
            <div class="carousel-item <?php echo $index === 0 ? "active" : null ?>">
              <div class="col-sm">
                <div id="content" role="main">
                  <div class="row">
                    <?php $post_type["content"](); ?>
                  </div>
                </div><!-- /#content -->
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

  </div><!-- /.row -->
</main><!-- /.container -->

<?php
    b4st_main_after();
    get_footer();
?>
