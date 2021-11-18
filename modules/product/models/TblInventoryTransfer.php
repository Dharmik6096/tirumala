<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\product\models\TblInventoryTransferTxn;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_inventory_transfer".
 *
 * @property string $inventory_transfer_code
 * @property string $inventory_transfer_no
 * @property string $inventory_transfer_date
 * @property string $from_type
 * @property string $from_code
 * @property string $to_type
 * @property string $to_code
 * @property string $remarks
 * @property string $union_code
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
class TblInventoryTransfer extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $from_dcs_code, $from_mcc_plant_code, $from_bmc_code, $to_dcs_code, $to_mcc_plant_code, $to_bmc_code;

    public static function tableName() {
        return 'tbl_inventory_transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['inventory_transfer_code'], 'required'],
                [['inventory_transfer_date', 'created_at', 'updated_at', 'from_mcc_plant_code', 'from_bmc_code', 'from_dcs_code', 'to_mcc_plant_code', 'to_bmc_code', 'to_dcs_code'], 'safe'],
                [['remarks'], 'string'],
                [['originating_type'], 'integer'],
                [['inventory_transfer_code', 'inventory_transfer_no'], 'string', 'max' => 30],
                [['from_type', 'from_code', 'to_type', 'to_code'], 'string', 'max' => 50],
                [['union_code'], 'string', 'max' => 3],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'inventory_transfer_code' => Yii::t('app', 'Inventory Transfer Code'),
            'inventory_transfer_no' => Yii::t('app', 'Inventory Transfer No'),
            'inventory_transfer_date' => Yii::t('app', 'Inventory Transfer Date'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_code' => Yii::t('app', 'From Code'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_code' => Yii::t('app', 'To Code'),
            'remarks' => Yii::t('app', 'Remarks'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'from_mcc_plant_code' => Yii::t('app', 'MCC'),
            'to_mcc_plant_code' => Yii::t('app', 'MCC'),
            'from_bmc_code' => Yii::t('app', 'BMC'),
            'to_bmc_code' => Yii::t('app', 'BMC'),
            'from_dcs_code' => Yii::t('app', 'DCS'),
            'to_dcs_code' => Yii::t('app', 'DCS'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
