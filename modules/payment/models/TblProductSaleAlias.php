<?php

namespace app\modules\payment\models;

use app\models\ChildModel;
use Yii;
use yii\base\UserException;

/**
 * This is the model class for table "tbl_product_sale_alias".
 *
 * @property integer $product_sale_alias_code
 * @property string $action_perform
 * @property string $product_sale_code
 * @property string $ref_product_sale_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $error_desc
 * @property string $approved_at
 * @property string $approved_by
 * @property string $approval_status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblProductSaleAlias extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_sale_alias';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['action_perform', 'product_sale_code', 'ref_product_sale_code', 'customer_type', 'customer_code', 'error_desc', 'approved_at', 'approved_by', 'approval_status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_sale_alias_code' => Yii::t('app', 'Product Sale Alias Code'),
            'action_perform' => Yii::t('app', 'Action Perform'),
            'product_sale_code' => Yii::t('app', 'Product Sale Code'),
            'ref_product_sale_code' => Yii::t('app', 'Ref Product Sale Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'error_desc' => Yii::t('app', 'Error Desc'),
            'approved_at' => Yii::t('app', 'Approved At'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'approval_status' => Yii::t('app', 'Approval Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getProductSaleDetails() {
        return $this->hasMany(TblProductSale::className(), ['product_sale_code' => 'product_sale_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        if (in_array($this->originating_org_type, ['VLC', 'BMC']) && in_array($this->originating_type, ['23', '24'])) {
            $sentboxArray = [];
            $productSaleDetailsData = $this->ProductSaleDetails;
            if (!empty($productSaleDetailsData)) {
                if (!empty($productSaleDetailsData->customer_code)) {
                    $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $productSaleDetailsData->customer_code);
                } else if (!empty($productSaleDetailsData->bmc_code)) {
                    $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $productSaleDetailsData->bmc_code, '', '');
                } else if (!empty($productSaleDetailsData->mcc_plant_code)) {
                    $sentboxArray = Yii::$app->general->getSentBoxCodes('', $productSaleDetailsData->mcc_plant_code, '', '', '');
                }
            }
            foreach ($sentboxArray as $sent) {
                $flag = ((isset($this->operation) && $this->operation == true) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
                $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    if (!($sentbox->setSentbox($this, $flag))) {
                        throw new UserException("SentBox Entry is not created so transaction is rollback!");
                    }
                }
            }
        }
    }

}
