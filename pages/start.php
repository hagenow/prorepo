<?php if($error): ?>

    <div class="bs-example">
      <div class="alert alert-danger fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>Oh snap! You got an error!</h4>
        <p><?php echo($error); ?></p>
      </div>
    </div><!-- /example -->
<?php endif; ?>

<div class="jumbotron">
  <div class="container">
  <h1>Welcome...</h1>
  <h2>to our process model & log repository!</h2>
   <p>
        Irgend was sinnfreies...
    </p>
   <p><a class="btn btn-primary btn-lg" role="button" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=cat" ?>'">Browse repository...</a></p>
  </div>
</div>
