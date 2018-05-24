<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\globalmaster\models\TblUnits;

/**
 * This is the model class for table "tbl_product".
 *
 * @property integer $product_code
 * @property integer $product_group_code
 * @property string $product_name
 * @property string $description
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 *
 * @property TblProductGroup $productGroupCode
 */
class TblProduct extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_group_code', 'product_name', 'union_code', 'unit_code'], 'required'],
            [['product_group_code', 'is_active'], 'integer'],
            [['product_name', 'description', 'created_by', 'updated_by', 'local_name'], 'string'],
            ['product_name', 'unique', 'when' => function($model) {
                    $data = $this->find()->where(['union_code' => $model->union_code, 'product_name' => $model->product_name])->andWhere(['<>', 'product_code', $model->product_code])->one();
                    return ($data) ? true : false;
                }],
                    [['product_name'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                    [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                    [['created_at', 'updated_at'], 'safe'],
                    [['product_group_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProductGroup::className(), 'targetAttribute' => ['product_group_code' => 'product_group_code']],
                ];
            }

            /**
             * @inheritdoc
             */
            public function attributeLabels() {
                return [
                    'product_code' => Yii::t('app', 'Product Code'),
                    'product_group_code' => Yii::t('app', 'Product Group'),
                    'product_name' => Yii::t('app', 'Product Name'),
                    'description' => Yii::t('app', 'Description'),
                    'created_at' => Yii::t('app', 'Created At'),
                    'created_by' => Yii::t('app', 'Created By'),
                    'is_active' => Yii::t('app', 'Is Active'),
                    'updated_at' => Yii::t('app', 'Updated At'),
                    'updated_by' => Yii::t('app', 'Updated By'),
                    'local_name' => Yii::t('app', 'Local Name'),
                    'union_code' => Yii::t('app', 'Union'),
                    'unit_code' => Yii::t('app', 'Unit'),
                ];
            }

            /**
             * @return \yii\db\ActiveQuery
             */
            public function getProductGroupCode() {
                return $this->hasOne(TblProductGroup::className(), ['product_group_code' => 'product_group_code']);
            }

            public function getUnionCode() {
                return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
            }

            public function getUnitCode() {
                return $this->hasOne(TblUnits::className(), ['unit_code' => 'unit_code']);
            }

            /**
             * @inheritdoc
             * @return TblProductQuery the active query used by this AR class.
             */
            public static function find() {
                return new TblProductQuery(get_called_class());
            }

        }
        