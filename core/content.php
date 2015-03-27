<?php
include 'simple_html_dom.php';

class ContentManager
{
	protected $pages = [
		'resume' => 'Résumé',
		'desktop_apps' => 'Desktop apps',
		'graphic_projects' => 'Graphic projects',
		'websites' => 'Websites',
		'sandbox' => 'Sandbox',
	];
	
	protected $page = null;
	protected $section = null; // null means show all
	
	public function __construct()
	{
		if (isset($_GET['p']))
		{
			// gorzsony.com/page
			if (1 === preg_match('/^([a-z_]+)\/?$/', $_GET['p'], $matches))
			{
				if (array_key_exists($matches[1], $this->pages))
					$this->page = $matches[1];
			}
			// gorzsony.com/page/section
			else if (1 === preg_match('/^([a-z_]+)\/([a-z_]+)\/?$/', $_GET['p'], $matches))
			{
				if (array_key_exists($matches[1], $this->pages))
				{
					$this->page = $matches[1];
					$this->section = $matches[2];
				}
			}
		}
		else
		{
			// if not specified go to default page
			foreach($this->pages as $id => $page)
			{
				$this->page = $id;
				break;
			}
		}
	}
	
	protected function display404()
	{
		echo '
		<p style="text-align: center">
			<img src="image/404.jpg" /><br />
			<button onclick="window.history.back()">Go Back</button>
		</p>';
	}
	
	public function getPage()
	{
		return $this->page;
	}
	
	public function displayNavLinks()
	{
		foreach($this->pages as $id => $page)
		{
			$selected = ($id === $this->page) ? ' class="selected"' : '';
			echo "<a href=\"{$id}/\"{$selected}>{$page}</a>\n";
		}
	}
	
	public function displayContent()
	{
		if (!$this->page)
			return $this->display404();
		
		if (file_exists("../content/{$this->page}.html"))
			$file = "../content/{$this->page}.html";
		else if (file_exists("../content/{$this->page}.php"))
			$file = "../content/{$this->page}.php";
		else
			return $this->display404();
		
		if ($this->section)
		{
			$html = file_get_html($file);
			$section = $html->find("section[data-id={$this->section}]", 0);
			
			if ($section)
				echo $section->outertext;
			else
				$this->display404();
		}
		else
		{
			include $file;
		}
	}
}

$content = new ContentManager();
?>