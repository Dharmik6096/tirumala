<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\usermanagement\models\User;
use app\modules\globalmaster\models\TblMiscellaneous;

/**
 * This is the model class for table "tbl_dcs_subcenter_misc".
 *
 * @property string $dcs_miscellaneous_code
 * @property string $created_at
 * @property string $description
 * @property string $local_description
 * @property integer $is_active
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
class TblDcsSubcenterMisc extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_subcenter_misc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_miscellaneous_code', 'description','miscellaneous_code'], 'required'],
            [['is_active', 'created_at', 'updated_at'], 'safe'],
            [[ 'miscellaneous_code'], 'integer'],
            [['description'], 'string', 'max' => 500],
            [['local_description'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code', 'subcenter_code'], 'string', 'max' => 9],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['miscellaneous_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMiscellaneous::className(), 'targetAttribute' => ['miscellaneous_code' => 'miscellaneous_code']],
            [['subcenter_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['subcenter_code' => 'sub_center_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
       return [
            'dcs_miscellaneous_code' => Yii::t('app','Dcs Miscellaneous Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'description' => Yii::t('app', 'Description'),
            'local_description' => Yii::t('app', 'Local Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'miscellaneous_code' => Yii::t('app', 'Miscellaneous'),
            'subcenter_code' => Yii::t('app', 'Subcenter'),
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
     * @return TblDcsSubcenterMiscQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDcsSubcenterMiscQuery(get_called_class());
    }

    public function getCode($code){     
        $data=  $this->find()->select(["convert(int,MAX(substring(dcs_miscellaneous_code,8,3))) as dcs_miscellaneous_code"])->where(['dcs_code'=>$code])->one();      
        $new_code = (int) $data['dcs_miscellaneous_code'] + 1;         
        return $code.str_pad($new_code, 3, '0', STR_PAD_LEFT);
    }
}
