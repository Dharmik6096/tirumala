<?php

namespace app\modules\setting\models;

use Yii;
use app\modules\usermanagement\models\User;
/**
 * This is the model class for table "tbl_frm_objects".
 *
 * @property integer $id
 * @property string $created_at
 * @property integer $is_active
 * @property string $object_name
 * @property string $updated_at
 * @property string $created_by
 * @property integer $module_id
 * @property string $updated_by
 *
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblModules $module
 * @property TblRoles[] $tblRoles
 */
class TblFrmObjects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_frm_objects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['is_active', 'module_id'], 'integer'],
            [['object_name'], 'required'],
            [['object_name'], 'string', 'max' => 100],
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
            'is_active' => Yii::t('app', 'Is Active'),
            'object_name' => Yii::t('app', 'Object Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'module_id' => Yii::t('app', 'Module ID'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getUpdatedBy()
//    {
//        return $this->hasOne(User::className(), ['user_id' => 'updated_by']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getCreatedBy()
//    {
//        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(TblModules::className(), ['id' => 'module_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoles()
    {
        return $this->hasMany(TblRoles::className(), ['frm_object_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return TblFrmObjectsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblFrmObjectsQuery(get_called_class());
    }
}
