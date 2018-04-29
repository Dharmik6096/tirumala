<?php

namespace app\modules\complain\models;

use Yii;

/**
 * This is the model class for table "tbl_complain_history".
 *
 * @property integer $id
 * @property integer $complain_code
 * @property string $union_code
 * @property string $dcs_code
 * @property string $date
 * @property string $remarks
 * @property string $complain_type
 * @property string $status
 * @property string $resolve_date
 * @property string $resolve_remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $delete_at
 * @property string $delete_by
 * @property integer $is_active
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblComplaintHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_complain_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['complain_code', 'is_active'], 'integer'],
            [['union_code', 'dcs_code', 'remarks', 'complain_type', 'status', 'resolve_remarks', 'created_by', 'updated_by', 'delete_by', 'operation_type'], 'string'],
            [['date', 'resolve_date', 'created_at', 'updated_at', 'delete_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'complain_code' => Yii::t('app', 'Complain Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'date' => Yii::t('app', 'Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'complain_type' => Yii::t('app', 'Complain Type'),
            'status' => Yii::t('app', 'Status'),
            'resolve_date' => Yii::t('app', 'Resolve Date'),
            'resolve_remarks' => Yii::t('app', 'Resolve Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'delete_at' => Yii::t('app', 'Delete At'),
            'delete_by' => Yii::t('app', 'Delete By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
