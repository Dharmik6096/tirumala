<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsHistory]].
 *
 * @see TblDcsHistory
 */
class TblDcsHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
