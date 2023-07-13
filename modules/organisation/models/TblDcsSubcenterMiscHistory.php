<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\usermanagement\models\User;
/**
 * This is the model class for table "tbl_dcs_subcenter_misc_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $dcs_miscellaneous_code
 * @property string $description
 * @property string $local_description
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $dcs_code
 * @property integer $miscellaneous_code
 * @property string $subcenter_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblMiscellaneous $miscellaneousCode
 * @property TblSubCenter $subcenterCode
 * @property User $updatedBy
 */
class TblDcsSubcenterMiscHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_subcenter_misc_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by','updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['description', 'miscellaneous_code','dcs_miscellaneous_code','dcs_code', 'subcenter_code'], 'safe'],
            [['is_active','created_at', 'history_created_at',  'updated_at'], 'safe'],
//            [['description'], 'required'],
//            [[ 'miscellaneous_code'], 'integer'],
//            [['dcs_miscellaneous_code'], 'string', 'max' => 255],
//            [['description'], 'string', 'max' => 500],
//            [['operation_type'], 'string', 'max' => 10],
//            [['created_by', 'updated_by'], 'string', 'max' => 14],
//            [[], 'string', 'max' => 9],
//            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
//            [['miscellaneous_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMiscellaneous::className(), 'targetAttribute' => ['miscellaneous_code' => 'miscellaneous_code']],
//            [['subcenter_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['subcenter_code' => 'sub_center_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            /*'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'dcs_miscellaneous_code' => Yii::t('app', 'Dcs Miscellaneous Code'),
            'description' => Yii::t('app', 'Description'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'miscellaneous_code' => Yii::t('app', 'Miscellaneous Code'),
            'subcenter_code' => Yii::t('app', 'Subcenter Code'),
            'updated_by' => Yii::t('app', 'Updated By'),*/
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::className());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiscellaneousCode()
    {
        return $this->hasOne(TblMiscellaneous::className(), ['miscellaneous_code' => 'miscellaneous_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubcenterCode()
    {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'subcenter_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblDcsSubcenterMiscHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDcsSubcenterMiscHistoryQuery(get_called_class());
    }
}
