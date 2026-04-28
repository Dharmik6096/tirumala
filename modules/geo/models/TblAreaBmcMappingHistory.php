<?php

namespace app\modules\geo\models;

use Yii;

/**
 * This is the model class for table "tbl_area_bmc_mapping_history".
 *
 * @property integer $id
 * @property integer $bmc_mapping_code
 * @property string $bmc_code
 * @property string $p_bmc_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_by
 * @property string $history_created_at
 */
class TblAreaBmcMappingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_area_bmc_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['area_bmc_mapping_code', 'is_active'], 'safe'],
            [['area_code', 'bmc_code', 'created_by', 'operation_type', 'updated_by', 'history_created_by', 'applicable_type', 'applicable_code'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'area_bmc_mapping_code' => Yii::t('app', 'Area Bmc Mapping Code'),
            'area_code' => Yii::t('app', 'Area Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
        ];
    }

}
