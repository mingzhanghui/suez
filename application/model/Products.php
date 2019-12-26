<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 12/24/2019
 * Time: 15:16
 */

namespace app\model;


use think\Model;

class Products extends Model
{
    protected $table = "products";

    protected $autoWriteTimestamp = 'date';

    protected $type = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $createTime = "created_at";
    protected $updateTime = "updated_at";

    protected $pk = 'id';

    protected $field = ['id', 'batch_number', 'mm6', 'mm7', 'language', 'weight'];

    public function __construct($data = []) {
        parent::__construct($data);
    }

    public static function emptyInstance() {
        $o = new self();
        $o->setBatchNumber('');
        $o->set6mm('');
        $o->set7mm('');
        $o->setLanguage('');
        $o->setWeight(0);
    }

    public function getBatchNumber() {
        return $this->data['batch_number'];
    }

    public function setBatchNumber($batchNumber) {
        $this->data['batch_number'] = $batchNumber;
    }

    public function get6mm() {
        return $this->data['mm6'];
    }

    public function set6mm($mm6) {
        $this->data['mm6'] = $mm6;
    }

    public function set7mm($mm7) {
        $this->data['mm7'] = $mm7;
    }

    public function get7mm() {
        return $this->data['mm7'];
    }

    public function getLanguage() {
        return $this->data['language'];
    }

    public function setLanguage($lan) {
        $this->data['language'] = $lan;
    }

    public function setWeight($w) {
        $this->data['weight'] = $w;
    }

    public function getWeight() {
        return $this->data['weight'];
    }
}