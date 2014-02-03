
<form class="form-horizontal" name="modelupload" id="modelupload" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=search">
  <fieldset>
    
    <!-- Form Name -->
    <legend>Search ProRepo</legend>
    
    <!-- Text input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="keyword">Keyword</label>
      <div class="col-sm-6">
        <input id="keyword" name="keyword" type="text" placeholder="" class="form-control" required="">
        
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-3" for="modelName">Select search type</label>
      <div class="col-sm-6">
        <select class="form-control" name="type">
          <option value="model">Model</option>
          <option value="log">Log</option>
          <option value="group">Group</option>
          <option value="user">User</option>
        </select> 
      </div>
    </div>
    
    <!-- hidden field for marking up as model -->
    <input type="hidden" name="timestamp" value="<?php echo date("YmdHis"); ?>">
    
    <!-- Action submit or reset -->
    <div class="form-group">
      <label class="control-label col-sm-3"></label>
        <div class="col-sm-6">
            <!-- Indicates a successful or positive action -->
            <button type="submit" class="btn btn-success" id="search" name="search" value="search">Search</button>
        </div>
    </div>
    
  </fieldset>
 </form>
<?php if(isset($_POST['search'])) : ?>
  <legend>Result(s)</legend>
  <?php if($_POST['type'] == "model") : ?>
    <div class="panel panel-success">
        <!-- List group -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Modelname</th>
                    <th class="text-center">Creation date</th>
                    <th class="text-center">Creator</th>
                </tr>
            </thead>
            <tbody>
                <?php searchmodel($_POST['keyword']); ?>
            </tbody>
        </table>
    </div>
    <?php elseif($_POST['type'] == "log") : ?>
    <div class="panel panel-success">
        <!-- List group -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Logname</th>
                    <th class="text-center">Creation date</th>
                    <th class="text-center">Creator</th>
                </tr>
            </thead>
            <tbody>
                <?php searchlog($_POST['keyword']); ?>
            </tbody>
        </table>
    </div>
    <?php elseif($_POST['type'] == "group") : ?>
    <div class="panel panel-success">
        <!-- List group -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Groupname</th>
                    <th class="text-center">Creation date</th>
                    <th class="text-center">Tags</th>
                    <th class="text-center">Creator</th>
                </tr>
            </thead>
            <tbody>
                <?php searchgroup($_POST['keyword']); ?>
            </tbody>
        </table>
    </div>
    <?php elseif($_POST['type'] == "user") : ?>
    <div class="panel panel-success">
        <!-- List group -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th class="text-center">First- & Lastname</th>
                    <th class="text-center">eMail</th>
                    <th class="text-center">Affiliation</th>
                </tr>
            </thead>
            <tbody>
                <?php searchuser($_POST['keyword']); ?>
            </tbody>
        </table>
    </div>
  <?php endif; ?>
<?php endif; ?>
