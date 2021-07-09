<?php
namespace humhub\modules\rocketfocuscomment\helpers;

use yii\helpers\Url as BaseUrl;

class Url extends BaseUrl
{
    public static function withGetParam($url, $param, $value)
    {
        $parsed = parse_url($url);
        $processed = ($parsed['scheme'] ? $parsed['scheme'] . '://' : '')
            . ($parsed['host'] ?? '')
            . ($parsed['port'] ? ':' . $parsed['port'] : '')
            . ($parsed['path'] ?? '');
        $queryParams = [];
        parse_str($parsed['query'], $queryParams);
        $queryParams[$param] = $value;

        return $processed . (empty($queryParams) ? '' : '?' . http_build_query($queryParams));
    }
}
