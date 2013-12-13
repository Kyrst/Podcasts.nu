<?php
class Podtalk extends Eloquent
{
	protected $table = 'podtalks';

	public function get_link()
	{
		return URL::to('poddsnack/' . $this->slug);
	}

	public function image_exists()
	{
		return file_exists($this->get_image_filepath());
	}

	public function delete_image_file()
	{
		if ( $this->image_exists() )
		{
			unlink($this->get_image_filepath());
		}
	}

	public function get_image_filepath()
	{
		return public_path() . '/images/poddsnack/' . $this->id . '.jpg';
	}

	public static function get_image_filepath_from_id($id)
	{
		return public_path() . '/images/poddsnack/' . $id . '.jpg';
	}

	public function save_image($file)
	{
		if ( $this->image === 'yes' )
		{
			$this->delete_image_file();
		}

		$save_dir = public_path() . '/images/poddsnack/';

		if ( $file->move($save_dir, $this->id . '.jpg') )
		{
			$this->image = 'yes';
			$this->save();
		}
	}

	public function get_image_link($size)
	{
		return URL::to('bild/poddsnack/' . $this->id . '/' . $size);
	}

	public function get_image($size)
	{
		return '<img src="' . $this->get_image_link($size) . '" alt="">';
	}
}