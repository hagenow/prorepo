<?php if(!isset($_POST['submit_cat']) || !$_POST['submit_cat']) : ?>
<form class="form-horizontal" name="CreateCategory" id="CreateCategory" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=catnew">
    <fieldset>
    <div class="form-group">
        <label class="control-label col-sm-3" for="catname">Name of Category</label>
        <div class="col-sm-8">
            <input id="login" name="catname" type="text" placeholder="Categoryname" class="form-control" required="">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3"></label>
        <div class="col-sm-8">
            <button type="submit" id="submit_cat" name="submit_cat" value="submit" class="btn btn-success">Create category</button>
            <button type="reset" id="reset" name="reset" value="reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
    </fieldset>
</form>
<?php endif; ?>
<?php 
if(isset($_POST['submit_cat']) && $_POST['submit_cat'])
{
    echo "<pre>" .print_r( $_POST, true ). "</pre>";

    /** write function that returns user-id or read user-id from session */

    if (isset( $_POST['catname'] ))
    {
        $catname = cleancatname();
        createcat($catname);
    }
    else
    {
        echo "Es wurde kein Kategoriename festgelegt!";
    }
}
?>
