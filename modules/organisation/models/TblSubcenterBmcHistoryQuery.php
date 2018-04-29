<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblSubcenterBmcHistory]].
 *
 * @see TblSubcenterBmcHistory
 */
class TblSubcenterBmcHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblSubcenterBmcHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblSubcenterBmcHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
