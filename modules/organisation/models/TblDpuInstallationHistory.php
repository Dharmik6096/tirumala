<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_dpu_installation_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $inst_code
 * @property string $inst_date
 * @property string $inst_by
 * @property string $remarks
 * @property string $attachment
 * @property string $dcs_code
 * @property string $union_code
 * @property string $soc_secretary
 * @property string $secretary_mobile
 * @property string $simcard_company
 * @property string $dpu_sim_mobile
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblDpuInstallationHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dpu_installation_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at', 'inst_date', 'created_at', 'updated_at'], 'safe'],
            [['operation_type', 'inst_by', 'remarks', 'attachment', 'dcs_code', 'union_code', 'soc_secretary', 'secretary_mobile', 'simcard_company', 'dpu_sim_mobile', 'created_by', 'updated_by'], 'string'],
            [['inst_code', 'is_active'], 'integer'],
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
            'inst_code' => Yii::t('app', 'Inst Code'),
            'inst_date' => Yii::t('app', 'Inst Date'),
            'inst_by' => Yii::t('app', 'Inst By'),
            'remarks' => Yii::t('app', 'Remarks'),
            'attachment' => Yii::t('app', 'Attachment'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'soc_secretary' => Yii::t('app', 'Soc Secretary'),
            'secretary_mobile' => Yii::t('app', 'Secretary Mobile'),
            'simcard_company' => Yii::t('app', 'Simcard Company'),
            'dpu_sim_mobile' => Yii::t('app', 'Dpu Sim Mobile'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
