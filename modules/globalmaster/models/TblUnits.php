<?php

namespace app\modules\globalmaster\models;

use Yii;
use app\models\ChildModel;
/**
 * This is the model class for table "tbl_units".
 *
 * @property integer $unit_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $unit_name
 * @property string $local_name
 * @property string $short_name
 * @property string $local_short_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
  */
class TblUnits extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_units';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at','short_name'], 'safe'],
            [['is_active'], 'safe'],
            [['unit_name'], 'required'],
            [['unit_name','short_name'], 'unique'],
            [['unit_name','short_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['unit_name'], 'string', 'max' => 20],
            [['local_name','local_short_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
       //     [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
        //    [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unit_code' => Yii::t('app', 'Unit Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'unit_name' => Yii::t('app', 'Unit Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'short_name' => Yii::t('app', 'Short Name'),
            'local_short_name' => Yii::t('app', 'Local Short Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
/*    public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }
*/
    /**
     * @return \yii\db\ActiveQuery
     */
 /*   public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }
*/
    /**
     * @return \yii\db\ActiveQuery
     */
 /*   public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className());
    }
*/
      /**
     * @inheritdoc
     * @return TblUnitsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblUnitsQuery(get_called_class());
    }
}
