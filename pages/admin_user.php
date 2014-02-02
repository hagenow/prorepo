<?php if(!isset($_GET['action']) || $_GET['action'] == "") : ?>
keine direkte angabe
<?php elseif(isset($_GET['action']) && $_GET['action'] == "view") : ?>
<?php require_once 'pages/admin/admin_user_view.php'; ?> 
<?php elseif(isset($_GET['action']) && $_GET['action'] == "edit") : ?>
Edit a user.
<?php require_once 'pages/admin/admin_user_edit.php'; ?>
<?php elseif(isset($_GET['action']) && $_GET['action'] == "delete") : ?>
Delete a user.
<?php require_once 'pages/admin/admin_user_delete.php'; ?>
<?php elseif(isset($_GET['action']) && $_GET['action'] == "unblock") : ?>
<?php require_once 'pages/admin/admin_user_unblock.php'; ?>
<?php elseif(isset($_GET['action']) && $_GET['action'] == "approve") : ?>
<?php require_once 'pages/admin/admin_user_approve.php'; ?>
<?php endif; ?>
