<?php
namespace humhub\modules\rocketadminstats\helpers;

use humhub\libs\DateHelper;

class DbDateParser
{
    public static function parse($dateStr)
    {
        //Standard helper doens't support direct parsing of dates with no time specified atm
        //return DateHelper::parseDateTime($this->endDate);
        return (new \DateTime($dateStr))->format('Y-m-d');
    }
}
