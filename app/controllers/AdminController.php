<?php
class AdminController extends BaseController
{
	public function view_blog_items()
	{
		if ( $this->user->blog_id !== NULL )
		{
			$blog_items = Blog_Item::where('blog_id', '=', $this->user->blog_id)->get();

			$this->assign('num_blog_items', $blog_items->count());
			$this->assign('blog_items', $blog_items);
		}

		$this->display('admin.view_blogs', 'Blogg - Administatör');
	}

	public function blog_item($id = NULL)
	{
		$input = Input::all();

		// Save
		if ( isset($input['save']) )
		{
			if ( isset($input['blog_item_id']) && filter_var($input['blog_item_id'], FILTER_VALIDATE_INT) ) // Edit
			{
				$blog_item_to_edit = Blog_Item::find($id);

				if ( $blog_item_to_edit !== NULL )
				{
					$blog_item_to_edit->title = trim($input['title']);
					$blog_item_to_edit->slug = Str::slug(trim($input['slug']));
					$blog_item_to_edit->body = trim($input['body']);
					$blog_item_to_edit->save();

					$this->showAlert('Blogginlägget sparad!');
				}

				return Redirect::to('admin/blogg');
			}
			else // Add
			{
				$blog_item = new Blog_Item();
				$blog_item->user_id = $this->user->id;
				$blog_item->title = trim($input['title']);
				$blog_item->slug = Str::slug(trim($input['slug']));
				$blog_item->body = trim($input['body']);
				$blog_item->save();

				$this->showAlert('Blogginlägget tillagt!');

				return Redirect::to('admin/blogg');
			}
		}

		$edit_mode = false;

		if ( $id !== NULL ) // Edit
		{
			$blog_item = Blog_Item::find($id);

			// If user was not found
			if ( $blog_item === NULL )
			{
				$this->showAlert('Kunde inte hitta blogginlägget!');

				return Redirect::to('admin/nyheter');
			}

			$this->assign('blog_item_to_edit', $blog_item);

			$edit_mode = true;
		}

		$this->assign('edit_mode', $edit_mode);

		$this->loadLib('ckeditor');

		$this->display('admin.blog_item');
	}

	public function view_news_items()
	{
		$news_items = News_Item::all();

		$this->assign('num_news_items', count($news_items));
		$this->assign('news_items', $news_items);

		$this->display('admin.view_news_items', 'Nyheter - Administatör');
	}

	public function news_item($id = NULL)
	{
		$input = Input::all();

		// Save
		if ( isset($input['save']) )
		{
			if ( isset($input['news_item_id']) && filter_var($input['news_item_id'], FILTER_VALIDATE_INT) ) // Edit
			{
				$news_item_to_edit = News_Item::find($id);

				if ( $news_item_to_edit !== NULL )
				{
					$title = trim($input['title']);

					$news_item_to_edit->title = $title;
					$news_item_to_edit->slug = Str::slug($title);
					$news_item_to_edit->content = trim($input['content']);
					$news_item_to_edit->save();

					$this->showAlert('Nyheten sparad!');
				}

				return Redirect::to('admin/nyheter');
			}
			else // Add
			{
				$title = trim($input['title']);

				$news_item = new News_Item();
				$news_item->title = $title;
				$news_item->slug = Str::slug($title);
				$news_item->content = trim($input['content']);
				$news_item->save();

				return Redirect::to('admin/nyheter');
			}
		}

		$edit_mode = false;

		if ( $id !== NULL ) // Edit
		{
			$news_item = News_Item::find($id);

			// If user was not found
			if ( $news_item === NULL )
			{
				$this->showAlert('Kunde inte hitta nyheten!');

				return Redirect::to('admin/nyheter');
			}

			$this->assign('news_item_to_edit', $news_item);

			$edit_mode = true;
		}

		$this->assign('edit_mode', $edit_mode);

		$this->loadLib('ckeditor');

		$this->display('admin.news_item', 'Nyhet - Nyheter - Administatör');
	}

	public function view_podcasts()
	{
		$podcasts = Podcast::with('category')->get();

		$this->assign('podcasts', $podcasts);

		$this->display('admin.view_podcasts', 'Poddar - Administatör');
	}

