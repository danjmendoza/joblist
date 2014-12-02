<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Global website controller that handles all universal 
 * functionality.
 */
class Vds_Controller_Website extends Controller_Template {

	/**
	 * Default template used by the site.
	 * 
	 * @var string
	 */
	public $template = 'template';

	/**
	 * Default title for site when there is no other.
	 * @var string
	 */
	public $title = 'Site Title';

	/**
	 * If ajax calls should be json encoded before
	 * rendering.
	 * @var boolean
	 */
	public $encode = TRUE;

	/**
	 * Runs before individual controller gets exectured
	 * 
	 * @return [type] [description]
	 */
	public function before()
	{
		parent::before();
		//I18n::lang('en');

		// Check if https and force redirect.
		//$this->is_https();

		// Check if user is logged in
		if (Auth::instance()->logged_in())
		{
			$orm_user = Auth::instance()->get_user();
		}
		else
		{
			$orm_user = ORM::factory('User');
		}

		// Set user for access in all controllers
		$this->user = User::instance($orm_user);

		// Set user to be available in all template files.
		$this->template->set_global('user', $this->user);
		$this->template->set_global('logged_in', Auth::instance()->logged_in());
	}

	/**
	 * Checks if the request is an ajax request
	 * or a request of some other type.
	 * 
	 * @param $_GET['json']
	 * @return boolean
	 */
	protected function is_ajax()
	{
		if ($this->request->is_ajax() or (isset($_GET['json']) AND $_GET['json'] == 'dev'))
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Runs all actions that happen after a page request.
	 * 
	 * @return [type] [description]
	 */
	public function after()
	{
		if ($this->is_ajax())
		{
			$this->auto_render = FALSE;
			$this->response->headers('Content-Type', 'application/json');
			if ($this->encode != TRUE)
			{
				$this->response->body($this->view);
			}
			else
			{
				$this->response->body(json_encode($this->view));
			}
		}
		else
		{
			$this->template->page_title = $this->title;
			$this->template->header_title = $this->title;
			$this->template->content = $this->view;
		}
		
		parent::after();
	}

	protected function is_https()
	{
		if ( ! isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == '' || strpos($_SERVER['HTTP_HOST'], 'www.') === false)
		{
			$base =str_replace('/', '', URL::base());
			$redirect = $base.$_SERVER['REQUEST_URI'];
			$this->redirect($redirect);
		}
	}

}
