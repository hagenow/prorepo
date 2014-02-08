<?php require 'includes/authcheck.inc.php'; ?>
<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">My uploaded models</h3>
  </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Modelname</th>
                <th class="text-center">Creation date</th>
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php getuseruploads("model"); ?>
        </tbody>
    </table>
</div>

