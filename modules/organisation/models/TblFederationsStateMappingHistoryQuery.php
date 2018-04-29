<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblFederationsStateMappingHistory]].
 *
 * @see TblFederationsStateMappingHistory
 */
class TblFederationsStateMappingHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFederationsStateMappingHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFederationsStateMappingHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
