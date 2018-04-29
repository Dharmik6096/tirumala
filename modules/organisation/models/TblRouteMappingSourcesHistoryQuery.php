<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblRouteMappingSourcesHistory]].
 *
 * @see TblRouteMappingSourcesHistory
 */
class TblRouteMappingSourcesHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblRouteMappingSourcesHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblRouteMappingSourcesHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
