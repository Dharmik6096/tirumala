<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsElectionHistory]].
 *
 * @see TblDcsElectionHistory
 */
class TblDcsElectionHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsElectionHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsElectionHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
