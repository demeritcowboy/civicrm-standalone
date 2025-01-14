<?php

require_once '../vendor/autoload.php';
require_once '../civicrm.settings.php';

function invoke() {
  // Redirect to the dashboard for discoverability
  // but in the future we may want a login page or something else.
  if ($_SERVER['REQUEST_URI'] == '/') {
    CRM_Utils_System::redirect('/civicrm');
  }

  if (!empty($_SERVER['REQUEST_URI'])) {
    // Add CSS, JS, etc. that is required for this page.
    \CRM_Core_Resources::singleton()->addCoreResources();

    $parts = explode('?', $_SERVER['REQUEST_URI']);
    $args = explode('/', $parts[0]);
    // Remove empty values
    $args = array_values(array_filter($args));
    // Set this for compatibility
    $_GET['q'] = implode('/', $args);
    // And finally render the page
    print CRM_Core_Invoke::invoke($args);
  }
  else {
    // @todo Is it necessary to support this?
    // Apache has not been tested yet, but presumably not required.
    $config = CRM_Core_Config::singleton();
    $urlVar = $config->userFrameworkURLVar;
    print CRM_Core_Invoke::invoke(explode('/', $_GET[$urlVar]));
  }
}

invoke();
