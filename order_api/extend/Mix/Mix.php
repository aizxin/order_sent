<?php
/**
 * FileName: Mix.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: 永 | <chuanshuo_yongyuan@163.com>
 * @date  : 2019/10/23 16:11
 */

namespace Mix;


use think\facade\Env;
use think\helper\Str;

class Mix
{
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     * @param string $manifestDirectory
     *
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    public function __invoke($path, $manifestDirectory = 'public')
    {
        static $manifests = [];

        if ( ! Str::startsWith($path, '/')) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && ! Str::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }
        if (file_exists(public_path($manifestDirectory) . 'hot')) {
            $url = rtrim(file_get_contents(public_path($manifestDirectory) . 'hot'));

            if (Str::startsWith($url, ['http://', 'https://'])) {
                return new HtmlString($this->after($url, ':') . $path);
            }

            return new HtmlString("//localhost:8080{$path}");
        }

        $manifestPath = public_path($manifestDirectory) . 'mix-manifest.json';

        if ( ! isset($manifests[ $manifestPath ])) {
            if ( ! file_exists($manifestPath)) {
                throw new \Exception('The Mix manifest does not exist.');
            }

            $manifests[ $manifestPath ] = json_decode(file_get_contents($manifestPath), true);
        }

        $manifest = $manifests[ $manifestPath ];

        if ( ! isset($manifest[ $path ])) {
            $exception = new \Exception("Unable to locate Mix file: {$path}.");

            if ( ! app('config')->get('app.debug')) {
                report($exception);

                return $path;
            } else {
                throw $exception;
            }
        }

        return new HtmlString(Env::get('app.mix_url') . $manifest[ $path ]);
    }

    public function after($subject, $search)
    {
        return $search === '' ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }
}