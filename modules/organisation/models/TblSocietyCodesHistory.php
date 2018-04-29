<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_society_codes_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $union_code
 * @property string $pooling_point_code
 * @property string $imei_no
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $bipl_code
 * @property string $route_code
 */
class TblSocietyCodesHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_society_codes_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at', 'created_at', 'updated_at'], 'safe'],
            [['operation_type', 'dcs_code', 'bmc_code', 'union_code', 'pooling_point_code', 'imei_no', 'created_by', 'updated_by', 'bipl_code', 'route_code'], 'safe'],
            [['code'], 'safe'],
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
            'code' => Yii::t('app', 'Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'pooling_point_code' => Yii::t('app', 'Pooling Point Code'),
            'imei_no' => Yii::t('app', 'Imei No'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'bipl_code' => Yii::t('app', 'Bipl Code'),
            'route_code' => Yii::t('app', 'Route Code'),
        ];
    }
}
