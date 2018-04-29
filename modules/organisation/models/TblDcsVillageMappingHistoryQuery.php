<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsVillageMappingHistory]].
 *
 * @see TblDcsVillageMappingHistory
 */
class TblDcsVillageMappingHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsVillageMappingHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsVillageMappingHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
