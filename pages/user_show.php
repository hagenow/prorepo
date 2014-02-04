<?php 
$error = "No username given!";
if(isset($_GET['name']))
{
        $name = cleaninput($_GET['name']);
        $userdata = showuserdata($name);
        $email = $userdata['email'];
?>
<form class="form-horizontal" role="form">
<legend>User details</legend>
    <div class="form-group">
        <label class="control-label col-sm-3" for="name">Name</label>
        <div class="col-sm-6">
        <p class="form-control-static"><?php echo $userdata['firstname']." ".$userdata['lastname']; ?></p>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="name">Affiliation</label>
        <div class="col-sm-6">
        <p class="form-control-static"><?php echo $userdata['affiliation']; ?></p>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="name">E-Mail</label>
        <div class="col-sm-6">
        <p class="form-control-static"><?php mailtogfx($email); ?></p>
        </div>
    </div>
</form>

<?php
}
else
{
    echo $error;
}
?>
