<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_banner_applicability_history".
 *
 * @property integer $id
 * @property integer $banner_applicability_code
 * @property integer $banner_code
 * @property string $login_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblBannerApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_banner_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['login_type', 'banner_applicability_code', 'banner_code', 'originating_type', 'operation_type', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'history_created_by', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'banner_applicability_code' => Yii::t('app', 'Banner Applicability Code'),
            'banner_code' => Yii::t('app', 'Banner Code'),
            'login_type' => Yii::t('app', 'Login Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
