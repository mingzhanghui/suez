<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 12/18/2019
 * Time: 10:38
 */

namespace app\model;

use app\utils\Crypt;
use think\Db;
use think\Model;

class Admin extends Model
{
    protected $pk = 'id';

    protected $field = ['id', 'account', 'password', 'enabled'];

    protected $autoWriteTimestamp = false;

    protected $table = 'admin';

    /**
     * @param $id int
     */
    public function setId($id) {
        $this->data['id'] = $id;
    }

    public function getId() {
        return $this->data['id'];
    }

    /**
     * @param $account string
     */
    public function setAccount($account) {
        $this->data['account'] = $account;
    }

    /**
     * @return string
     */
    public function getAccount() {
        return $this->data['account'];
    }

    /**
     * @param $plain string
     */
    public function setPassword($plain) {
        $this->data['password'] = Crypt::encrypt($plain);
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->data['password'];
    }

    /**
     * @param $enabled int
     */
    public function setEnabled($enabled) {
        $this->data['enabled'] = $enabled;
    }

    /**
     * @param $plain string
     * @return bool
     */
    public function isValidPwd($plain) {
        return strcmp($this->getPassword(), Crypt::encrypt($plain)) === 0;
    }

    public function isEnabled() {
        return $this->data['enabled'] == 1;
    }

    public static function updatePwdByAccount($account, $password) {
        return Admin::where("account", "=", $account)->update([
            'password' => Crypt::encrypt($password)
        ]);
    }

    /**
     * @param $account
     * @return Admin|null
     */
    public static function findByAccount($account) {
        $sql = "SELECT `id`, `account`, `password`, `enabled` FROM `admin` WHERE `account`=?";
        $res = Db::query($sql, [$account]);
        if (empty($res)) {
            return null;
        }
        $assoc = $res[count($res)-1];
        $admin = new self();
        $admin->data['id'] = $assoc['id'];
        $admin->data['account'] = $assoc['account'];
        $admin->data['password'] = $assoc['password'];
        $admin->data['enabled'] = $assoc['enabled'];
        return $admin;
    }
}