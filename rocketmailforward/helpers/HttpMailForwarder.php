<?php
namespace humhub\modules\rocketmailforward\helpers;

use humhub\libs\HttpClient;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\rocketmailforward\models\Config;
use humhub\modules\rocketmailforward\models\MailForward;
use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;

class HttpMailForwarder
{
    private $timeout;

    public function __construct()
    {
        $config = Config::instance();

        $this->timeout = (int)($config->httpRequestTimeout ?? static::DEFAULT_TIMEOUT_SEC);
    }

    public function send(MessageEntry $messageEntry, MailForward $mailForward)
    {
        $httpClient = new HttpClient();
        $request = $httpClient->createRequest();
        $request->setOptions($this->getDefaultOptions());
        $request->setMethod('post');
        $request->setUrl($mailForward->endpoint);
        $request->setFormat(Client::FORMAT_JSON);
        $request->setData(Json::encode($messageEntry));

        try {
            $httpClient->send($request);
        } catch (\Exception $e) {
            Yii::error(sprintf(
                '[rocketmailforward] Error during forwarding message (message_entry.id=%s) for user (user.id=%s) to (endpoint=%s): %s',
                $messageEntry->id,
                $mailForward->user_id,
                $mailForward->endpoint,
                $e->getMessage()
            ));
        }
    }

    public function getDefaultOptions()
    {
        return [
            'timeout' => $this->timeout,
        ];
    }
}
