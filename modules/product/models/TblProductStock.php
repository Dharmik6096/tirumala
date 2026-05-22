<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\syncutility\models\TblSentbox;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_product_stock".
 *
 * @property string $product_stock_code
 * @property string $product_code
 * @property string $stock
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
class TblProductStock extends \app\models\ChildModel {

    public $is_sentbox = TRUE;
    public $qty, $type, $product_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_stock_code'], 'required', 'on' => ['androidsync']],
                [['product_stock_code', 'product_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['stock', 'sap_batch_no'], 'safe'],
                [['created_at', 'updated_at'], 'safe'],
                [['originating_type'], 'safe'],
                [['qty', 'type', 'rate'], 'safe'],
                [['rate'], 'default', 'value' => 0.00],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_stock_code' => Yii::t('app', 'Product Stock Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'stock' => Yii::t('app', 'Stock'),
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

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    // public function getPartyCode() {
    //     return $this->hasOne(TblGeneralPartyMaster::className(), ['generate_party_code' => 'customer_code']);
    // }

    public function getExistStock($type, $batch = '', $checkMccStock = false) {
        $query = $this->find()->where(['union_code' => $this->union_code, 'mcc_plant_code' => $this->mcc_plant_code, 'product_code' => $this->product_code]);
        if (!empty($batch)) {
            $query->andWhere(['sap_batch_no' => (string) $batch]);
        }
        if (strtoupper($type) == 'MCC') {
            $query->andWhere(['AND', ['is', 'bmc_code', NULL], ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'BMC') {
            $query->andWhere(['bmc_code' => $this->bmc_code])
                    ->andWhere(['AND', ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'DCS' || strtoupper($type) == 'VLC') {
            $query->andWhere(['bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code]);
        }
        return $query->orderBy(['created_at' => SORT_ASC])->one();
    }

    public function getCode($autoInc = 1) {
        return Yii::$app->general->getNextCode($this, $autoInc);
    }

    public function setCodes($type, $code) {
        if (strtoupper($type) == 'MCC') {
            $this->mcc_plant_code = $code;
            $this->plant_code = Yii::$app->general->getforeignkey($this->mccPlantCode, 'plant_code');
        } elseif (strtoupper($type) == 'BMC') {
            $this->bmc_code = $code;
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
        } elseif (strtoupper($type) == 'DCS' || strtoupper($type) == 'VLC') {
            $this->dcs_code = $code;
            $this->bmc_code = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'mcc_plant_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'plant_code');
        }
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
//            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
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
//        } foreach ($sentboxArray as $sent) {
//            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
//            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
//                if (!($sentbox->setSentbox($this, 'DELETE'))) {
//                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
//                }
//            }
//        }
//    }
    public function getProductBatchList($type, $code, $product, $check_is_mcc = false) {
        $query = $this->find()->where([
                    'product_code' => $product])
                ->andWhere(['>', 'tbl_product_stock.stock', 0])
                ->andWhere(['!=', "ISNULL(tbl_product_stock.sap_batch_no, '')", '']);
        /* $isMcc = FALSE;
          if ($check_is_mcc && strtoupper($type) == 'BMC') {
          $this->setCodes(strtoupper($type), $code);
          $this->bmc_code = $code;
          $isMcc = Yii::$app->general->getforeignkey($this->bmcCode, 'is_mcc') == '1' ? TRUE : FALSE;
          $code = ($isMcc) ? $this->mcc_plant_code : $code;
          }
          if ((strtoupper($type) == 'DCS' || strtoupper($type) == 'VLC')) {
          $this->setCodes(strtoupper($type), $code);
          $isBmc = Yii::$app->general->getforeignkey($this->dcsCode, 'is_bmc');
          $isBmcMcc = Yii::$app->general->getforeignkey($this->bmcCode, 'is_mcc');
          $isMcc = ($isBmc == 1 && $isBmcMcc == 1) ? TRUE : FALSE;
          $code = ($isMcc) ? $this->mcc_plant_code : $code;
          } */
        $query->andWhere(['tbl_product_stock.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        if (strtoupper($type) == 'MCC') {
            $query->andWhere(['mcc_plant_code' => $code])
                    ->andWhere(['AND', ['is', 'bmc_code', NULL], ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'BMC') {
            $query->andWhere(['bmc_code' => $code])
                    ->andWhere(['AND', ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'DCS' || strtoupper($type) == 'VLC') {
            $query->andWhere(['dcs_code' => $code]);
        }
        $data = $query->orderBy(['created_at' => SORT_ASC])->all();
        if (!empty($data)) {
            $data = ArrayHelper::map($data, 'sap_batch_no', 'sap_batch_no');
        }
        return $data;
    }

    public function getProductBatchListLastSixMonth($type, $code, $product, $check_is_mcc = false) {
        $query = $this->find()->where([
                    'product_code' => $product])
                ->andWhere(['>', 'tbl_product_stock.stock', 0])
                ->andWhere(['!=', "ISNULL(tbl_product_stock.sap_batch_no, '')", '']);
        /*  $isMcc = FALSE;
          if ($check_is_mcc && strtoupper($type) == 'BMC') {
          $this->setCodes(strtoupper($type), $code);
          $this->bmc_code = $code;
          $isMcc = Yii::$app->general->getforeignkey($this->bmcCode, 'is_mcc') == '1' ? TRUE : FALSE;
          $code = ($isMcc) ? $this->mcc_plant_code : $code;
          } */
        $query->andWhere(['tbl_product_stock.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        if (strtoupper($type) == 'MCC') {
            $query->andWhere(['mcc_plant_code' => $code])
                    ->andWhere(['AND', ['is', 'bmc_code', NULL], ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'BMC') {
            $query->andWhere(['bmc_code' => $code])
                    ->andWhere(['AND', ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'DCS' || strtoupper($type) == 'VLC') {
            $query->andWhere(['dcs_code' => $code]);
        }
//        $six_month_ago_date = date("Y-m-d", strtotime(date('Y-m-01') . " -6 months"));
//        $current_date = date("Y-m-d");
//        $query->andWhere(['>=', 'cast(created_at as date)', $six_month_ago_date]);
//        $query->andWhere(['<=', 'cast(created_at as date)', $current_date]);
        $data = $query->orderBy(['created_at' => SORT_ASC])->all();
        if (!empty($data)) {
            $data = ArrayHelper::map($data, 'sap_batch_no', 'sap_batch_no');
        }
        return $data;
    }

    public function getAvailableStock($type, $batch = '', $checkMccStock = false, $productStockCode = '') {
        $query = $this->find()->where(['union_code' => $this->union_code, 'mcc_plant_code' => $this->mcc_plant_code, 'product_code' => $this->product_code])
                ->andWhere(['>', 'stock', 0]);
        if (!empty($batch)) {
            $query->andWhere(['sap_batch_no' => $batch]);
        }
        
        if (!empty($productStockCode)) {
            $query->andWhere(['not in', 'product_stock_code', $productStockCode]);
        }
        
        if (strtoupper($type) == 'MCC') {
            $query->andWhere(['AND', ['is', 'bmc_code', NULL], ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'BMC') {
            $query->andWhere(['bmc_code' => $this->bmc_code])
                    ->andWhere(['AND', ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'DCS' || strtoupper($type) == 'VLC') {
            $query->andWhere(['bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code]);
        }
        return $query->orderBy(['created_at' => SORT_ASC])->all();
    }

    public function getExistStockDelete($type, $batch = '', $checkMccStock = false) {
        $query = $this->find()->where(['union_code' => $this->union_code, 'mcc_plant_code' => $this->mcc_plant_code, 'product_code' => $this->product_code]);
        $query->andWhere(["ISNULL(sap_batch_no,'')" => empty($batch) ? '' : $batch]);
        if (strtoupper($type) == 'MCC') {
            $query->andWhere(['AND', ['is', 'bmc_code', NULL], ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'BMC') {
            $query->andWhere(['bmc_code' => $this->bmc_code])
                    ->andWhere(['AND', ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'DCS' || strtoupper($type) == 'VLC') {
            $query->andWhere(['bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code]);
        }
        return $query->orderBy(['created_at' => SORT_DESC])->one();
    }

    public function getTotalAvailableStock() {
        return TblProductStock::find()->where(['union_code' => $this->union_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code, 'product_code' => $this->product_code])->andWhere(['AND', ['is', 'dcs_code', NULL]])->andWhere(['>', 'stock', 0])->sum('stock');
    }

}
