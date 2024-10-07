<?php
// use Image;

if (! function_exists('compressImage')) {
	function compressImage($fileUpload, $width, $height, $quality)
	{

        $path = public_path('uploads/'.$fileUpload);
		try {
			Image::make($path)
			->resize($width+150, $height+150, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			})
			->crop($width, $height)
			->encode('jpg', $quality)
			->save($path);
		} catch (\Throwable $th) {

		}
	}
}

if (! function_exists('createImage')) {
	function createImage($param)
	{
		$file = isset($param['file']) ? $param['file'] : null;
		$dir = isset($param['dir']) ? $param['dir'] : null;
		$name = isset($param['name']) ? $param['name'] : null;
		$width = isset($param['width']) ? $param['width'] : null;
		$height = isset($param['height']) ? $param['height'] : null;
		$quality = isset($param['quality']) ? $param['quality'] : null;


		list($extension, $content) = explode(';', $file);
		$tmpExtension = explode('/', $extension);
		preg_match('/.([0-9]+) /', microtime(), $m);
		if(empty($name)){
			$fileName = 'PMS_'.sprintf('%s%s.%s', date('YmdHis'), $m[1], $tmpExtension[1]);
		}
		else{
			$fileName = $name;
		}
		$content = explode(',', $content)[1];
		$storage = Storage::disk('public_uploads');

		$checkDirectory = $storage->exists($dir);

		if (!$checkDirectory) {
			$storage->makeDirectory($dir);
		}
		$fileUpload = $dir . '/' .$fileName;
		$checkFile = $storage->exists($fileUpload);
		$decoded = base64_decode($content);
		if(!$checkFile){
			$storage->put($fileUpload, $decoded, 'public');
		} else{
			$fileUpload = $dir . '/' .date('his').$fileName;
			$storage->put($fileUpload, $decoded, 'public');
		}
		if (in_array(strtolower($tmpExtension[1]), ['png','jpg','jpeg']) && $quality) {
			compressImage($fileUpload, $width, $height, $quality);
		}
		return $fileName;
	}
}