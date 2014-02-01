<?php if(!isset($_GET['action']) || $_GET['action'] == "") : ?>
keine direkte angabe
<?php elseif(isset($_GET['action']) && $_GET['action'] == "view") : ?>
List all users.<br>
<?php require_once 'pages/admin/admin_user_view.php'; ?> 
<?php elseif(isset($_GET['action']) && $_GET['action'] == "edit") : ?>
Edit a user.
<?php require_once 'pages/admin/admin_user_edit.php'; ?>
<?php elseif(isset($_GET['action']) && $_GET['action'] == "delete") : ?>
Delete a user.
<?php require_once 'pages/admin/admin_user_delete.php'; ?>
<?php elseif(isset($_GET['action']) && $_GET['action'] == "unblock") : ?>
Unblock users.
<?php require_once 'pages/admin/admin_user_unblock.php'; ?>
<?php elseif(isset($_GET['action']) && $_GET['action'] == "validate") : ?>
Validate users.
<?php require_once 'pages/admin/admin_user_validate.php'; ?>
<?php endif; ?>
