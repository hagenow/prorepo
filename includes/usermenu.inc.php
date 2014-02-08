<?php if(isset($_SESSION['user'])) : ?>
<ul class="nav nav-pills nav-stacked">
    <?php if(isset($_SESSION['groupID'])) : ?>
        <li class="active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=groupcurrent">Group <? echo $_SESSION['groupName']; ?></a></li>
        <hr>
    <?php endif; ?>
  <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=mymodels">My models</a></li>
  <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=mylogs">My logs</a></li>
  <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=mygroups">My groups</a></li>
</ul>
<?php endif; ?>
