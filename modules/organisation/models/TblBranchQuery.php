<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblBranch]].
 *
 * @see TblBranch
 */
class TblBranchQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblBranch[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblBranch|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
