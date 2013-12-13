<?php
use Intervention\Image\Image;

class ImageController extends BaseController
{
	private $sizes = array
	(
		'small' => array
		(
			'width' => 64,
			'height' => 64
		),
		'medium' => array
		(
			'width' => 128,
			'height' => 128
		),
		'admin_poddsnack' => array
		(
			'width' => NULL,
			'height' => 120,
			'proportional' => TRUE
		)
	);

	public function init($type, $id, $size)
	{
		switch ( $type )
		{
			case 'poddsnack':
				$this->poddsnack($id, $size);

				break;
		}
	}

	private function poddsnack($id, $size)
	{
		$filepath = Podtalk::get_image_filepath_from_id($id);

		if ( file_exists($filepath) )
		{
			$size = $this->sizes[$size];

			$image = Image::make($filepath)
				->resize($size['width'], $size['height'], isset($size['proportional']) && $size['proportional'] === TRUE ? TRUE : FALSE);

			header('Content-Type: image/jpeg');

			die($image);
		}
		else
		{
			die('no');
		}
	}
}