<?php

namespace app\modules\general\models;

use Yii;
use app\modules\document\models\TblAttachment;
use app\modules\organisation\models\TblUnions;
use app\modules\general\models\TblBannerApplicability;

/**
 * This is the model class for table "tbl_banner".
 *
 * @property integer $banner_code
 * @property string $union_code
 * @property string $from_date
 * @property string $to_date
 * @property integer $seq_no
 * @property string $title
 * @property string $banner_for
 * @property string $description
 * @property string $tap_operation
 * @property string $tap_event
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblBanner extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_banner';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'title', 'banner_for', 'description', 'tap_operation', 'tap_event', 'from_date', 'created_by', 'updated_by', 'to_date', 'created_at', 'updated_at', 'seq_no', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['title', 'banner_for', 'description', 'from_date', 'to_date', 'seq_no'], 'required'],
                [['tap_event'], 'required', 'when' => function ($model) {
                    return ($model->tap_operation != '');
                }, 'whenClient' => "function (attribute, value) { 
                  return $('#tblbanner-tap_operation').val() != ''; 
              }",],
                [['tap_event'], 'url', 'when' => function ($model) {
                    return ($model->tap_operation == 'external') || ($model->tap_operation == 'pdf');
                }, 'whenClient' => "function (attribute, value) { 
                  return $('#tblbanner-tap_operation').val() == 'external' || $('#tblbanner-tap_operation').val() == 'pdf'; 
              }",],
                [['to_date'], 'validateToDate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'banner_code' => Yii::t('app', 'Banner Code'),
            'union_code' => Yii::t('app', 'Union'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'seq_no' => Yii::t('app', 'Seq No'),
            'title' => Yii::t('app', 'Title'),
            'banner_for' => Yii::t('app', 'Banner For'),
            'description' => Yii::t('app', 'Description'),
            'tap_operation' => Yii::t('app', 'Tap Operation'),
            'tap_event' => Yii::t('app', 'Tap Event'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getAttachment() {
        return $this->hasMany(TblAttachment::className(), ['module_code' => 'banner_code']);
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->to_date) && !empty($this->from_date) && ($this->from_date > $this->to_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date Must be Greater than From Date.'));
            return false;
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBannerApplicabilityCode() {
        return $this->hasOne(TblBannerApplicability::className(), ['banner_code' => 'banner_code']);
    }

    public function getDepartmentId() {
        return $this->hasOne(TblDepartment::className(), ['department' => 'department']);
    }

}
