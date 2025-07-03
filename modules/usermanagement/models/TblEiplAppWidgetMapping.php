<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_eipl_app_widget_mapping".
 *
 * @property integer $mapping_id
 * @property integer $widget_id
 * @property string $login_type
 * @property string $department
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblEiplAppWidgetMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_widget_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['widget_id'], 'integer'],
                [['union_code'], 'required'],
                [['login_type', 'department', 'created_by', 'updated_by'], 'string'],
                [['created_at', 'updated_at', 'union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mapping_id' => Yii::t('app', 'Mapping ID'),
            'widget_id' => Yii::t('app', 'Widget ID'),
            'login_type' => Yii::t('app', 'Login Type'),
            'department' => Yii::t('app', 'Department'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getWidgets() {
        if (!empty($this->login_type)) {
            $query = $this->find()
                    ->where(['login_type' => $this->login_type, "ISNULL(department,'')" => $this->department, 'union_code' => $this->union_code])
                    ->all();
            return ArrayHelper::map($query, 'widget_id', 'widget_id');
        } else {
            return [];
        }
    }

    public function getExistMappedWidgets() {
        return $this->find()
                        ->where(['login_type' => $this->login_type, 'widget_id' => $this->widget_id, "ISNULL(department,'')" => $this->department, 'union_code' => $this->union_code])
                        ->one();
    }

}
