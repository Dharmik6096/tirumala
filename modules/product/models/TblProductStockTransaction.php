<?php

namespace app\modules\product\models;

use Yii;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_product_stock_transaction".
 *
 * @property string $product_stock_transaction_code
 * @property string $old_value
 * @property string $new_value
 * @property string $final_value
 * @property string $transaction_type
 * @property string $transaction_date
 * @property string $reference_code
 * @property string $product_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
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
class TblProductStockTransaction extends \app\models\ChildModel {

    public $is_sentbox = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_stock_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_stock_transaction_code'], 'required', 'on' => ['androidsync']],
            [['product_stock_transaction_code', 'transaction_type', 'reference_code', 'product_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['old_value', 'new_value', 'final_value', 'sap_batch_no'], 'safe'],
            [['transaction_date', 'created_at', 'updated_at'], 'safe'],
            [['originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_stock_transaction_code' => Yii::t('app', 'Product Stock Transaction Code'),
            'old_value' => Yii::t('app', 'Old Value'),
            'new_value' => Yii::t('app', 'New Value'),
            'final_value' => Yii::t('app', 'Final Value'),
            'transaction_type' => Yii::t('app', 'Transaction Type'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'reference_code' => Yii::t('app', 'Reference Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
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
        ];
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getCode($autoInc = 1) {
        $primaryKey = 'product_stock_transaction_code';
        $orgCode = 'PORTAL-' . $this->bmc_code . '-';
        $len = strlen($orgCode);
        $val = $this->find()
                ->select(["MAX(CONVERT(INT,substring(" . $primaryKey . ", " . $len . " +1,8))) AS " . $primaryKey])
                ->where(['like', $primaryKey, trim($orgCode) . '%', false])
                ->one();
        $code1 = (int) $val[$primaryKey] + $autoInc;
        $value = $orgCode . $code1;

        return $value;

    }

//    public function afterSave($insert, $changedAttributes) {
//        $sentboxArray = [];
//        if (!empty($this->dcs_code)) {
//            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
//        } else if (!empty($this->bmc_code)) {
//            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code, '', '');
//        } else if (!empty($this->mcc_plant_code)) {
//            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->mcc_plant_code, '', '', '');
//        }
//        foreach ($sentboxArray as $sent) {
//            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
//            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
//            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
//                if (!($sentbox->setSentbox($this, $flag))) {
//                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
//                }
//            }
//        }
//    }
//    private function sentboxModel($code, $type) {
//        $sentbox = new TblSentbox();
//        $sentbox->dest_org_id = $code;
//        $sentbox->source_org_id = $this->union_code;
//        $sentbox->dest_org_type = $type;
//        return $sentbox;
//    }
//    public function afterDelete() {
//        $sentboxArray = [];
//        if (!empty($this->dcs_code)) {
//            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
//        } else if (!empty($this->bmc_code)) {
//            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code, '', '');
//        } else if (!empty($this->mcc_plant_code)) {
//            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->mcc_plant_code, '', '', '');
//        }
//        foreach ($sentboxArray as $sent) {
//            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
//            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
//                if (!($sentbox->setSentbox($this, 'DELETE'))) {
//                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
//                }
//            }
//        }
//    }

    public function getProductTransactionCode($id) {
        return $this->find()->where(['reference_code' => $id])->all();
    }

}
