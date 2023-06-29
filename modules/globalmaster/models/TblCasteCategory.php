<?php
namespace app\modules\globalmaster\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_caste_category".
 *
 * @property integer $caste_category_code
 * @property string $caste_category_name
 * @property string $local_name
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 */
class TblCasteCategory extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_caste_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['caste_category_name'], 'required'],
            [['caste_category_name'], 'unique'],
            [['caste_category_name'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'safe'],
            [['caste_category_name'], 'string', 'max' => 100],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
//            [['dcs_type_name'], function ($attribute, $params) {
//                    Yii::$app->general->validateAlphaNumber($this, $attribute,$params);
//                },'skipOnEmpty'=> false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
          //  [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
          //  [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'caste_category_code' => Yii::t('app', 'Caste Category Code'),
            'caste_category_name' => Yii::t('app', 'Caste Category Name'),
            'local_name' => Yii::t('app', 'Local Name'),
           /* 'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),*/
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
 /*   public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }
*/
    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }
*/
    /**
     * @return \yii\db\ActiveQuery
     */
/*    public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className());
    }
*/
     /**
     * @inheritdoc
     * @return TblCasteCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblCasteCategoryQuery(get_called_class());
    }    
    
    public function getCasteCategory($cast_category_code){
        return $this->find()->where(['cast_category_code'=>$cast_category_code])->count();       
    } 
}
