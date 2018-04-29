<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblHeadLoadHistory]].
 *
 * @see TblHeadLoadHistory
 */
class TblHeadLoadHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblHeadLoadHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
