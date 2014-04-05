<?php 
    if(isset($_GET['content']))
    {
        if($_GET['content'] == "frontpage")
        {
            $file = file_get_contents('content/frontpage.inc.php', true);
?>
<?php 
    if(isset($_POST['update_content'])) 
    {
        $file = file_put_contents('content/frontpage.inc.php', $_POST['html']);
        echo "Frontpage is now updated!";
    }
?>


<?php if(!isset($_POST['update_content'])) : ?> 
<strong>Frontpage</strong>
<hr>
<form class="form-horizontal" role="form" method="post" name"updatecontent" id="updatecontent" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=editcontent&content=frontpage">
  <div class="form-group">
    <label for="html" class="col-sm-2 control-label">Plain HTML</label>
    <div class="col-sm-10">
    <textarea id="html" name="html" class="form-control" rows="20"><?php echo htmlentities($file); ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="update_content" class="btn btn-default" value="content">Update content</button>
    </div>
  </div>
</form>
<?php endif; ?>


<?
        }
        elseif($_GET['content'] == "footer")
        {
            $file = file_get_contents('content/footer.inc.php', true);
?>
<?php 
    if(isset($_POST['update_content'])) 
    {
        $file = file_put_contents('content/footer.inc.php', $_POST['html']);
        echo "Footer is now updated!";
    }
?>


<?php if(!isset($_POST['update_content'])) : ?> 
<strong>Footer</strong>
<hr>
<form class="form-horizontal" role="form" method="post" name"updatecontent" id="updatecontent" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=editcontent&content=footer">
  <div class="form-group">
    <label for="html" class="col-sm-2 control-label">Plain HTML</label>
    <div class="col-sm-10">
    <textarea id="html" name="html" class="form-control" rows="20"><?php echo htmlentities($file); ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="update_content" class="btn btn-default" value="content">Update content</button>
    </div>
  </div>
</form>
<?php endif; ?>


<?
        }
        elseif($_GET['content'] == "contact")
        {
            $file = file_get_contents('content/contact.inc.php', true);
?>
<?php 
    if(isset($_POST['update_content'])) 
    {
        $file = file_put_contents('content/contact.inc.php', $_POST['html']);
        echo "Contact is now updated!";
    }
?>


<?php if(!isset($_POST['update_content'])) : ?> 
<strong>Contact</strong>
<hr>
<form class="form-horizontal" role="form" method="post" name"updatecontent" id="updatecontent" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=editcontent&content=contact">
  <div class="form-group">
    <label for="html" class="col-sm-2 control-label">Plain HTML</label>
    <div class="col-sm-10">
    <textarea id="html" name="html" class="form-control" rows="20"><?php echo htmlentities($file); ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="update_content" class="btn btn-default" value="content">Update content</button>
    </div>
  </div>
</form>
<?php endif; ?>


<?
        }
        else
        {
            header( 'location: index.php?show=404' );
        }
    }
    elseif(!isset($_GET['content']))
    {
        header( 'location: index.php?show=404' );
    }
    else
    {
        header( 'location: index.php?show=404' );
    }
?>
