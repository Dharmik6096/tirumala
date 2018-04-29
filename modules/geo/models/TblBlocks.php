<?php

namespace app\modules\geo\models;

use Yii;
use app\modules\organisation\models\TblUnionsDistrictMapping;
use app\models\ChildModel;
use app\modules\geo\models\TblSubDistricts;

/**
 * This is the model class for table "tbl_blocks".
 *
 * @property string $block_code
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 * @property string $block_name
 * @property string $sub_district_code
 * @property string $local_name
 */
class TblBlocks extends ChildModel
{
    /**
     * @inheritdoc
     */
    public $state;
    public $district;
    public $district_name;
    public $sub_district_name;
    public $param = 'block';
    public static function tableName()
    {
        return 'tbl_blocks';
    }

    /**
     * @inheritdoc
     */

    public function rules() {
        return [
            [['block_code', 'block_name'], 'required', 'message' => Yii::t('app/validation', '{attribute} cannot be blank.')],
            [['state', 'district','sub_district_code'], 'required', 'message' => Yii::t('app/validation', '{attribute} cannot be blank.'),'on'=>'add'],
            [['block_code'], 'unique'],
            ['block_code', 'compare', 'compareValue' => '000000', 'operator' => '!=', 'type' => 'number','message'=> Yii::t('app/validation', '{attribute} can not be "000000".')],
            [['created_at', 'updated_at', 'state', 'district', 'sub_district_code','is_active'], 'safe'],
            [['block_code'], 'integer','message'=> Yii::t('app/validation', '{attribute} must be a digit. e.g. "000001"')],
            [['block_name'], 'string', 'max' => 100],
            //[['block_name'], 'match', 'pattern' => '/^[a-zA-Z\/]*$/'],
            [['block_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['sub_district_code'], 'string', 'max' => 5],
//            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_code']],
//            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_code']],
            [['sub_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubDistricts::className(), 'targetAttribute' => ['sub_district_code' => 'sub_district_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'State' => Yii::t('app', 'state'),
            'district' => Yii::t('app', 'District'),
            'block_code' => Yii::t('app', 'Block Code'),
            /* 'created_at' => Yii::t('app', 'Created At'),
              'is_active' => Yii::t('app', 'Is Active'),
              'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
              'updated_at' => Yii::t('app', 'Updated At'), */
            'block_name' => Yii::t('app', 'Block Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
                /*  'created_by' => Yii::t('app', 'Created By'),
                  'updated_by' => Yii::t('app', 'Updated By'), */
        ];
    }

    public function getCode() {
        $data=  $this->find()->select(["max(convert(int,substring(block_code,6,2))) as block_code"])->where(['sub_district_code'=>  $this->sub_district_code])->one();        
        return $this->sub_district_code.str_pad((int)$data['block_code']+1,2,'0',STR_PAD_LEFT);;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }
    /**
     * @inheritdoc
     * @return TblBlocksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblBlocksQuery(get_called_class());
    }
}
