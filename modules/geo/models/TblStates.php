<?php

namespace app\modules\geo\models;

use Yii;
use app\modules\organisation\models\TblFederations;
use app\modules\organisation\models\TblUnions;
use app\modules\usermanagement\models\User;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_states".
 *
 * @property string $state_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $state_name
 * @property string $local_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblDcs[] $tblDcs
 * @property TblDcsHistory[] $tblDcsHistories
 * @property TblDistricts[] $tblDistricts
 * @property TblDistrictsHistory[] $tblDistrictsHistories
 * @property TblFederationState[] $tblFederationStates
 * @property TblFederations[] $federationCodes
 * @property TblFederationStateHistory[] $tblFederationStateHistories
 * @property TblFederations[] $tblFederations
 * @property TblFederationsHistory[] $tblFederationsHistories
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblLanguages $languageCode
 * @property User $updatedBy
   * @property TblSubCenter[] $tblSubCenters
 * @property TblSubCenterHistory[] $tblSubCenterHistories
 * @property TblUnions[] $tblUnions
 * @property TblUnionsHistory[] $tblUnionsHistories
 */
class TblStates extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_states';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_code', 'state_name'], 'required'],
            [['state_code','state_name'], 'unique'],
            ['state_code', 'compare', 'compareValue' => '00', 'operator' => '!=', 'type' => 'number','message'=> Yii::t('app/validation', '{attribute} can not be "00".')],
            [['created_at', 'updated_at','param','is_active'], 'safe'],
            [['state_code'], 'integer','message'=> Yii::t('app/validation', '{attribute} must be a digit. e.g. "01"')],
            [['state_code'], 'string', 'max' => 2,'min'=>2,'tooLong' =>  Yii::t('app/validation', '{attribute} must be of  2 digit. e.g."01"'),
            'tooShort' => Yii::t('app/validation', '{attribute} must be of  2 digit. e.g."01"')],
            [['state_name'], 'string', 'max' => 100],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            /* alphbet validation*/
            //[['state_name'], 'match', 'pattern' => '/^[a-zA-Z\/]*$/'],
            [['state_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute,$params);
                },'skipOnEmpty'=> false],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'state_code' => Yii::t('app', 'State Code'),
            'local_name' => Yii::t('app', 'Local Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'state_name' => Yii::t('app', 'State Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

     public function getTblDcs()
    {
        return $this->hasMany(TblDcs::className(), ['state_code' => 'state_code']);
    }
     public function getTblDcsHistories()
    {
        return $this->hasMany(TblDcsHistory::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDistricts()
    {
        return $this->hasMany(TblDistricts::className(), ['state_code' => 'state_code']);
    }
     public function getTblDistrictsHistories()
    {
        return $this->hasMany(TblDistrictsHistory::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

     public function getTblFederationStates()
    {
        return $this->hasMany(TblFederationState::className(), ['state_code' => 'state_code']);
    }
    public function getTblFederations()
    {
        return $this->hasMany(TblFederations::className(), ['state_code' => 'state_code']);
    }
      public function getFederationCodes()
    {
        return $this->hasMany(TblFederations::className(), ['federation_code' => 'federation_code'])->viaTable('{{%tbl_federation_state}}', ['state_code' => 'state_code']);
    }
    public function getTblFederationStateHistories()
    {
        return $this->hasMany(TblFederationStateHistory::className(), ['state_code' => 'state_code']);
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
    public function getDeletedBy()
    {
        return $this->hasOne(User::className());
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
    public function getTblSubCenters()
    {
        return $this->hasMany(TblSubCenter::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubCenterHistories()
    {
        return $this->hasMany(TblSubCenterHistory::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnions()
    {
        return $this->hasMany(TblUnions::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnionsHistories()
    {
        return $this->hasMany(TblUnionsHistory::className(), ['state_code' => 'state_code']);
    }

    /**
     * @inheritdoc
     * @return TblStatesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblStatesQuery(get_called_class());
    }

    public function getActiveStates($state_code=''){
            $selected = '';
            if (!empty(Yii::$app->session->get('States'))) {
                $selected = explode(',', Yii::$app->session->get('States'));
            }
            if(!empty($state_code)){
                $subQuery=$this->find()->select(['state_code','state_name','local_name'])->where(['state_code'=>$state_code]);
                $array= $this->find()->select(['state_code','state_name','local_name'])->where(['state_code'=>$selected, 'is_active'=>1])->union($subQuery)->all();
            }else{
                $array= $this->find()->select(['state_code','state_name','local_name'])->where(['state_code'=>$selected, 'is_active'=>1])->all();                
            }
            $data = \yii\helpers\ArrayHelper::map($array, 'state_code',function($array, $key) {
                    if (!empty($array['local_name']))
                        return $array['state_name'] . '(' . $array['local_name'] . ')';
                    else
                        return $array['state_name'];
                });
         asort($data,SORT_NATURAL | SORT_FLAG_CASE);
         return $data;
     }
}
