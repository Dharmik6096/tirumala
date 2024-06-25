<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_union_share_config".
 *
 * @property integer $union_share_config_code
 * @property string $union_code
 * @property string $process_type
 * @property integer $gender_code
 * @property integer $min_share
 * @property integer $max_share
 * @property string $admission_fee
 * @property string $per_share_rate
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblUnionShareConfig extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_union_share_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['gender_code', 'min_share', 'max_share', 'originating_type', 'admission_fee', 'per_share_rate', 'union_code', 'created_at', 'updated_at', 'process_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'union_share_config_code' => Yii::t('app', 'Union Share Config Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'process_type' => Yii::t('app', 'Process Type'),
            'gender_code' => Yii::t('app', 'Gender Code'),
            'min_share' => Yii::t('app', 'Min Share'),
            'max_share' => Yii::t('app', 'Max Share'),
            'admission_fee' => Yii::t('app', 'Admission Fee'),
            'per_share_rate' => Yii::t('app', 'Per Share Rate'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getShareDetail($type, $gender, $union, $bmc) {
        $shareData = $this->find()->where(['process_type' => $type, 'gender_code' => $gender, 'bmc_code' => $bmc])->one();
        if (empty($shareData)) {
            $shareData = $this->find()->where(['process_type' => $type, 'gender_code' => $gender, 'union_code' => $union, 'bmc_code' => null])->one();
        }
        return $shareData;
    }

}
