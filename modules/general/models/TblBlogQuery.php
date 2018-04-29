<?php

namespace app\modules\general\models;

/**
 * This is the ActiveQuery class for [[TblBlog]].
 *
 * @see TblBlog
 */
class TblBlogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblBlog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblBlog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
