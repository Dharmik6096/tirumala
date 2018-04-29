<?php

namespace app\modules\geo\models;

/**
 * This is the ActiveQuery class for [[TblBlocksHistory]].
 *
 * @see TblBlocksHistory
 */
class TblBlocksHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblBlocksHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblBlocksHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
