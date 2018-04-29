<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
/**
 * This is the model class for table "tbl_member_classification".
 *
 * @property string $member_classification_code
 * @property string $created_at
 * @property string $flg_sentbox_entry
 * @property integer $is_active
 * @property string $member_classification_name
 * @property string $local_name
 * @property double $range_from
 * @property double $range_to
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $created_by
 * @property integer $member_classification_type
 * @property string $union_code
 * @property string $updated_by
 *
 * @property TblMemberClassificationType $memberClassificationTypeCode
 * @property TblUnions $unionCode
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblMemberClassificationType $memberClassificationTypeCode0
 * @property TblUnions $unionCode0
 * @property User $updatedBy
 * @property TblMemberClassificationLocal[] $tblMemberClassificationLocals
 * @property TblMemberClassificationLocalHistory[] $tblMemberClassificationLocalHistories
 * @property TblMemberInformation[] $tblMemberInformations
 * @property TblMemberInformation[] $tblMemberInformations0
 * @property TblMemberInformationHistory[] $tblMemberInformationHistories
 * @property TblMemberInformationHistory[] $tblMemberInformationHistories0
 */
class TblMemberClassification extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_member_classification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'member_classification_name', 'member_classification_type', 'range_from', 'range_to'], 'required'],
            [['member_classification_name'], 'getMemberClassificationCode' ,'on'=>'importCsv'],
            [['member_classification_code'], 'required' ,'except'=>'importCsv'],
            [['created_at', 'updated_at','is_active', 'local_name'], 'safe'],
            [['range_from', 'range_to'], 'number', 'min' => 0],
            [['range_to'], 'customValidate'],
            [['member_classification_name'],'unique', 'when' => function($model) {
                $validate = Yii::$app->general->valiadteUnique($model, 'member_classification_name', $this->member_classification_name);
                    if($validate==0){return true;}return false;
            },'skipOnEmpty'=> true],
            [['member_classification_code'], 'string', 'max' => 11],
            [['member_classification_name'], 'string', 'max' => 25],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['member_classification_name'], function ($attribute, $params) {
                Yii::$app->general->validateName($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['union_code'], 'string', 'max' => 3],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }
    
    public function customValidate($attribute, $params) {

        if (!empty($this->range_from) && !empty($this->range_to)) {

            if($this->range_to < $this->range_from){
                $this->addError($attribute, Yii::t('app/validation','To Rang cannot be less then From Range.'));
                return false;
            }else if($this->range_to == $this->range_from){
                $this->addError($attribute, Yii::t('app/validation','To Rang and From Range cannot be same.'));
                return false;
            }
        }
    }
    
    public function getMemberClassificationCode($attribute, $params){
        if (!empty($this->member_classification_name)) {
            $this->member_classification_code = $this->getCode();
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_classification_code' => Yii::t('app', 'Member Classification Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'member_classification_name' => Yii::t('app', 'Member Classification Name'),
            'local_name'=> Yii::t('app', 'Local Name'),
            'range_from' => Yii::t('app', 'Range From'),
            'range_to' => Yii::t('app', 'Range To'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'member_classification_type' => Yii::t('app', 'Member Classification Type'),
            'union_code' => Yii::t('app', 'Union'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
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
    public function getMemberClassificationTypeCode0()
    {
        return $this->hasOne(TblMemberClassificationType::className(), ['member_classification_type' => 'member_classification_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode0()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberClassificationLocals()
    {
        return $this->hasMany(TblMemberClassificationLocal::className(), ['member_classification_code' => 'member_classification_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberClassificationLocalHistories()
    {
        return $this->hasMany(TblMemberClassificationLocalHistory::className(), ['member_classification_code' => 'member_classification_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformations()
    {
        return $this->hasMany(TblMemberInformation::className(), ['farmer_type_animal_id' => 'member_classification_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformations0()
    {
        return $this->hasMany(TblMemberInformation::className(), ['farmer_type_land_id' => 'member_classification_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformationHistories()
    {
        return $this->hasMany(TblMemberInformationHistory::className(), ['farmer_type_animal_id' => 'member_classification_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformationHistories0()
    {
        return $this->hasMany(TblMemberInformationHistory::className(), ['farmer_type_land_id' => 'member_classification_code']);
    }

    /**
     * @inheritdoc
     * @return TblMemberClassificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMemberClassificationQuery(get_called_class());
    }
    
    public function getCode(){
        $data=  $this->find()->select(["convert(int,MAX(member_classification_code)) as member_classification_code"])->one();
        return str_pad((int)$data['member_classification_code']+1,4,'0',STR_PAD_LEFT);
    }
}
