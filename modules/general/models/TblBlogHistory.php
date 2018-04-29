<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_blog_history".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $user_id
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 */
class TblBlogHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_blog_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['title', 'description', 'user_id', 'created_by', 'updated_by'], 'string'],
            [['title', 'description', 'user_id', 'created_by', 'updated_by', 'created_at', 'is_active', 'updated_at', 'history_created_at'], 'safe'],
//            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
        ];
    }
}
