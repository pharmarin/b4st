<div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
  <div class="row">
    <?php foreach ($cards as $card) : ?>
      <div class="col-sm-6 mb-4">
        <a href="<?php echo $card["link"] ?>">
          <div class="card card-squarred" style="min-height: 10em;">
            <div class="card-img-overlay d-flex justify-content-center align-items-center">
              <h5 class="card-title text-center"><?php echo $card["title"] ?></h5>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
