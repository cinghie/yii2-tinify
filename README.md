# Yii2 Tinify
Yii2 integration for TinyPng: https://tinypng.com/

Installation
-----------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require cinghie/yii2-tinify "^1.0.0"
```

or add this line to the require section of your `composer.json` file.

```
"cinghie/yii2-tinify": "^1.0.0"
```

Usage
-----------------

Initialize component: https://tinypng.com/developers/reference/php#authentication

```
$tinify = new Tinify(['apiKey' => 'YOUR API KEY']);
```

Compress image from Path: https://tinypng.com/developers/reference/php#compressing-images)

```
// create a new image
$tinify->compress('path/to/file/to/compress','path/to/file/after/compression');
// overwrite file
$tinify->compress('path/to/file/to/compress');
```

Compress image from Buffer: https://tinypng.com/developers/reference/php#compressing-images)

```
$tinify->compressFromBuffer('path/to/file/to/compress');
```

Compress image from Url: https://tinypng.com/developers/reference/php#compressing-images)

```
$tinify->compressFromUrl('https://tinypng.com/images/panda-happy.png','path/to/file/after/compression');
```

Resize image: https://tinypng.com/developers/reference/php#resizing-images

```
// create a new image
$tinify->resize('path/to/file/to/compress','path/to/file/after/compression',['method' => 'fit', 'width' => 150, 'height' => 100]);
// overwrite file
$tinify->resize('path/to/file/to/compress',null,['method' => 'fit', 'width' => 150, 'height' => 100]);
// Available methods
scale,fit,cover,thumb
```

Store image to Amazon S3: https://tinypng.com/developers/reference/php#saving-to-amazon-s3

```
$tinify = new Tinify([  
	'apiKey' => 'YOUR API KEY',  
	'aws_access_key_id' => 'AKIAIOSFODNN7EXAMPLE',  
	'aws_secret_access_key' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',  
	'region' => 'us-west-1',  
	'headers' => array('Cache-Control' => 'max-age=31536000, public'),  
	'path' => 'example-bucket/my-images/optimized.jpg'
]);
$tinify->storeToAmazonS3('path/to/file/to/compress');
```

Compressions used this month: https://tinypng.com/developers/reference/php#compression-count

```
$count = $tinify->usage();
```
