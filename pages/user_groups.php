<?php require 'includes/authcheck.inc.php'; ?>
<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">My created groups</h3>
  </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Groupname</th>
                <th class="text-center">Status</th>
                <th class="text-center">Creation date</th>
            </tr>
        </thead>
        <tbody>
            <?php getuseruploads("group"); ?>
        </tbody>
    </table>
</div>

