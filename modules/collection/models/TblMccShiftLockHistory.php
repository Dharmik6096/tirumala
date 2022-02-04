<?php

namespace app\modules\collection\models;

use Yii;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_mcc_shift_lock_history".
 *
 * @property integer $id
 * @property string $shift_lock_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property integer $data_lock
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
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblMccShiftLockHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_shift_lock_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['date_time_of_collection', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['data_lock', 'originating_type'], 'safe'],
                [['shift_lock_code'], 'safe'],
                [['union_code'], 'safe'],
                [['plant_code', 'mcc_plant_code'], 'safe'],
                [['shift_code'], 'safe'],
                [['created_by', 'updated_by', 'history_created_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['operation_type'], 'safe'],
                [['qty', 'avg_fat', 'avg_snf', 'amount', 'bmc_lock', 'member_lock', 'product_sale_lock'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'shift_lock_code' => Yii::t('app', 'Shift Lock Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'data_lock' => Yii::t('app', 'Data Lock'),
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
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'history_created_by']);
    }

}