	public function podcast($id = NULL)
	{
		$input = Input::all();

		if ( isset($input['save']) )
		{
			if ( isset($input['podcast_id']) && filter_var($input['podcast_id'], FILTER_VALIDATE_INT) ) // Edit
			{
				$podcast_to_edit = Podcast::find($id);

				if ( $podcast_to_edit !== NULL )
				{
					$podcast_to_edit->name = trim(Input::get('name'));
					$podcast_to_edit->slug = trim(Input::get('slug'));
					$podcast_to_edit->description = trim(Input::get('description'));
					$podcast_to_edit->category_id = Input::get('category');
					$podcast_to_edit->rss = trim(Input::get('rss'));
					$podcast_to_edit->homepage = trim(Input::get('homepage'));
					$podcast_to_edit->facebook = trim(Input::get('facebook'));
					$podcast_to_edit->twitter = trim(Input::get('twitter'));
					$podcast_to_edit->itunes = trim(Input::get('itunes'));
					$podcast_to_edit->email = trim(Input::get('email'));
					$podcast_to_edit->save();

					$image_dir = $podcast_to_edit->getImageFolder(true);

					if ( Input::file('image_standard') !== NULL )
						Input::file('image_standard')->move($image_dir, $podcast_to_edit->getImageFilename('standard'));

					if ( Input::file('image_panorama') !== NULL )
						Input::file('image_panorama')->move($image_dir, $podcast_to_edit->getImageFilename('panorama'));

					$this->showAlert('Podden sparad!');
				}

				return Redirect::to('admin/poddar');
			}
			else // Add
			{
				$podcast = new Podcast();
				$podcast->name = trim(Input::get('name'));
				$podcast->slug = trim(Input::get('slug'));
				$podcast->description = trim(Input::get('description'));
				$podcast->category_id = Input::get('category');
				$podcast->rss = trim(Input::get('rss'));
				$podcast->homepage = trim(Input::get('homepage'));
				$podcast->facebook = trim(Input::get('facebook'));
				$podcast->twitter = trim(Input::get('twitter'));
				$podcast->itunes = trim(Input::get('itunes'));
				$podcast->email = trim(Input::get('email'));
				$podcast->save();

				$image_dir = $podcast->getImageFolder(true);

				if ( Input::file('image_standard') !== NULL )
					Input::file('image_standard')->move($image_dir, $podcast->getImageFilename('standard'));

				if ( Input::file('image_panorama') !== NULL )
					Input::file('image_panorama')->move($image_dir, $podcast->getImageFilename('panorama'));

				$this->showAlert('Podden tillagd!');

				return Redirect::to('admin/poddar');
			}
		}

		$edit_mode = false;

		if ( $id !== NULL ) // Edit
		{
			$podcast = Podcast::find($id);

			// If user was not found
			if ( $podcast === NULL )
			{
				$this->showAlert('Kunde inte hitta podden!');

				return Redirect::to('admin/poddar');
			}

			$this->assign('podcast_to_edit', $podcast);

			$edit_mode = true;
		}

		$this->assign('categories', Category::all());

		$this->assign('edit_mode', $edit_mode);

		$this->display('admin.podcast', 'Podcast - Poddar - Administatör');
	}

	public function view_episodes()
	{
		$episodes = Episode::all();

		$this->assign('episodes', $episodes);

		$this->display('admin.view_episodes', 'Avsnitt - Administatör');
	}

	// Add/Edit episode
	public function episode($id = NULL)
	{
		$input = Input::all();

		if ( isset($input['save']) )
		{
			if ( isset($input['episode_id']) && filter_var($input['episode_id'], FILTER_VALIDATE_INT) ) // Edit
			{
				$episode_to_edit = Episode::find($id);

				if ( $episode_to_edit !== NULL )
				{
					$episode_to_edit->podcast_id = Input::get('podcast');
					$episode_to_edit->title = trim(Input::get('title'));
					$episode_to_edit->slug = trim(Input::get('slug'));
					$episode_to_edit->description = trim(Input::get('description'));
					$episode_to_edit->media_link = trim(Input::get('media_link'));
					$episode_to_edit->save();

					$this->showAlert('Avsnitt sparat!');
				}

				return Redirect::to('admin/episodes');
			}
			else // Add
			{
				$episode = new Episode();
				$episode->podcast_id = Input::get('podcast');
				$episode->title = trim(Input::get('title'));
				$episode->slug = trim(Input::get('slug'));
				$episode->description = trim(Input::get('description'));
				$episode->media_link = trim(Input::get('media_link'));
				$episode->save();

				$this->showAlert('Avsnitt tillagt!');

				return Redirect::to('admin/episodes');
			}
		}

		$edit_mode = false;

		if ( $id !== NULL ) // Edit
		{
			$episode = Episode::find($id);

			// If episode was not found
			if ( $episode === NULL )
			{
				$this->showAlert('Kunde inte hitta avsnittet!');

				return Redirect::to('admin/episodes');
			}

			$this->assign('episode_to_edit', $episode);

			$edit_mode = true;
		}

		$this->assign('podcasts', Podcast::all());

		$this->assign('edit_mode', $edit_mode);

		$this->loadLib('ckeditor');

		$this->display('admin.episode', 'Avsnitt - Avsnitt - Administatör');
	}

	public function view_users()
	{
		$users = User::all();

		$this->assign('users', $users);

		$this->display('admin.view_users', 'Användare - Administatör');
	}
}