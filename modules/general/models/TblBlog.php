<?php

namespace app\modules\general\models;

use Yii;
use yii\web\UploadedFile;
use app\modules\general\models\TblBlogAttachment;
/**
 * This is the model class for table "tbl_blog".
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
 */
class TblBlog extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_blog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'user_id', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'integer'],
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
        ];
    }

    /**
     * @inheritdoc
     * @return TblBlogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblBlogQuery(get_called_class());
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBlogAttachments() {
        return $this->hasMany(TblBlogAttachment::className(), ['blog_id' => 'id']);
    }
    public function afterSave($insert, $changedAttributes)
    {
            $attachModule=new TblBlogAttachment();
            $attachments=UploadedFile::getInstances($attachModule, 'attachment_path');;
            foreach($attachments as $file)
            {
                $model=new TblBlogAttachment();
                $model->blog_id=  $this->id;
                $model->save();
                //var_dump($model);
                Yii::$app->general->uploadFile($file,$model,'attachment_path','attachments'); 
            }
        
    }
   
}
