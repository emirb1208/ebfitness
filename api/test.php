<?php
require_once dirname(__FILE__)."/dao/AccountDao.class.php";

//$user_dao = new UserDao();

//$user = $user_dao -> get_user_by_id("10");

 //------------------------------------

$account_dao = new AccountDao();

//$account = $account_dao -> get_account_by_email("emir.beba@stu.ibu.edu.ba");

$account = [
    "contact" => 0000
];


$account = $account_dao -> update_account(10, $account);

print_r($account);

?>
