# SOFTONE ASIA - LARAVEL UPLOAD PACKAGE

[![N|Solid](https://images.careerbuilder.vn/employer_folders/lot8/207078/112258softone_jpg.jpg)](https://nodesource.com/products/nsolid)

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger)

### Features

  - Upload file (image, audio, video, document)
  - Auto generate multi size for image

### Installation

Requires to run:
> + PHP 5.6+
> + Laravel 5.4+


Install the dependencies by composer

```sh
$ composer require softoneasia/laravelupload
```

Publish config file

```sh
$ php artisan vendor:publish --tag=config
```

### Code Examples

```php
// Upload image
$file = $request->file('image');
SOUploader::upload($file,'image')

// Get Real Path
echo getFilePath('2018/12/1545965975_12.png'); // result: /_uploads/images/thumb/2018/12/1545965975_12.png
echo getFilePath('2018/12/1545965975_12.png', "medium"); // result: /_uploads/images/medium/2018/12/1545965975_12.png
echo getFilePath('2018/12/1545965975_12.png', "medium_large"); // result: /_uploads/images/medium_large/2018/12/1545965975_12.png
echo getFilePath('2018/12/1545965975_12.png', "large"); // result: /_uploads/images/large/2018/12/1545965975_12.png

// Show image
echo getImage('2018/12/1545965975_12.png');   
/*
<img src="/_uploads/images/thumb/2018/12/1545965975_12.png" alt="2018/12/1545965975_12.png" title="2018/12/1545965975_12.png" class="">
*/
echo getImage('2018/12/1545965975_12.png', ['size' => 'original', 'title'=>'example title', 'class' => 'img']);
/*
<img src="/_uploads/images/original/2018/12/1545965975_12.png" alt="example title" title="example title" class="img">
*/

