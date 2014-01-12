<?php
if(!isset($_SESSION)){
        session_start();
}
// session_destroy();
// 
// $hostname = $_SERVER['HTTP_HOST'];
// $path = dirname($_SERVER['PHP_SELF']);
// 
// header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/');

?>
<div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">Error</h3>
        </div>
        <div class="panel-body">
            In case you want to see this page, you have to login or create an user account!
        </div>
      </div>
</div>
