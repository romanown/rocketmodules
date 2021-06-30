<?php
namespace  humhub\modules\rocketmailforward;

use humhub\modules\mail\models\MessageEntry;
use humhub\modules\rocketmailforward\helpers\HttpMailForwarder;
use humhub\modules\rocketmailforward\models\MailForward;
use Yii;
use yii\db\AfterSaveEvent;
use yii\helpers\Url;

class Events
{
    public static function onMessageSent(AfterSaveEvent $event)
    {
        /** @var MessageEntry $messageEntry */
        $messageEntry = $event->sender;

        try {
            $recipients = (new MailForward)->findRecipients($messageEntry);
            $forwarder = static::getHttpForwarder();
            foreach ($recipients as $recipient) {
                $forwarder->send($messageEntry, $recipient);
            }
        } catch (\Exception $e) {
            \Yii::error(sprintf(
                '[rocketmailforward] Error during post-handling mail module message (message_entry.id=%s): %s',
                $messageEntry->id,
                $e->getMessage()
            ));
        }
    }

    public static function onMessageUpdated(AfterSaveEvent $event)
    {
        static::onMessageSent($event);
    }

    public static function getHttpForwarder()
    {
        return \Yii::$container->get(HttpMailForwarder::class);
    }

    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param $event
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem([
            'label' => '(Rocket) Mail forward',
            'url' => Url::to(['/rocketmailforward/admin']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-rocket"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'rocketmailforward' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 88801,
        ]);
    }
}
