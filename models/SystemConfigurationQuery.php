<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SystemConfiguration]].
 *
 * @see SystemConfiguration
 */
class SystemConfigurationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SystemConfiguration[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SystemConfiguration|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
