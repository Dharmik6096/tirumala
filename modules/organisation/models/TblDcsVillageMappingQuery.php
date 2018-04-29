<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsVillageMapping]].
 *
 * @see TblDcsVillageMapping
 */
class TblDcsVillageMappingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsVillageMapping[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsVillageMapping|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
