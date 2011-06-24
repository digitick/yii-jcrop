<?php

/**
 * Base class.
 */
class EJCropper
{
	public $jpeg_quality = 100;
	public $targ_w = 100;
	public $targ_h = 100;

	public function crop($src, $coords)
	{
		$file_type = pathinfo($src, PATHINFO_EXTENSION);

		if ($file_type == 'jpg' || $file_type == 'jpeg') {
			$img_r = imagecreatefromjpeg($src);
		}
		elseif ($file_type == 'png') {
			$img_r = imagecreatefrompng($src);
		}
		else {
			return false;
		}
		$dest_r = imagecreatetruecolor($this->targ_w, $this->targ_h);
		if (!imagecopyresampled($dest_r, $img_r, 0, 0, $coords['x'], $coords['y'], $this->targ_w, $this->targ_h, $coords['w'], $coords['h'])) {
			return false;
		}
		return $dest_r;
	}

}