<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_election_history".
 *
 * @property integer $id
 * @property integer $election_id
 * @property string $dcs_code
 * @property string $election_date
 * @property string $tenure_from
 * @property string $tenure_to
 * @property string $remarks
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 */
class TblDcsElectionHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_election_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['election_id', 'is_active', 'is_delete'], 'integer'],
            [['dcs_code', 'remarks', 'created_by', 'updated_by', 'operation_type'], 'string'],
            [['election_date', 'tenure_from', 'tenure_to', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'election_id' => Yii::t('app', 'Election ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'election_date' => Yii::t('app', 'Election Date'),
            'tenure_from' => Yii::t('app', 'Tenure From'),
            'tenure_to' => Yii::t('app', 'Tenure To'),
            'remarks' => Yii::t('app', 'Remarks'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsElectionHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDcsElectionHistoryQuery(get_called_class());
    }
}
