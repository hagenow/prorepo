<?php if($error): ?>
    <div class="bs-example">
      <div class="alert alert-danger fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>You entered a wrong username or password!</h4>
        <p><?php echo($error); ?></p>
      </div>
    </div>
<?php endif; ?>
<?php require_once 'content/frontpage.inc.php'; ?>
