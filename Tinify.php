<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-media
 * @license BSD-3-Clause
 * @package yii2-tinify
 * @version 1.0.2
 */

namespace cinghie\tinify;

use Tinify as BaseTinify;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\web\UnauthorizedHttpException;

/**
 * Class Tinify
 */
class Tinify extends Component
{
	/**
	 * @var string $amazon_access_key_id
	 */
	public string $amazon_access_key_id;

	/**
	 * @var string $amazon_secret_access_key
	 */
	public string $amazon_secret_access_key;

	/**
	 * @var string $amazon_region
	 */
	public string $amazon_region;

	/**
	 * @var string $amazon_headers
	 */
	public string $amazon_headers;

	/**
	 * @var string $amazon_path
	 */
	public string $amazon_path;

	/**
	 * @var string $apiKey
	 */
	public string $apiKey;

	/**
	 * @var string $proxy
	 */
	public string $proxy;

	/**
	 * Initialize Component
	 *
	 * @throws InvalidConfigException if $apiKey is not set
	 * @throws UnauthorizedHttpException if $apiKey is not validated
	 *
	 * @see https://tinypng.com/developers/reference/php#authentication
	 */
	public function init()
	{
		if (!$this->apiKey) {
			throw new InvalidConfigException('The TinyPNG apiKey must be in set in ' . get_class($this) . '.');
		}

		if($this->proxy) {
			BaseTinify\setProxy($this->proxy);
		}

		try {
			BaseTinify\setKey($this->apiKey);
			BaseTinify\validate();
		} catch(BaseTinify\Exception $e) {
			$message = 'The error message is: '.$e->getMessage().'<br><br>>';
			$message .= 'The TinyPNG apiKey '.$this->apiKey.' could not be validated';
			throw new UnauthorizedHttpException($message);
		}
	}

	/**
	 * Compress image from Path
	 *
	 * @param string $sourceImage
	 * @param string $destinationImage
	 *
	 * @see https://tinypng.com/developers/reference/php#compressing-images
	 */
	public function compress($sourceImage, $destinationImage = null)
	{
		$destinationImage = $destinationImage ?: $sourceImage;
		$source = BaseTinify\fromFile($sourceImage);
		$source->toFile($destinationImage);
	}

	/**
	 * Compress image from Buffer
	 *
	 * @param string $bufferImage
	 *
	 * @return mixed
	 *
	 * @see https://tinypng.com/developers/reference/php#compressing-images
	 */
	public function compressFromBuffer($bufferImage)
	{
		$sourceData = file_get_contents($bufferImage);

		return BaseTinify\fromBuffer($sourceData)->toBuffer();
	}

	/**
	 * Compress image from URL
	 *
	 * @param string $urlImage
	 * @param string $destinationImage
	 *
	 * @see https://tinypng.com/developers/reference/php#compressing-images
	 */
	public function compressFromURL($urlImage, $destinationImage)
	{
		$source = BaseTinify\fromUrl($urlImage);
		$source->toFile($destinationImage);
	}

	/**
	 * Resize image
	 *
	 * @param string $sourceImage
	 * @param string $destinationImage
	 * @param array $options
	 *
	 * - string method scale | fit | cover | thumb
	 * - int width
	 * - int height
	 *
	 * @see https://tinypng.com/developers/reference/php#resizing-images
	 */
	public function resize($sourceImage, $destinationImage = null, $options = ['method' => 'fit', 'width' => 150, 'height' => 100])
	{
		$destinationImage = $destinationImage ?: $sourceImage;
		$source = BaseTinify\fromFile($sourceImage);
		$resized = $source->resize($options);
		$resized->toFile($destinationImage);
	}

	/**
	 * Converting images
	 *
	 * @param string $sourceImage
	 * @param string $destinationImage
	 * @param array $options
	 *
	 * @see https://tinypng.com/developers/reference/php#converting-images
	 */
	public function convert($sourceImage, $destinationImage = null, $options = ['type' => ['image/webp','image/png']])
	{
		$destinationImage = $destinationImage ?: $sourceImage;
		$source = BaseTinify\fromFile($sourceImage);
		$converted = $source->convert($options);
		$extension = $converted->result()->extension();
		$pos = strrchr($destinationImage, ".");
		$destinationImage = substr($destinationImage, 0, $pos);
		$converted->toFile($destinationImage.'.'.$extension);
	}

	/**
	 * Store image to Amazon S3
	 *
	 * @param $sourceImage
	 *
	 * @see https://tinypng.com/developers/reference/php#saving-to-amazon-s3
	 */
	public function storeToAmazonS3($sourceImage)
	{
		$source = BaseTinify\fromFile($sourceImage);
		$source->store(array(
			'aws_access_key_id' => $this->amazon_access_key_id,
			'aws_secret_access_key' => $this->amazon_secret_access_key,
			'headers' => $this->amazon_headers,
			'path' => $this->amazon_path,
			'region' => $this->amazon_region,
			'service' => 's3'
		));
	}

	/**
	 * Gets compressions used this month
	 *
	 * @see https://tinypng.com/developers/reference/php#compression-count
	 */
	public function compressCount()
	{
		return BaseTinify\compressionCount();
	}
}
