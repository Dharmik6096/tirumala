<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsElection]].
 *
 * @see TblDcsElection
 */
class TblDcsElectionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsElection[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsElection|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
