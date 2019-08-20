<?php

if (!isset($_REQUEST)) {
    return;
}

//Строка для подтверждения адреса сервера из настроек Callback API
$confirmation_token = '43851ac7';

//Ключ доступа сообщества
$token = 'd42f7be6e0dc205cf12da2019f96fbe0ee279d6ac25702d132f909f6881624cd3beceb3b5cd2a9d8e3a53';

//Получаем и декодируем уведомление
$data = json_decode(file_get_contents('php://input'));

//Проверяем, что находится в поле "type"
switch ($data->type) {
//Если это уведомление для подтверждения адреса...
    case 'confirmation':
//...отправляем строку для подтверждения
        echo $confirmation_token;
        break;



//!!!!!!!!!!!!!!!!!!!!!!!!!!!! событие приходящие из приложения!!!!!!!!!!!!!!!!!!!!!!!
    case 'app_payload':
//...получаем id его автора
        $user_id = $data->object->user_id;

// делаем рандомное число для уникальности
        $random_id = rand(1000000000000, 9000000000000);
        //готовим ответ
        $request_params = array(
            'message' => "Спасибо за ваше мнимание!",
            'user_id' => $user_id,
            'random_id' => $random_id,
            'access_token' => $token,
            'v' => '5.101'
        );

        //собираем до кучи параметры
        $get_params = http_build_query($request_params);

        //отправляем тудысь....
        file_get_contents('https://api.vk.com/method/messages.send?' . $get_params);

//Возвращаем "ok" серверу Callback API
        echo('ok');
        break;
}
?>