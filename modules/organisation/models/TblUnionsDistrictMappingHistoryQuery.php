<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblUnionsDistrictMappingHistory]].
 *
 * @see TblUnionsDistrictMappingHistory
 */
class TblUnionsDistrictMappingHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblUnionsDistrictMappingHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblUnionsDistrictMappingHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
