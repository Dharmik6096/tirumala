<?php

namespace app\modules\product\models;

use app\modules\usermanagement\models\TblUserDispatchCenterMapping;
use Yii;

/**
 * This is the model class for table "tbl_dispatch_center_type".
 *
 * @property string $dispatch_center_type_code
 * @property string $dispatch_center_type
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblDispatchCenterType extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dispatch_center_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dispatch_center_type_code', 'dispatch_center_type'], 'required'],
                [['dispatch_center_type'], 'string', 'max' => 100],
                [['dispatch_center_type_code', 'dispatch_center_type', 'is_active', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dispatch_center_type_code' => Yii::t('app', 'Dispatch Center Type Code'),
            'dispatch_center_type' => Yii::t('app', 'Dispatch Center Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getCode() {
        $data = $this->find()->select(["MAX(CONVERT(INT,dispatch_center_type_code)) AS dispatch_center_type_code"])->one();
        return (int) $data['dispatch_center_type_code'] + 1;
    }

    public function getUserDispatchCenterMapping() {
        return $this->hasOne(TblUserDispatchCenterMapping::className(), ['dispatch_center_type_code' => 'dispatch_center_type_code']);
    }

}
