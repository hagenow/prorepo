<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';
require 'includes/authcheck.inc.php';

?>


<?php if(!(isset($_GET['action'])) && isadmin()) : ?>
<div class="panel panel-default">
<!-- Default panel contents -->
<div class="panel-body">
  <p>Rename or delete categories.<p>
</div>

<!-- List group -->
<table class="table table-striped">
    <thead>
        <tr>
            <th>Name of Category</th>
            <th class="text-center">Rename</th>
            <th class="text-center">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php adm_getcategories(); ?>
    </tbody>
</table>
</div>
<?php endif; ?>



<?php if(isset($_GET['action']) && $_GET['action'] == "rename") : ?>
<?php if(!isset($_POST['submit_newcatname']) || !$_POST['submit_newcatname']) : ?>
<form class="form-horizontal" name="CreateCategory" id="CreateCategory" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=catedit&catID=<?php echo $_GET['catID']; ?>&action=rename">
    <fieldset>
    <div class="form-group">
        <label class="control-label col-sm-3" for="catname">Name of Category</label>
        <div class="col-sm-8">
        <input id="catname" name="catname" type="text" value="<?php echo getcatname($_GET['catID']); ?>" class="form-control" required="">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3"></label>
        <div class="col-sm-8">
            <button type="submit" id="submit_newcatname" name="submit_newcatname" value="submit" class="btn btn-success">Rename category</button>
            <button type="reset" id="reset" name="reset" value="reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
    </fieldset>
</form>
<?php endif; ?>
<?php 
if(isset($_POST['submit_newcatname']) && $_POST['submit_newcatname'])
{
    if(DEBUG) echo "<pre>" .print_r( $_POST, true ). "</pre>";

    /** write function that returns user-id or read user-id from session */

    if (isset( $_POST['catname'] ))
    {
        $catname = $_POST['catname'];
        $catname = cleancatname();
        $catid = $_GET['catID'];
        renamecat($catid,$catname);
        echo "Category renamed!";
    }
    else
    {
        echo "Es wurde kein Kategoriename festgelegt!";
    }
}
?>
<?php endif; ?>

<?php if(isset($_GET['action']) && $_GET['action'] == "delete") : ?>
<?php if(!isset($_POST['delete_cat']) || !$_POST['delete_cat']) : ?>
<form class="form-horizontal" name="CreateCategory" id="CreateCategory" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=catedit&catID=<?php echo $_GET['catID']; ?>&action=delete&delete=confirm">
    <fieldset>
    <div class="form-group">
        <label class="control-label col-sm-3" for="catname">Delete category</label>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3"></label>
        <div class="col-sm-8">
            <button type="submit" id="delete_cat" name="delete_cat" value="submit" class="btn btn-success">Delete category</button>
            <button type="reset" id="reset" name="reset" value="reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
    </fieldset>
</form>
<?php endif; ?>
<?php 
if(isset($_POST['delete_cat']) && $_POST['delete_cat'])
{
    if(DEBUG) echo "<pre>" .print_r( $_POST, true ). "</pre>";

    /** write function that returns user-id or read user-id from session */

    if (isset( $_GET['delete'] ) && $_GET['delete'] == "confirm" )
    {
        $catid = $_GET['catID'];
        deletecat($catid);
        echo "Category deleted!";
    }
}
?>
<?php endif; ?>
