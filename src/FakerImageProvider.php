<?php

namespace QSchool\FakerImageProvider;

use Faker\Provider\Base as BaseProvider;
use InvalidArgumentException;
use RuntimeException;

class FakerImageProvider extends BaseProvider
{
    protected static string $baseUrl = "https://studentsapi.academy.qsoft.ru/faker-images/image/";

    private const CATEGORIES = [
        'album',
        'book',
        'car',
        'face',
        'fashion',
        'furniture',
        'game',
        'movie',
        'shoes',
        'watch',
    ];

    public static function imageUrl(
        int $width = 640,
        int $height = 480,
        ?string $category = null,
    ): string {
        if (empty($category) || ! in_array($category, self::CATEGORIES)) {
            $category = self::CATEGORIES[array_rand(self::CATEGORIES)];
        }

        return  self::$baseUrl . $category . "?w={$width}&h={$height}";
    }

    public static function image(
        string $dir = null,
        int $width = 640,
        int $height = 480,
        ?string $category = null,
    ): bool|RuntimeException|string {
        return self::fetchImage(static::imageUrl($width, $height, $category), $dir);
    }

    private static function fetchImage(
        string $url,
        ?string $dir,
    ): bool|RuntimeException|string {
        $dir = $dir === null ? sys_get_temp_dir() : $dir;

        if (! is_dir($dir) || ! is_writable($dir)) {
            throw new InvalidArgumentException(sprintf('Cannot write to directory "%s"', $dir));
        }

        $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        $filename = $name . '.jpeg';
        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        if (! function_exists('curl_exec')) {
            return new RuntimeException('The image formatter downloads an image from a remote HTTP server. Therefore, it requires that PHP can request remote hosts, either via cURL or fopen()');
        }

        $fp = fopen($filepath, 'w');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $success = curl_exec($ch) && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;
        fclose($fp);
        curl_close($ch);

        if (! $success) {
            unlink($filepath);

            return false;
        }

        return $filepath;
    }
}
