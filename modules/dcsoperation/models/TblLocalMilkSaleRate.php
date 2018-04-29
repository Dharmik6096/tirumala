<?php

namespace app\modules\dcsoperation\models;

use Yii;
use webvimark\modules\UserManagement\models\User;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMilkClass;

/**
 * This is the model class for table "tbl_local_milk_sale_rate".
 *
 * @property string $local_sale_rate_code
 * @property string $created_at
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property integer $is_active
 * @property integer $is_delete
 * @property double $rate
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property integer $milk_type
 * @property string $sub_center_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblAnimalType $milkType
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblLocalMilkSaleRate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_local_milk_sale_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['local_sale_rate_code', 'milk_type'], 'required'],
            [['created_at', 'deleted_at', 'updated_at', 'wef_date'], 'safe'],
            [['is_active', 'is_delete', 'milk_type'], 'integer'],
            [['rate'], 'number'],
            [['local_sale_rate_code'], 'string', 'max' => 20],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code', 'sub_center_code'], 'string', 'max' => 9],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['milk_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type' => 'animal_type_code']],
            [['sub_center_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['sub_center_code' => 'sub_center_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'local_sale_rate_code' => Yii::t('app', 'Local Sale Rate Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'rate' => Yii::t('app', 'Rate'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'milk_type' => Yii::t('app', 'Milk Type'),
            'sub_center_code' => Yii::t('app', 'Sub Center Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
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
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkType()
    {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkClass()
    {
        return $this->hasOne(TblMilkClass::className(), ['id' => 'milk_class']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubCenterCode()
    {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'sub_center_code']);
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
     * @return TblLocalMilkSaleRateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblLocalMilkSaleRateQuery(get_called_class());
    }
}
