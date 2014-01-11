<?php if($error): ?>

    <div class="bs-example">
      <div class="alert alert-danger fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>Oh snap! You got an error!</h4>
        <p><?php echo($error); ?></p>
      </div>
    </div><!-- /example -->
<?php endif; ?>
