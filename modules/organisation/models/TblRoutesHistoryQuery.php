<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblRoutesHistory]].
 *
 * @see TblRoutesHistory
 */
class TblRoutesHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblRoutesHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblRoutesHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
