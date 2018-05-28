<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_tab_local_sale".
 *
 * @property integer $Id
 * @property string $parentId
 * @property string $stationId
 * @property string $date
 * @property string $shift
 * @property string $milkType
 * @property string $quantity
 * @property string $quantityMode
 * @property string $rate
 * @property string $amount
 * @property string $createOnUtc
 */
class TblTabLocalSale extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_tab_local_sale';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['parentId', 'stationId', 'shift', 'milkType', 'quantityMode'], 'string'],
            [['date', 'createOnUtc'], 'safe'],
            [['quantity', 'rate', 'amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'Id' => Yii::t('app', 'ID'),
            'parentId' => Yii::t('app', 'Parent ID'),
            'stationId' => Yii::t('app', 'DCS Name'),
            'date' => Yii::t('app', 'Date'),
            'shift' => Yii::t('app', 'Shift'),
            'milkType' => Yii::t('app', 'Milk Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'quantityMode' => Yii::t('app', 'Quantity Mode'),
            'rate' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount'),
            'createOnUtc' => Yii::t('app', 'Create On Utc'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'stationId']);
    }

}
