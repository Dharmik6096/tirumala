<?php

namespace app\modules\creamy\models;

use Yii;

/**
 * This is the model class for table "tbl_dpu_product_demand".
 *
 * @property integer $Id
 * @property string $trDate
 * @property string $shift
 * @property string $BMCCode
 * @property string $VillageCode
 * @property string $MemberCode
 * @property string $ProductId
 * @property string $PPrice
 * @property string $PQty
 * @property string $PAmount
 * @property integer $Status
 * @property string $ApprovedDate
 * @property string $CreateOnUtc
 * @property string $CreatedBy
 * @property string $UpdateOnUtc
 * @property string $UpdatedBy
 * @property string $ProductStatus
 * @property integer $data_post_status
 */
class TblDpuProductDemandCreamy extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    
    public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }
    
    public static function tableName()
    {
        return 'tbl_dpu_product_demand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trDate', 'shift', 'BMCCode', 'VillageCode', 'MemberCode', 'ProductId', 'PPrice', 'PQty', 'PAmount', 'CreateOnUtc', 'CreatedBy'], 'required'],
            [['trDate', 'ApprovedDate', 'CreateOnUtc', 'UpdateOnUtc'], 'safe'],
            [['shift', 'BMCCode', 'VillageCode', 'MemberCode', 'ProductId', 'CreatedBy', 'UpdatedBy', 'ProductStatus'], 'string'],
            [['PPrice', 'PQty', 'PAmount'], 'number'],
            [['Status', 'data_post_status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'trDate' => 'Tr Date',
            'shift' => 'Shift',
            'BMCCode' => 'Bmccode',
            'VillageCode' => 'Village Code',
            'MemberCode' => 'Member Code',
            'ProductId' => 'Product ID',
            'PPrice' => 'Pprice',
            'PQty' => 'Pqty',
            'PAmount' => 'Pamount',
            'Status' => 'Status',
            'ApprovedDate' => 'Approved Date',
            'CreateOnUtc' => 'Create On Utc',
            'CreatedBy' => 'Created By',
            'UpdateOnUtc' => 'Update On Utc',
            'UpdatedBy' => 'Updated By',
            'ProductStatus' => 'Product Status',
            'data_post_status' => 'Data Post Status',
        ];
    }
}
