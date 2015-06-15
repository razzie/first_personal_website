<?php
include 'simple_html_dom.php';

class ContentManager
{
	protected $pages = [
		'feed' => 'News feed',
		'bio' => 'Bio',
		'projects' => 'Projects',
		'demos' => 'Demos',
	];

	protected $page_id = null;
	protected $section_id = null; // null means show all

	protected $page = null;
	protected $section = null;


	public function __construct()
	{
		if (isset($_GET['p']))
		{
			// gorzsony.com/page
			if (1 === preg_match('/^([a-z_]+)\/?$/', $_GET['p'], $matches))
			{
				$this->page_id = $matches[1];
			}
			// gorzsony.com/page/section
			else if (1 === preg_match('/^([a-z_]+)\/([a-z_]+)\/?$/', $_GET['p'], $matches))
			{
				$this->page_id = $matches[1];
				$this->section_id = $matches[2];
			}
		}
		else
		{
			// if not specified go to default page
			foreach($this->pages as $id => $page_title)
			{
				$this->page_id = $id;
				break;
			}
		}
		
		if (file_exists("../content/{$this->page_id}.html"))
		{
			$this->page = file_get_html("../content/{$this->page_id}.html");
		}
		else if (file_exists("../content/{$this->page_id}.php"))
		{
			ob_start();
			include "../content/{$this->page_id}.php";
			$this->page = str_get_html(ob_get_clean());
		}
		
		if ($this->page)
		{
			foreach($this->page->find('section') as $section)
			{
				$section_id = $section->getAttribute('id');
				$heading = $section->find('h2, h3', 0);
				
				if ($heading)
					$heading->outertext = "<a href=\"{$this->page_id}/{$section_id}/\" class=\"ajax\">{$heading->outertext}</a>";
				
				if ($this->section_id && $section_id == $this->section_id)
					$this->section = $section;
			}
		}
	}


	public function getPageName()
	{
		if (!$this->page)
			return 'Error 404';
		else if ($this->section)
		{
			$heading = $this->section->find('h2, h3', 0);
			if ($heading)
				return $heading->innertext;
			else if ($this->section->getAttribute('id'))
				return $this->section->getAttribute('id');
			else
				return 'Unknown';
		}
		else
			return $this->pages[$this->page_id];
	}

	public function getTitle()
	{
		return "{$this->getPageName()} - Gábor Görzsöny";
	}


	protected function display404()
	{
		echo '
		<p style="text-align: center">
			<img src="image/404.png" /><br />
			<button onclick="window.history.back()">Go Back</button>
		</p>';
	}

	public function displayNavLinks()
	{
		foreach($this->pages as $id => $page_title)
		{
			$selected = ($id === $this->page_id) ? ' class="selected"' : '';
			echo "<li><a href=\"{$id}/\"{$selected}>{$page_title}</a></li>\n";
		}
	}

	public function displayContent()
	{
		echo "<h1 style=\"display: none;\">{$this->getPageName()}</h1>";
		
		if (!$this->page)
			$this->display404();
		else if ($this->section)
			echo $this->section->outertext;
		else
			echo $this->page->outertext;
	}
}


$content = new ContentManager();
?>