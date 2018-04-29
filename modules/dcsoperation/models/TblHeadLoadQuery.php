<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblHeadLoad]].
 *
 * @see TblHeadLoad
 */
class TblHeadLoadQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblHeadLoad[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoad|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
