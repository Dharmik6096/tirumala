<?php

namespace app\modules\feedback\models;

use Yii;
use app\modules\feedback\models\TblEiplAppFeedbackMasterTxn;
use webvimark\modules\UserManagement\models\User;
//use app\modules\organisation\models\Mastermcc;
//use app\modules\organisation\models\MasterBmc;
//use app\modules\organisation\models\Mastervillage;
use app\modules\dcsoperation\models\Masterfarmer;
use app\modules\feedback\models\TblEiplAppFeedbackItem;
//use app\modules\organisation\models\Masterplant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_eipl_app_feedback_master".
 *
 * @property integer $eipl_app_feedback_master_code
 * @property string $user_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $member_code
 * @property integer $eipl_app_feedback_item_code
 * @property string $feedback_message
 * @property string $feedback_message_datetime
 * @property string $user_type
 * @property integer $feedback_status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblEiplAppFeedbackMaster extends \app\models\ChildModel {

    public $member_name, $activityStatus;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_feedback_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'feedback_message', 'user_type', 'created_by', 'updated_by'], 'string'],
            [['eipl_app_feedback_item_code', 'feedback_status'], 'integer'],
            [['feedback_message_datetime', 'created_at', 'updated_at', 'activityStatus'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'eipl_app_feedback_master_code' => Yii::t('app', 'Eipl App Feedback Master Code'),
            'user_code' => Yii::t('app', 'User Name'),
            'plant_code' => Yii::t('app', 'Plant Name'),
            'mcc_plant_code' => Yii::t('app', 'MCC Name'),
            'bmc_code' => Yii::t('app', 'BMC Name'),
            'dcs_code' => Yii::t('app', 'DCS Name'),
            'member_code' => Yii::t('app', 'Member Name'),
            'eipl_app_feedback_item_code' => Yii::t('app', 'Feedback Item Name'),
            'feedback_message' => Yii::t('app', 'Feedback Message'),
            'feedback_message_datetime' => Yii::t('app', 'Feedback Message Date'),
            'user_type' => Yii::t('app', 'User Type'),
            'feedback_status' => Yii::t('app', 'Feedback Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getFeedbackMasterTxn() {
        return $this->hasMany(TblEiplAppFeedbackMasterTxn::className(), ['eipl_app_feedback_master_code' => 'eipl_app_feedback_master_code']);
    }

    public function getUserCodeById() {
        return $this->hasOne(User::className(), ['id' => 'user_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(Masterfarmer::className(), ['member_code' => 'member_code']);
    }

    public function getEiplAppFeedbackItemCode() {
        return $this->hasOne(TblEiplAppFeedbackItem::className(), ['eipl_app_feedback_item_code' => 'eipl_app_feedback_item_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

}
