<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_generate_sentbox".
 *
 * @property integer $id
 * @property string $table_name
 * @property string $where_clause
 * @property string $operation_type
 * @property string $sentbox_key
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property integer $status
 * @property string $entry_datetime
 * @property string $picked_datetime
 * @property string $response_datetime
 * @property string $model_name
 */
class TblGenerateSentbox extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_generate_sentbox';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['table_name', 'where_clause', 'operation_type', 'sentbox_key', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'string'],
                [['status'], 'integer'],
                [['entry_datetime', 'picked_datetime', 'response_datetime', 'model_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'table_name' => Yii::t('app', 'Table Name'),
            'where_clause' => Yii::t('app', 'Where Clause'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'sentbox_key' => Yii::t('app', 'Sentbox Key'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'status' => Yii::t('app', 'Status'),
            'entry_datetime' => Yii::t('app', 'Entry Datetime'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'model_name' => Yii::t('app', 'Model Name'),
        ];
    }

    public function getData() {
        return $this->find()
                        ->where(['or', ['status' => 0], ['status' => NULL]])
                        ->limit(50)
                        ->orderby('id ASC')
                        ->all();
    }

}
