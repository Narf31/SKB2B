<?php


namespace App\Helpers;


class ResultGenerate
{
    /**
     * @param array|object $object
     * @param bool $status
     * @param string $message
     * @return bool|string
     */
    public static function Success(string $message = 'Успешно!', $object = [], bool $status = true): string
    {
        return json_encode((object)[
            'status' => $status,
            'message' => empty($message) ? 'Успешно' : $message,
            'result' => $object,
        ], JSON_UNESCAPED_UNICODE);
    }

    public static function Error(string $message = 'Ошибка!', $object = [], bool $status = false): string
    {
        return json_encode((object)[
            'status' => $status,
            'message' => empty($message) ? 'Ошибка' : $message,
            'result' => $object,
        ], JSON_UNESCAPED_UNICODE);
    }

    public static function JSON($object): string
    {
        return json_encode((object)$object, JSON_UNESCAPED_UNICODE);
    }
}
