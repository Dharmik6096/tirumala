<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;
use app\modules\installation\models\TblUserAndroid;
use app\modules\syncutility\models\TblSentbox;
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_collection_approval".
 *
 * @property string $uuid
 * @property string $date
 * @property integer $shift_code
 * @property integer $collection_type
 * @property string $code
 * @property integer $is_approve
 * @property string $requested_by
 * @property string $approved_by
 * @property string $approve_date
 * @property string $allow_till_date
 * @property integer $valid_hours
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 */
class TblCollectionApproval extends \app\models\ChildModel {

    public $is_sentbox = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_collection_approval';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['uuid', 'date', 'shift_code', 'collection_type', 'code', 'is_approve', 'requested_by', 'approved_by', 'approve_date', 'allow_till_date', 'valid_hours', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['valid_hours'], 'required', 'on' => 'approve', 'except' => 'androidsync'],
                [['date', 'approve_date', 'allow_till_date', 'created_at', 'updated_at'], 'safe'],
                [['shift_code', 'collection_type', 'is_approve', 'valid_hours', 'originating_type'], 'integer'],
                [['uuid'], 'string', 'max' => 255],
                [['valid_hours'], 'integer', 'min' => 1],
                [['code', 'originating_org_type', 'originating_org_code'], 'string', 'max' => 25],
                [['requested_by', 'approved_by'], 'string', 'max' => 14],
                [['created_by', 'updated_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'uuid' => Yii::t('app', 'Uuid'),
            'date' => Yii::t('app', 'Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'collection_type' => Yii::t('app', 'Collection Type'),
            'code' => Yii::t('app', 'Code'),
            'is_approve' => Yii::t('app', 'Is Approve'),
            'requested_by' => Yii::t('app', 'Requested By'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'approve_date' => Yii::t('app', 'Approve Date'),
            'allow_till_date' => Yii::t('app', 'Allow Till Date'),
            'valid_hours' => Yii::t('app', 'Valid Hours'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'f_union_code' => Yii::t('app', 'Union'),
            'f_plant_code' => Yii::t('app', 'Plant'),
            'f_mcc_code' => Yii::t('app', 'MCC'),
            'f_bmc_code' => Yii::t('app', 'BMC'),
            'f_dcs_code' => Yii::t('app', 'DCS'),
        ];
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getUserAndroidCode() {
        return $this->hasOne(TblUserAndroid::className(), ['user_code' => 'requested_by']);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'approved_by']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
//        if ($this->originating_org_type == 'VLC') {
//            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->userAndroidCode->dcs_code, '');
//        } else if ($this->originating_org_type == 'BMC') {
//            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->userAndroidCode->bmc_code, '', '');
//        } else if ($this->originating_org_type == 'MCC') {
//            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->userAndroidCode->mcc_plant_code, '', '', '');
//        }
//        foreach ($sentboxArray as $sent) {
        if (!empty($this->originating_org_code) && !empty($this->originating_org_type)) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';

//            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            $sentbox = $this->sentboxModel($this->originating_org_code, $this->originating_org_type);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
//        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = Yii::$app->session->get('Unions'); //$this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'code']);
    }

}
