<?php
class Config {

  const DATE_FORMAT = "Y-m-d H:i:s";

public static function DB_HOST(){
  return Config::get_env("DB_HOST", "localhost");
}
public static function DB_USERNAME(){
  return Config::get_env("DB_USERNAME", "emirbeba12");
}
public static function DB_PASSWORD(){
  return Config::get_env("DB_PASSWORD", "emiremir");
}
public static function DB_SCHEME(){
  return Config::get_env("DB_SCHEME", "ebfitness");
}
public static function DB_PORT(){
  return Config::get_env("DB_PORT", "3306");
}
public static function SMTP_HOST(){
  return Config::get_env("SMTP_HOST", "smtp.mailgun.org");
}
public static function SMTP_PORT(){
  return Config::get_env("SMTP_PORT", "587");
}
public static function SMTP_USER(){
  return Config::get_env("SMTP_USER", NULL);
}
public static function SMTP_PASSWORD(){
  return Config::get_env("SMTP_PASSWORD", NULL);
}

  const JWT_SECRET ="m1qx2KcwtcZVqn3F7upF";
  const JWT_TOKEN_TIME = 2419200;

  public static function get_env($name, $default){
  return isset($_ENV[$name]) && trim($_ENV[$name]) != '' ? $_ENV[$name] : $default;
 }
}

?>
