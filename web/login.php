<?php
// TODO: This should be rewritten with one of the available frameworks, and also in a way so that it picks up the theme etc when that's available. POC for now.
require_once '../vendor/autoload.php';
require_once '../data/civicrm.settings.php';

if (\CRM_Core_Session::getLoggedInContactId()) {
  header('Location: /civicrm');
}
elseif (!empty($_POST['username']) && !empty($_POST['pw'])) {
  // make sure ts and whatnot is loaded
  \Civi\Core\Container::singleton();
?>
<html>
<head>
<title><?php echo ts('CiviCRM Login'); ?></title>
</head>
<body>
<script type="text/javascript">
(function() {
  var request = new XMLHttpRequest();
  request.open("POST", window.location.href.substring(0, window.location.href.lastIndexOf("/")) + "/civicrm/authx/login");
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  request.responseType = "json";
  request.onreadystatechange = function() {
    console.log(request.response);
    if (request.readyState == 4) {
      if (request.status == 200) {
        if (request.response.user_id > 0) {
          window.location.href = "/civicrm?reset=1";
        } else {
          // probably won't ever be here?
          alert("Success but fail because ???");
          console.log(request.response);
        }
      } else {
        // todo - send errors back to the form via whatever forms framework we'll be using
        alert("Fail with status code " + request.status + " " + request.statusText);
        console.log(request.response);
      }
    }
  };
  //var data = <?php echo json_encode('_authx=Bearer+' . base64_encode("{$_POST['username']}:{$_POST['pw']}")); ?>;
  var data = <?php echo json_encode('_authx=Bearer+' . rawurlencode($_POST['pw'])); ?>;
  request.send(data);
})();
</script>
</body>
</html>
<?php
}
else {
  // make sure ts and whatnot is loaded
  \Civi\Core\Container::singleton();
?>
<html>
<head>
<title><?php echo ts('CiviCRM Login'); ?></title>
</head>
<body>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
<div><label for="username"><?php echo ts('Username:');?></label><input type="text" name="username"></div>
<div><label for="pw"><?php echo ts('Password:');?></label><input type="password" name="pw"></div>
<input type="submit" value="Log in">
</form>
</body>
</html>
<?php } ?>
