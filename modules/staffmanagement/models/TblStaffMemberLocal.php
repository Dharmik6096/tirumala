<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_member_local".
 *
 * @property string $id
 * @property string $created_at
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $local_address
 * @property string $local_name
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property integer $language_id
 * @property string $staffmember_code
 * @property string $updated_by
 *
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 * @property TblLanguages $language
 * @property TblStaffMember $staffmemberCode
 * @property TblUsers $updatedBy
 */
class TblStaffMemberLocal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_member_local';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'deleted_at', 'sync_timestamp', 'updated_at'], 'safe'],
            [['is_active', 'is_delete', 'language_id'], 'integer'],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
            [['local_address'], 'string', 'max' => 500],
            [['local_name'], 'string', 'max' => 200],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 10],
            [['staffmember_code'], 'string', 'max' => 20],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['deleted_by' => 'user_id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => TblLanguages::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['staffmember_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStaffMember::className(), 'targetAttribute' => ['staffmember_code' => 'staff_member_code']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'local_address' => Yii::t('app', 'Local Address'),
            'local_name' => Yii::t('app', 'Local Name'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'language_id' => Yii::t('app', 'Language ID'),
            'staffmember_code' => Yii::t('app', 'Staffmember Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(TblLanguages::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffmemberCode()
    {
        return $this->hasOne(TblStaffMember::className(), ['staff_member_code' => 'staffmember_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblStaffMemberLocalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblStaffMemberLocalQuery(get_called_class());
    }
}
