<?php if(isset($_POST['submit_msg']) && isset($_POST['checkthisout']) && empty($_POST['foobar'])) : ?>
<pre>
<?php
echo print_r($_POST, TRUE);
?>
</pre>
<?php 
sendadminmail();
?>
<?php elseif(isset($_POST['submit_msg']) && !isset($_POST['checkthisout']) && !empty($_POST['foobar'])) : ?>
Uncool behavior!
<?php endif; ?>
<?php if(!isset($_POST['submit_msg'])) : ?>
<form class="form-horizontal" name="admincontact" id="admincontact" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=admincontact" enctype="multipart/form-data">
    <fieldset>
    <!-- Form Name -->
    <legend>Contact administrator</legend>
    
    <!-- Text input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="modelName">Contact details</label>
      <div class="col-sm-6">
    <?php if(isset($_SESSION['angemeldet'])) : ?>
        <input id="name" name="name" type="text" class="form-control" value="<?php echo $_SESSION['user']; ?>" disabled >
        <!-- hidden login field -->
        <input type="hidden" name="login" value="<?php echo $_SESSION['user']; ?>">
    <?php else : ?>
        <input id="login" name="login" type="text" class="form-control" placeholder="Your Name" required="" />
    <?php endif; ?>
      </div>
    </div>
        
        <!-- hidden groupID field -->
    <?php if(isset($_GET['groupID'])) : ?>
        <input type="hidden" name="groupid" value="<?php echo $_GET['groupID']; ?>">
    <?php endif; ?>


    <?php if(!isset($_SESSION['angemeldet'])) : ?>
    <div class="form-group">
        <label class="control-label col-sm-3" for="email">E-mail</label>
        <div class="col-sm-6">
            <input id="email" name="email" type="text" placeholder="user@domain.ext" class="form-control" required="">
        </div>
    </div>
    <?php endif; ?>
    
    <div class="form-group">
      <label class="control-label col-sm-3" for="reason">Reason</label>
      <div class="col-sm-6">
        <select name="reason" class="form-control">
            <?php if(isset($_SESSION['angemeldet'])) : ?>
                <option>Reopen closed group</option>
                <option>Delete a file/model/log</option>
                <option>Something else</option>
            <?php else : ?>
                <option>My username is blocked</option>
                <option>Something else</option>
            <?php endif; ?>
        </select>
      </div>
    </div>

    <!-- Textarea -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="message">Message</label>
      <div class="col-sm-8">
        <textarea id="message" name="message" class="form-control" rows="4" placeholder="Additional text..."></textarea>
      </div>
    </div>


    <!-- check here, that you are a user -->
    <div class="form-group">
      <label class="control-label col-sm-3"></label>
        <label class="checkbox-inline col-sm-6">
            <input type="checkbox" name="checkthisout" id="checkthisout" value="Click here if you are a really person." />
            Click here if you are a really person.
        </label>
    </div>
    
    <!-- Action submit or reset -->
    <div class="form-group">
      <label class="control-label col-sm-3"></label>
        <div class="col-sm-6">
            <!-- hidden field timestamp -->
            <input type="hidden" name="timestamp" value="<?php echo date("YmdHis"); ?>">
            <!-- hidden field for protection -->
            <input type="text" name="foobar" style="display: none;">
            <!-- Indicates a successful or positive action -->
            <button type="submit" class="btn btn-success" id="submit_msg" name="submit_msg" value="Submit">Send</button>
            <!-- Indicates a unsuccesful or negative action -->
            <button type="reset" class="btn btn-danger" id="reset_msg" name="reset_msg" value="Reset">Reset</button>
        </div>
    </div>
    
    </fieldset>
</form> 
<?php endif; ?>
