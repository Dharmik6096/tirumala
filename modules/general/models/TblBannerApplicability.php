<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_banner_applicability".
 *
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
 */
class TblBannerApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_banner_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['login_type', 'banner_code', 'originating_type', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['login_type'], 'required', 'on' => ['banner_upload']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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
        ];
    }

}
