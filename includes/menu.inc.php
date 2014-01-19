    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">ProRepo</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
	    <li class="active"><a href="#">Home</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Repository<b class="caret"></b></a>
              <ul class="dropdown-menu">
              <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=cat">Browse</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Upload new files</li>
                <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=modnew">New model</a></li>
                <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=lognew">New log</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Batch upload</li>
                <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=modbatch">New models</a></li>
                <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=logbatch">New logs</a></li>
              </ul>
            </li>
            <li><a href="#contact">Contact</a></li>
	</ul>
          </ul>
        <?php require_once 'includes/login.inc.php'; ?>
        </div><!--/.nav-collapse -->
      </div>
    </div>

