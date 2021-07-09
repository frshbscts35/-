<?php

class Translation {

    const DETECT_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/detect'; // констаннта, неизменное значение
    const TRANSLATE_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/translate'; // констаннта, неизменное значение
    public $key = "AlzalyCf2zgkmk-nRxdbB4gg49M9GZhmFei55uo"; // присвоение общедоступной переменной

    public function init(){ // функция вывода ошибки
        parent::init();

        if(empty($this->key)) { // если значение переменной является пустым
            throw new InvalidConfigException("Field <b>$key</b> is required");
        }
    }

    /*
    * @param $format text format need to translate
    * @return string
    */

    public static function translate_text($format="text") { 
        if (empty($this->key)) { // если значение переменной является пустым
            throw new InvalidConfigException("Field <b>$key</b> is required");
        }

        $values = array(
        'key' => $this->key,
        'text' => $_GET['text'],
        'lang' => $_GET['lang'],
        'format' => $format == "text" ? 'plain' : $format, // тернарный оператор производит смену формата
        );
        
        $formData = http_build_query($values); // генерирует URL-кодированную строку запроса массива $values

        $ch = curl_init(self::TRANSLATE_YA_URL); // инициализирует сеанс cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // устанавливает параметр для сеанса cURL, передача в качестве строки из curl_exec()
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formData); // устанавливает параметр для сеанса cURL, все данные передаваемые в HTTP POST-запросе

        $json = curl_exec($ch); // выполнение запроса cURL
        curl_close($ch); // завершение сеанса с cURL

        $data = json_decode($json, true);
        if ($data['code']==200){
            return $data['text'];
        }
        return $data;
    }
}