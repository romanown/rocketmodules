<?php

namespace  humhub\modules\rocketfocuscomment\assets;

use humhub\modules\stream\assets\StreamAsset;
use yii\web\AssetBundle;

/**
* AssetsBundles are used to include assets as javascript or css files
*/
class FocusCommentAssets extends AssetBundle
{
    public $depends = [
        StreamAsset::class,
    ];

    /**
     * @var string defines the path of your module assets
     */
    public $sourcePath = '@rocketfocuscomment/resources';

    /**
     * @var array defines where the js files are included into the page, note your custom js files should be included after the core files (which are included in head)
     */
    public $jsOptions = ['position' => \yii\web\View::POS_END];

    /**
     * @inheritdoc
     */
    public $cssOptions = ['position' => \yii\web\View::POS_HEAD];

    /**
    * @var array change forceCopy to true when testing your js in order to rebuild this assets on every request (otherwise they will be cached)
    */
    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $js = [
        'js/humhub.rocketfocuscomment.main.js?v=0.3',
    ];

    public $css = [
        'css/rocketfocuscomment.css?v=0.3',
    ];
}
