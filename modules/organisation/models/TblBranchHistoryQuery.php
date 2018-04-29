<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblBranchHistory]].
 *
 * @see TblBranchHistory
 */
class TblBranchHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblBranchHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblBranchHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
