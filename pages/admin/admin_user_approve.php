<?php if(isset($_POST['approve'])) : ?>
<?php
echo "<pre>".print_r($_POST, TRUE)."</pre>";
    approveusers();
?>
<?php endif; ?>

<?php if(!isset($_POST['approve'])) : ?>
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <h3 class="panel-title"><h3>Approve users</h3></h3>
    </div>

    <!-- List group -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?show=adminuser&action=approve" method="post" name="approveusers" id="approveusers">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center"><input type="button" id="toggleall" class="btn btn-default btn-xs" value="Toggle all"></th>
                <th class="text-center">Login</th>
                <th class="text-center">First- & lastname</th>
                <th class="text-center">eMail</th>
                <th class="text-center">Affiliation</th>
            </tr>
        </thead>
        <tbody>
            <?php getunapprovedusers(); ?>
        </tbody>
    </table>
    </form>
</div>
<?php endif; ?>
