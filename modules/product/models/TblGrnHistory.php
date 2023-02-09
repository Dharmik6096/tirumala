<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_grn_history".
 *
 * @property integer $id
 * @property string $grn_code
 * @property string $grn_no
 * @property string $grn_date
 * @property string $vendor_master_code
 * @property string $mcc_plant_code
 * @property string $invoice_date
 * @property string $invoice_no
 * @property string $remarks
 * @property string $union_code
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
class TblGrnHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_grn_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['grn_code'], 'safe'],
            [['grn_date', 'invoice_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['remarks'], 'safe'],
            [['originating_type'], 'safe'],
            [['grn_code', 'grn_no', 'vendor_master_code', 'mcc_plant_code', 'invoice_no', 'ref_no'], 'safe'],
            [['union_code'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['operation_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'grn_code' => Yii::t('app', 'Grn Code'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'grn_date' => Yii::t('app', 'Grn Date'),
            'vendor_master_code' => Yii::t('app', 'Vendor Master Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'invoice_date' => Yii::t('app', 'Invoice Date'),
            'invoice_no' => Yii::t('app', 'Invoice No'),
            'remarks' => Yii::t('app', 'Remarks'),
            'union_code' => Yii::t('app', 'Union Code'),
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
