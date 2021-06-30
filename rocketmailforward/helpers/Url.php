<?php
namespace humhub\modules\rocketmailforward\helpers;

use yii\helpers\Url as BasicUrl;

class Url extends BasicUrl
{
    public static function toAdminIndex()
    {
        return static::to(['/rocketmailforward/admin']);
    }

    public static function toRegisterMailForward()
    {
        return static::to(['/rocketmailforward/admin/save']);
    }

    public static function toDeleteMailForward()
    {
        return static::to(['/rocketmailforward/admin/delete']);
    }

    public static function toSearchUniqueUser()
    {
        return static::to(['/rocketmailforward/admin/search-user']);
    }
}
