<?php

namespace app\modules\setting\models;

/**
 * This is the ActiveQuery class for [[TblGeneralConfig]].
 *
 * @see TblGeneralConfig
 */
class TblGeneralConfigQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblGeneralConfig[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblGeneralConfig|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
