<?php require 'includes/authcheck.inc.php'; ?>
<div class="panel panel-info">
  <div class="panel-heading">
  <h3 class="panel-title">My uploaded logs</h3>
  </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Modelname</th>
                <th class="text-center">Creation date</th>
            </tr>
        </thead>
        <tbody>
            <?php getuseruploads("log"); ?>
        </tbody>
    </table>
</div>
