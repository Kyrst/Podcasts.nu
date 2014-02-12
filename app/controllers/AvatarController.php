<?php
use Intervention\Image\Image;

class AvatarController extends Controller
{
	private $image_to_render;
	private $size;

	private $image_found = false;

	public function init($user_id, $size_name)
	{
		try
		{
			$this->size = User::get_avatar_size($size_name);
		}
		catch ( Exception $e )
		{
			exit;
		}

		$avatar_path = User::get_avatar_path($user_id);

		try
		{
			$this->image_to_render = Image::make($avatar_path);
		}
		catch ( \Intervention\Image\Exception\ImageNotFoundException $e )
		{
			$this->set_image_not_found();
		}

		$this->adjust_size();

		return $this->render();
	}

	private function adjust_size()
	{
		$this->image_to_render->resize($this->size['width'] > 0 ? $this->size['width'] : NULL, $this->size['height'] > 0 ? $this->size['height'] : NULL, isset($this->size['proportional']) ? $this->size['proportional'] : false);

		if ( isset($this->size['canvas']) && $this->size['canvas'] !== NULL )
		{
			$this->image_to_render->resizeCanvas(isset($this->size['canvas']['width']) ? $this->size['canvas']['width'] : NULL, isset($this->size['canvas']['height']) ? $this->size['canvas']['height'] : NULL);
		}
	}

	private function set_image_not_found()
	{
		//$this->image_to_render = Image::make(public_path() . '/images/avatars/default.png');
		$this->image_to_render = Image::make(public_path() . '/images/logga1_red.png');
	}

	private function render()
	{
		if ( $this->image_found )
		{
			$response = Response::make($this->image_to_render->encode('jpg'));
			$response->header('Content-Type', 'image/jpeg');
		}
		else
		{
			$response = Response::make($this->image_to_render->encode('png'));
			$response->header('Content-Type', 'image/png');
		}

		return $response;
	}
}