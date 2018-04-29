<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_qualification".
 *
 * @property integer $qualification_code
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $qualification_name
 * @property integer $sequences_no
 * @property string $updated_at
 * @property string $updated_by
 */
class TblQualification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_qualification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'qualification_name', 'updated_by'], 'string'],
            [['is_active', 'sequences_no'], 'integer'],
            [['qualification_name'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'qualification_code' => Yii::t('app', 'Qualification Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'qualification_name' => Yii::t('app', 'Qualification Name'),
            'sequences_no' => Yii::t('app', 'Sequences No'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblQualificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblQualificationQuery(get_called_class());
    }
    
    public function getQualification($qualification_code){
        return $this->find()->where(['qualification_code'=>$qualification_code])->count();       
    }    
}
