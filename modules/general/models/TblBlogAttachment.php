<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_blog_attachment".
 *
 * @property integer $id
 * @property integer $blog_id
 * @property string $attachment_path
 */
class TblBlogAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_blog_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'blog_id'], 'integer'],
            [['attachment_path'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'blog_id' => Yii::t('app', 'Blog ID'),
            'attachment_path' => Yii::t('app', 'Attachment Path'),
        ];
    }
}
