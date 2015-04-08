<?php
include 'simple_html_dom.php';

class ContentManager
{
	protected $pages = [
		'home' => 'Home',
		'projects' => 'Projects',
		'demos' => 'Demos',
		'resume' => 'Résumé',
	];
	
	protected $page_id = null;
	protected $section_id = null; // null means show all
	
	protected $file = null;
	protected $page = null;
	protected $section = null;
	
	public function __construct()
	{
		if (isset($_GET['p']))
		{
			// gorzsony.com/page
			if (1 === preg_match('/^([a-z_]+)\/?$/', $_GET['p'], $matches))
			{
				if (array_key_exists($matches[1], $this->pages))
					$this->page_id = $matches[1];
			}
			// gorzsony.com/page/section
			else if (1 === preg_match('/^([a-z_]+)\/([a-z_]+)\/?$/', $_GET['p'], $matches))
			{
				if (array_key_exists($matches[1], $this->pages))
				{
					$this->page_id = $matches[1];
					$this->section_id = $matches[2];
				}
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
		
		// check if file exists and save its name
		if (file_exists("../content/{$this->page_id}.html"))
			$this->file = "../content/{$this->page_id}.html";
		else if (file_exists("../content/{$this->page_id}.php"))
			$this->file = "../content/{$this->page_id}.php";
		
		// preload section if specified
		if ($this->file && $this->section_id)
		{
			$this->page = file_get_html($this->file);
			$this->section = $this->page->find("section[data-id={$this->section_id}]", 0);
		}
	}
	
	public function getTitle()
	{
		if ($this->section)
			return "{$this->section->find('h1, h2', 0)->innertext} - Gábor Görzsöny";
		else if ($this->file)
			return "{$this->pages[$this->page_id]} - Gábor Görzsöny";
		else
			return "Gábor Görzsöny";
	}
	
	public function getPage()
	{
		return $this->page_id;
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
		if ($this->section)
			echo $this->section->outertext;
		else if ($this->file)
			{ include $this->file; }
		else
			$this->display404();

		echo "<script>var pageTitle = '{$this->getTitle()}';</script>\n";
	}
}

$content = new ContentManager();
?>