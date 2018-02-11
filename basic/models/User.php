<?php

namespace app\models;

use Codeception\Module\Yii2;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    public $referal ;
    public $referelCode;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $row = (new \yii\db\Query())
            ->select(['*'])
            ->from('user')
            ->where(['id'=>$id])
            ->one();
        return $row ? new static($row) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $row = (new \yii\db\Query())
            ->select(['*'])
            ->from('user')
            ->where(['username'=>$username])
            ->one();
        if($row)
            return new static($row);
        return null;
    }

    /**
     * @param $username
     * @param $password
     * @param string $referelCode
     * @throws \yii\db\Exception
     */
    public static function registerUser($username, $password){
        $user = User::checkReferelCode();
        $sql = (new \yii\db\Query())
            ->createCommand()->insert("user",[
                "username" => $username,
                "password" => $password,
                "referal" => (!empty($user)?$user->id:0),
                "referelCode"=> crc32(time())
            ])
            ->execute();
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
    public function validateReferal($referal){
        return true;
    }
    public function setReferal($value){
        $this->referal =  $value;
    }
    public function setReferelCode($value){
        $this->referelCode =  $value;
    }
    public static function saveReferelCode($value){
        $row = (new \yii\db\Query())
            ->select(['id'])
            ->from('user')
            ->where(['referelCode'=>$value])
            ->one();
        if($row){
            $session = \Yii::$app->session;
            $session->set("referel",$row["id"]);
        }
        return User::checkReferelCode();
    }
    public static function checkReferelCode(){
        $session = \Yii::$app->session;
        $id = $session->get("referel");
        if($id > 0){
            return User::findIdentity($id);
        }
        return null;

    }
    public function findRef(){
        $row = (new \yii\db\Query())
            ->select(['*'])
            ->from('user')
            ->where(['referal'=>$this->id])
            ->all();
        $res = array();
        if($row){
            foreach($row as $value)
                $res[] = new static($value);
        }
        return $res;
    }
}
