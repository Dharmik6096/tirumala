<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_milk_type_history".
 *
 * @property integer $id
 * @property string $bmc_code
 * @property integer $milk_type_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblBmcMilkTypeHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_milk_type_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_code', 'created_by', 'updated_by', 'operation_type'], 'safe'],
            [['milk_type_code', 'is_active'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bmc_code' => Yii::t('app', 'Mcc Plant Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
