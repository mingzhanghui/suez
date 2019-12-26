<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 12/18/2019
 * Time: 15:59
 */

namespace app\model;

use think\Db;
use think\Model;

class Config extends Model
{
    protected $table = 'config';

    // table.config + unique(name)
    protected $field = ['id', 'c_name', 'c_value', 'c_comment'];

    public $timestamps = false;

    protected $dates = [];

    protected $error = "";

    public static function getByName($name) {
        $sql = "SELECT `c_value` from config WHERE `c_name`=?";
        $pairs = Db::query($sql, [$name]);
        if (empty($pairs)) {
            return "";
        }
        return $pairs[0]['c_value'];
    }

    /**
     * 配置项可能不存在, 不存在则取默认值, 并在数据库中设定默认值
     * @param $name string
     * @param $defaultValue string
     * @return string
     */
    public function tryGet($name, $defaultValue) {
        $sql = "SELECT `c_value` FROM config WHERE `c_name`=?";
        $pairs = Db::query($sql, [$name]);
        if (empty($pairs)) {
            return $defaultValue;
        }
        return $pairs[0]['c_value'];
    }

    /**
     * @param $name
     * @param $value
     * @return int
     */
    public static function setPair($name, $value) {
        $sql = "SELECT `id`, `c_name`, `c_value` from config WHERE `c_name`=?";
        $pairs = Db::query($sql, [$name]);
        if (empty($pairs)) {
            // insert
            $sql = "INSERT INTO `config`(`c_name`, `c_value`) VALUES(:name, :value)";
            return Db::execute($sql, [
                'name' => $name,
                'value' => $value
            ]);
        }
        $id = $pairs[0]['id'];
        // 与数据库中相同不做变更
        if (strcmp($value, $pairs[0]['c_value'])===0) {
            return 0;
        }
        // update
        $sql = "UPDATE `config` SET `c_name`=:name, `c_value`=:value, `updated_at`=:updated_at WHERE id=:id";
        return Db::execute($sql, [
            'name' => $name,
            'value' => $value,
            'updated_at' => date("Y-m-d H:i:s", time()),
            'id' => $id
        ]);
    }
}