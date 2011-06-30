<?php

/**
 * Base class.
 */
class EJCropper
{
	/**
	 * @var integer JPEG image quality
	 */
	public $jpeg_quality = 100;
	/**
	 * @var integer The thumbnail width
	 */
	public $targ_w = 100;
	/**
	 * @var integer The thumbnail height
	 */
	public $targ_h = 100;
	/**
	 * @var string The path for saving thumbnails
	 */
	public $thumbPath;

	/**
	 * Crop an image and save the thumbnail.
	 * 
	 * @param string $src Source image path.
	 * @param array $coords Cropping coordinates.
	 * @return string $thumbName Path of thumbnail.
	 */
	public function crop($src, array $coords)
	{
		if (!$this->thumbPath) {
			throw new CException(__CLASS__ . ' : thumbpath is not specified.');
		}
		$file_type = pathinfo($src, PATHINFO_EXTENSION);
		$thumbName = $this->thumbPath . '/' . pathinfo($src, PATHINFO_BASENAME);
		
		if ($file_type == 'jpg' || $file_type == 'jpeg') {
			$img = imagecreatefromjpeg($src);
		}
		elseif ($file_type == 'png') {
			$img = imagecreatefrompng($src);
		}
		else {
			return false;
		}
		$dest_r = imagecreatetruecolor($this->targ_w, $this->targ_h);
		if (!imagecopyresampled($dest_r, $img, 0, 0, $coords['x'], $coords['y'], $this->targ_w, $this->targ_h, $coords['w'], $coords['h'])) {
			return false;
		}
		if ($file_type == 'jpg' || $file_type == 'jpeg') {
			imagejpeg($dest_r, $thumbName, $this->jpeg_quality);
		}
		elseif ($file_type == 'png') {
			imagepng($dest_r, $thumbName, 5);
		}
		return $thumbName;
	}
}