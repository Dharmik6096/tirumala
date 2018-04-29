<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblFederationsHistory]].
 *
 * @see TblFederationsHistory
 */
class TblFederationsHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFederationsHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFederationsHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
