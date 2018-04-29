<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDpuInstallation]].
 *
 * @see TblDpuInstallation
 */
class TblDpuInstallationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDpuInstallation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDpuInstallation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
