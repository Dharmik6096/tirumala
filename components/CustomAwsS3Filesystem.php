<?php

namespace app\components;

use Aws\S3\S3Client;
use creocoder\flysystem\AwsS3Filesystem;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * Custom S3 Filesystem to handle SSL certificate issues on Windows.
 */
class CustomAwsS3Filesystem extends AwsS3Filesystem
{
    protected function prepareAdapter()
    {
        $config = [];
        $config['http'] = [
            'verify' => true,
            'curl'   => [
                CURLOPT_SSL_OPTIONS => CURLSSLOPT_NATIVE_CA
            ]
        ];

        if ($this->credentials === null) {
            $config['credentials'] = ['key' => $this->key, 'secret' => $this->secret];
        } else {
            $config['credentials'] = $this->credentials;
        }

        if ($this->pathStyleEndpoint === true) {
            $config['use_path_style_endpoint'] = true;
        }

        if ($this->region !== null) {
            $config['region'] = $this->region;
        }

        if ($this->baseUrl !== null) {
            $config['base_url'] = $this->baseUrl;
        }

        if ($this->endpoint !== null) {
            $config['endpoint'] = $this->endpoint;
        }

        $config['version'] = (($this->version !== null) ? $this->version : 'latest');

        $client = new S3Client($config);

        return new AwsS3Adapter($client, $this->bucket, $this->prefix, $this->options, $this->streamReads);
    }
}
