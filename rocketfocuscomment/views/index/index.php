<?php

use humhub\widgets\Button;

// Register our module assets, this could also be done within the controller
\humhub\modules\rocketfocuscomment\assets\FocusCommentAssets::register($this);

$displayName = (Yii::$app->user->isGuest) ? Yii::t('RocketfocuscommentModule.base', 'Guest') : Yii::$app->user->getIdentity()->displayName;

// Add some configuration to our js module
$this->registerJsConfig("rocketfocuscomment", [
    'username' => (Yii::$app->user->isGuest) ? $displayName : Yii::$app->user->getIdentity()->username,
    'text' => [
        'hello' => Yii::t('RocketfocuscommentModule.base', 'Hi there {name}!', ["name" => $displayName])
    ]
])

?>

<div class="panel-heading"><strong>Rocketfocuscomment</strong> <?= Yii::t('RocketfocuscommentModule.base', 'overview') ?></div>

<div class="panel-body">
    <p><?= Yii::t('RocketfocuscommentModule.base', 'Hello World!') ?></p>

    <?=  Button::primary(Yii::t('RocketfocuscommentModule.base', 'Say Hello!'))->action("rocketfocuscomment.hello")->loader(false); ?></div>
