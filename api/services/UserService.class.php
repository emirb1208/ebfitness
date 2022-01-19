<?php

require_once dirname(__FILE__).'/BaseService.class.php';
require_once dirname(__FILE__).'/../dao/UserDao.class.php';
require_once dirname(__FILE__).'/../dao/AccountDao.class.php';

class UserService extends BaseService{

  private $accountDao;

  public function __construct(){
    $this->dao = new UserDao();
    $this->accountDao = new AccountDao();
  }

  public function reset($user){
    $db_user = $this->dao->get_user_by_token($user['token']);

    if (!isset($db_user['id'])) throw new Exception("Invalid token", 400);

    $this->dao->update($db_user['id'], ['password' => md5($user['password'])]);
}

  public function login($user){
    $db_user = $this->dao->get_user_by_email($user['email']);

    if (!isset($db_user['id'])) throw new Exception("User doesn't exists", 400);

    if ($db_user['status'] != 'ACTIVE') throw new Exception("Account not active", 400);

    $account = $this->accountDao->get_by_id($db_user['account_id']);
    if (!isset($account['id']) || $account['status'] != 'ACTIVE') throw new Exception("Account not active", 400);

    if ($db_user['password'] != md5($user['password'])) throw new Exception("Incorrect password", 400);

    return $db_user;
  }

  public function register($user){
    if(!isset($user['account'])) throw new Exception("Account field is required");

    try {
    $this->dao->beginTransaction();
    $account = $this->accountDao->add([
      "name" => $user['account'],
      "status" => "PENDING",
      "created_at" => date(Config::DATE_FORMAT),
      "account_workoutplan_id" => 4,
      "account_fitnessgoal_id" => 4
    ]);

    $user = parent::add([
      "account_id" => $account['id'],
      "name" => $user['name'],
      "email" => $user['email'],
      "password" => md5($user['password']),
      "status" => "PENDING",
      "role" => "USER",
      "created_at" => date(Config::DATE_FORMAT),
      "token" => md5(random_bytes(16))
    ]);
    $this->dao->commit();

  } catch (\Exception $e) {
    $this->dao->rollBack();

/* adding if statement due to using php 7 */
    if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}

    if (str_contains($e->getMessage(), 'users.uq_user_email')) {
        throw new Exception("Account with same email exists in the database", 400, $e);
    }else{
        throw $e;
      }
  }

    // TODO: send email with some token

    return $user;
  }

  public function confirm($token){
    $user = $this->dao->get_user_by_token($token);

    if(!isset($user['id'])) throw new Exception("Invalid token");

    $this->dao->update($user['id'], ["status" => "ACTIVE"]);
    $this->accountDao->update($user['account_id'], ["status" => "ACTIVE"]);

    //TODO send email to customer
  }

}
?>
