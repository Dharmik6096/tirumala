<?php

namespace app\modules\installation\models;

use Yii;

/**
 * This is the model class for table "tbl_action".
 *
 * @property integer $action_code
 * @property string $action_name
 * @property integer $menu_level
 * @property string $description
 * @property integer $parent_code
 * @property integer $action_type
 * @property integer $action_for
 */
class TblAction extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_action';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['menu_level', 'parent_code', 'action_type', 'action_for'], 'integer'],
            [['action_name', 'description'], 'string', 'max' => 255],
            [['action_name', 'menu_level', 'parent_code', 'description'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'action_code' => Yii::t('app', 'Action Code'),
            'action_name' => Yii::t('app', 'Action Name'),
            'menu_level' => Yii::t('app', 'Menu Level'),
            'description' => Yii::t('app', 'Description'),
            'parent_code' => Yii::t('app', 'Parent Code'),
            'action_type' => Yii::t('app', 'Action Type'),
            'action_for' => Yii::t('app', 'Action For'),
        ];
    }

    public function getActionDetail() {
        $allMenu = $this->find()
                ->select(['action_code', 'action_name', 'parent_code', 'description'])
                ->orderBy(['parent_code' => SORT_ASC])
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
