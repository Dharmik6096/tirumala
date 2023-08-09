<?php

namespace app\modules\usermanagement\models\rbacDB;

use Yii;
use yii\helpers\Inflector;

trait AbstractItemTrait {

    public $organizations_type;

    public static function create($name, $description = null, $groupCode = null, $ruleName = null, $data = null) {
        $item = new static;

        $item->type = static::ITEM_TYPE;
        $item->name = $name;
        $item->description = ($description === null) ? Inflector::titleize($name) : $description;
        $item->rule_name = $ruleName;
        $item->group_code = $groupCode;
        $item->data = $data;

        $item->save();

        return $item;
    }

    public function rules() {
        return [
            [['name', 'rule_name', 'group_code'], 'trim'],
            ['description', 'required', 'on' => 'webInput'],
            ['description', 'string', 'max' => 255],
            ['name', 'required'],
            ['name', 'validateUniqueName'],
            [['name', 'rule_name', 'group_code'], 'string', 'max' => 64],
            [['rule_name', 'description', 'group_code', 'data'], 'default', 'value' => null],
            [['type', 'entry_type'], 'integer'],
            ['organizations_type', 'safe'],
            ['type', 'in', 'range' => [static::TYPE_ROLE, static::TYPE_PERMISSION, static::TYPE_ROUTE]],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('app', 'Code'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule'),
            'group_code' => Yii::t('app', 'Group'),
            'data' => Yii::t('app', 'Data'),
            'type' => Yii::t('app', 'Type'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Updated'),
            'organizations_type' => Yii::t('app', 'Organizations Type'),
        ];
    }

    public function beforeSave($insert) {
        $this->type = static::ITEM_TYPE;
        (Yii::$app->session->get('organizations_type') == 'UNION') ? $this->entry_type = 2 : $this->entry_type = 1;
        return parent::beforeSave($insert);
    }

}
