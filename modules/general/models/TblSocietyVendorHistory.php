<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_society_vendor_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $society_vendor_code
 * @property string $dcs_code
 * @property string $vendor_code
 */
class TblSocietyVendorHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_society_vendor_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at'], 'safe'],
            [['operation_type', 'dcs_code', 'vendor_code'], 'safe'],
            [['society_vendor_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'society_vendor_code' => Yii::t('app', 'Society Vendor Code'),
            'dcs_code' => Yii::t('app', 'Society Code'),
            'vendor_code' => Yii::t('app', 'Vender Code'),
        ];
    }
}
