<?php

namespace Poman\TestDrom\Task2\client;

use CurlHandle;
use Poman\TestDrom\Task2\throwable\RequestFormatException;
use Poman\TestDrom\Task2\throwable\ServiceError;

class CommentClient implements CommentClientInterface
{
    // Можно вынести в .env, но для простоты не стал
    const URL = 'http://example.com/';

    /**
     * @throws RequestFormatException|ServiceError
     */
    public function getComments(string $url = 'comments'): array
    {
        $curl = self::curlInit();
        curl_setopt($curl, CURLOPT_URL, self::URL . $url);

        $response = curl_exec($curl);
        return self::parseResponse($response, $curl);
    }

    /**
     * @throws ServiceError
     */
    private function curlInit(): CurlHandle
    {
        $curl = curl_init();
        if (!$curl) {
            throw new ServiceError('Unable to initialize cURL');
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        return $curl;
    }

    /**
     * @throws ServiceError
     * @throws RequestFormatException
     */
    private function parseResponse(string|bool $response, CurlHandle $curl)
    {
        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new ServiceError($error);
        }

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($http_status === 400) {
            throw new RequestFormatException($response);
        }
        if (str_starts_with($http_status, '2')) {
            return json_decode($response, true);
        }
        throw new ServiceError($response);

    }

    /**
     * @throws ServiceError
     * @throws RequestFormatException
     */
    public function postComment(string $json, string $url = 'comment'): array
    {
        $curl = self::curlInit();
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl, CURLOPT_URL, self::URL . $url);
        $response = curl_exec($curl);
        return self::parseResponse($response, $curl);
    }

    /**
     * @throws ServiceError
     * @throws RequestFormatException
     */
    public function updateComment(int $id, string $json, string $url = 'comment'): array
    {
        $curl = self::curlInit();
        curl_setopt($curl, CURLOPT_PUT, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl, CURLOPT_URL, self::URL . "$url/$id");
        $response = curl_exec($curl);
        return self::parseResponse($response, $curl);
    }

}