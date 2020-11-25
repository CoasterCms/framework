<?php namespace CoasterCms\Croppa;

use Bkwld\Croppa\URL as BaseUrl;

class Url extends BaseUrl
{

    /**
     * Extract the path from a URL and remove it's leading slash
     *
     * @param string $url
     * @return string path
     */
    public function toPath($url) {
        $path = parent::toPath($url);
        $replaces = $this->config['url_replace'] ?: [];
        foreach ($replaces as $replacePattern => $replacement) {
            $path = preg_replace($replacePattern, $replacement, $path);
        }
        return  $path;
    }

}
