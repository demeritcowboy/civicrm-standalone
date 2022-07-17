<?php
namespace Civi\Standalone;

class LoginService implements LoginServiceInterface {

  /**
   * @var \Civi\Standalone\LoginService
   */
  private static $_singleton;

  public static function singleton(): LoginService {
    if (!isset(self::$_singleton)) {
      self::$_singleton = new static();
    }
    return self::$_singleton;
  }

  /**
   * We don't know which provider is authenticating the user until we look
   * up the user, so do that and then hand off. It feels a bit redundant when
   * the authentication provider is the local civi database, but you could also
   * have a local database that is not the civi database which would be a
   * different provider.
   */
  public function authenticate(string $username, string $password): int {
    $uid = 0;
    $user = new \CRM_Standalone_DAO_User();
    $user->username = $username;
    if ($user->find(TRUE)) {
      // @todo something like $auth_provider = AuthenticationProviderFactory::create($user->auth_provider)
      // fake it for now and always use local
      $auth_provider = AuthenticationProvider\LocalCiviDatabase::singleton();
      $uid = $auth_provider->authenticate($username, $password);
    }
    return $uid;
  }

}
