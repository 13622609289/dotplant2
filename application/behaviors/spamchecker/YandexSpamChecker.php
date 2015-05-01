<?php

namespace app\behaviors\spamchecker;

class YandexSpamChecker implements SpamCheckable
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getType()
    {
        return 'yandex';
    }

    /**
     * @return array
     */
    public function check()
    {
        if (!isset($this->data['ip']) && isset($_SERVER['REMOTE_ADDR'])) {
            $this->data['ip'] = $_SERVER['REMOTE_ADDR'];
        }
        $query = http_build_query($this->data);

        $curl = curl_init();
        if ($curl === false) {
            return [
                'ok' => "0",
                'message' => 'curl_init failed'
            ];
        }

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, "http://cleanweb-api.yandex.ru/1.0/check-spam");

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            return [
                'ok' => false,
                'message' => curl_error($curl)
            ];
        }

        try {
            $xmlObj = new \SimpleXMLElement($response);

            $ro = new \ReflectionObject($xmlObj);
            if ($ro->hasProperty('id') && $ro->hasProperty('text')) {
                $id = (string) $xmlObj->id;
                $spamFlag = $xmlObj->text->attributes()['spam-flag'];
            } elseif ($ro->hasProperty('message')) {
                return [
                    'ok' => false,
                    'message' => (string) $xmlObj->message
                ];
            } else {
                return [
                    'ok' => false,
                    'message' => 'invalid response'
                ];
            }
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'message' => 'Exception: ' . $e->getMessage() . ' with code: ' . $e->getCode()
            ];
        }
        $is_spam = false;
        if ($spamFlag[0] == 'yes') {
            $is_spam = true;
        }
        return [
            'ok' => true,
            'responce_id' => $id,
            'is_spam' => $is_spam
        ];

    }
}
