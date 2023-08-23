<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_animal_inspector_request_history".
 *
 * @property integer $id
 * @property string $animal_inspector_request_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $user_type
 * @property string $member_code
 * @property string $member_name
 * @property string $mobile_no
 * @property string $address
 * @property string $ai_request_for
 * @property integer $animal_inspector_code
 * @property string $expected_visit_date
 * @property string $remarks
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
class TblAnimalInspectorRequestHistory extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_animal_inspector_request_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['animal_inspector_code', 'originating_type'], 'integer'],
                [['expected_visit_date', 'created_at', 'updated_at', 'history_created_at', 'request_date', 'status', 'close_remarks'], 'safe'],
                [['animal_inspector_request_code'], 'string', 'max' => 40],
                [['union_code'], 'string', 'max' => 3],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'string', 'max' => 12],
                [['user_type', 'member_code', 'ai_request_for', 'created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 20],
                [['member_name', 'mobile_no', 'remarks'], 'string', 'max' => 255],
                [['address'], 'string', 'max' => 500],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                [['operation_type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'animal_inspector_request_code' => Yii::t('app', 'Animal Inspector Request Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'user_type' => Yii::t('app', 'User Type'),
            'member_code' => Yii::t('app', 'Member Code'),
            'member_name' => Yii::t('app', 'Member Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'address' => Yii::t('app', 'Address'),
            'ai_request_for' => Yii::t('app', 'Ai Request For'),
            'animal_inspector_code' => Yii::t('app', 'Animal Inspector Code'),
            'expected_visit_date' => Yii::t('app', 'Expected Visit Date'),
            'remarks' => Yii::t('app', 'Remarks'),
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
