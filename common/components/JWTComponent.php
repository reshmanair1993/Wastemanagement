<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use Firebase\JWT\JWT;
class JWTComponent extends Component
{
  public $PRIVATE_KEY;
  public $PUBLIC_KEY;

  function __construct($config=[]) {
    parent::__construct($config);
  }

  public function encode($data)
  {
    // print_r($this->PRIVATE_KEY);exit;
    $private_key = $this->PRIVATE_KEY;
    $token = JWT::encode($data,$private_key,"RS256");
    // print_r($token);exit;
    return $token;
  }
  public function decode($token)
  {
    $public_key = $this->PUBLIC_KEY;
    // $data = JWT::decode($token,$public_key,['RS256']);
    try {
      $data = JWT::decode($token,$public_key,['RS256']);
    } catch (\Exception $e) { // Also tried JwtException
        $data = null;
      }
    return $data;
  }
}
