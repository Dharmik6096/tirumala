<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_financial_year_history".
 *
 * @property integer $id
 * @property string $code
 * @property string $created_at
 * @property string $created_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $ending_date
 * @property string $flg_sentbox_entry
 * @property string $history_created_at
 * @property boolean $is_active
 * @property boolean $is_delete
 * @property string $operation_type
 * @property string $starting_date
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $updated_by
 */
class TblFinancialYearHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_financial_year_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code', 'created_at', 'created_by', 'ending_date', 'history_created_at', 'is_active', 'operation_type', 'starting_date', 'updated_at', 'updated_by', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'ending_date' => Yii::t('app', 'Ending Date'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'starting_date' => Yii::t('app', 'Starting Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

}
