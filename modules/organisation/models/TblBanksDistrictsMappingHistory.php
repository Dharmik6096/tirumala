<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_bank_district_history".
 *
 * @property integer $id
 * @property string $bank_code
 * @property string $district_code
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by

 */
class TblBanksDistrictsMappingHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bank_district_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by','updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['bank_code','district_code'], 'safe'],
//            [['bank_code', 'district_code'], 'required'],
//            [['bank_code'], 'integer'],
//            [['district_code'], 'string', 'max' => 3],
//            [['created_at' ,'is_active','updated_at', 'created_by','updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bank_code' => Yii::t('app', 'Bank Code'),
            'district_code' => Yii::t('app', 'District Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblBanksDistrictsMappingHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblBanksDistrictsMappingHistoryQuery(get_called_class());
    }
}
