<?php

namespace app\modules\usermanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_app_menu_actions".
 *
 * @property integer $action_code
 * @property string $action_name
 * @property string $service_url
 * @property string $description
 * @property integer $is_active
 * @property integer $sequence_no
 * @property integer $parent_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblEiplAppMenuActions extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_menu_actions';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['action_name', 'service_url', 'description', 'created_by', 'updated_by'], 'string'],
            [['is_active', 'sequence_no', 'parent_code'], 'integer'],
            [['created_at', 'updated_at', 'app_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'action_code' => Yii::t('app', 'Action Code'),
            'action_name' => Yii::t('app', 'Action Name'),
            'service_url' => Yii::t('app', 'Service Url'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'sequence_no' => Yii::t('app', 'Sequence No'),
            'parent_code' => Yii::t('app', 'Parent Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'app_type' => Yii::t('app', 'App Type'),
        ];
    }

    public function getActionDetail() {
        $allMenu = $this->find()
                ->select(['action_code', 'action_name', 'parent_code', 'description'])
                ->where(['is_active' => 1])
                ->andFilterWhere(['app_type' => $this->app_type])
                ->orderBy(['sequence_no' => SORT_ASC, 'description' => SORT_ASC])
                ->asArray()
                ->all();
        $menuTree = [];
        if (!empty($allMenu)) {
            $menuTree = $this->buildTree($allMenu);
        }
        return $menuTree;
    }

    function buildTree($main_menu, $parentId = 0) {
        $sub_menu = [];
        foreach ($main_menu as $menu) {
            if ($menu['parent_code'] == $parentId) {
                $children = $this->buildTree($main_menu, $menu['action_code']);
                if ($children) {
                    $menu['children'] = $children;
                }
                $sub_menu[$menu['action_code']] = $menu;
            }
        }
        return $sub_menu;
    }

}
