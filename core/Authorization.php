<?php


namespace core;


class Authorization
{
    /**
     * @param $url
     * @return string
     */
    public function basicAuth($url) : string
    {
        $request = new Request();

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: ' . $request->getHeader('Authorization')]);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    /**
     * @return array
     */
    public function getBasicAuthData() : array
    {
        $request = new Request();
        $auth = $request->getHeader('Authorization');

        $data = base64_decode($auth);
        $data = str_replace('Basic ', '', $data);
        $username = strstr($data, ':', true);
        $password = str_replace($username . ':', '', $data);

        return ['username' => $username, 'password' => $password];
    }
}