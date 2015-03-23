<?php
class Page
{
	public $id;
	public $title;
	public $sections = [];
	
	public function __construct($id, $title, $sections)
	{
		$this->id = $id;
		$this->title = $title;
		$this->sections = $sections;
	}
}

class ContentManager
{
	protected $content = [];
	protected $page = 'error';
	protected $sections = ['404'];
	
	public function __construct()
	{
		// setting up pages and sections
		$this->content[] = new Page('home', 'Home', ['introduction']);
		$this->content[] = new Page('resume', 'Résumé', [
			'objective', 'achievements', 'competence',
			'education', 'other']);
		$this->content[] = new Page('projects', 'Pet projects', [
			'desktop', 'graphic', 'websites', 'other']);
		$this->content[] = new Page('contact', 'Contact', ['contact']);
		
		if (isset($_GET['p']))
		{
			// gorzsony.com/pagename
			if (1 === preg_match('/^([a-z_]+)\/?$/', $_GET['p'], $matches))
			{
				foreach ($this->content as $page)
				{
					if ($page->id == $matches[1])
					{
						$this->page = $matches[1];
						$this->sections = $page->sections;
						break;
					}
				}
			}
			// gorzsony.com/pagename/sectionname
			else if (1 === preg_match('/^([a-z_]+)\/([a-z_]+)\/?$/', $_GET['p'], $matches))
			{
				foreach ($this->content as $page)
				{
					if ($page->id == $matches[1] and
						in_array($matches[2], $page->sections))
					{
						$this->page = $matches[1];
						$this->sections = array($matches[2]);
						break;
					}
				}
			}
		}
		else
		{
			// if not specified go to default page
			$this->page = $this->content[0]->id;
			$this->sections = $this->content[0]->sections;
		}
	}
	
	public function displayNavLinks()
	{
		foreach($this->content as $page)
		{
			$selected = ($page->id === $this->page) ? ' class="selected"' : '';
			echo "<a href=\"{$page->id}\"{$selected}>{$page->title}</a>\n";
		}
	}
	
	public function displayContent()
	{
		$pages_count = count($this->sections);
		
		foreach($this->sections as $section)
		{
			if (file_exists("content/{$this->page}/{$section}.html"))
			{
				$file = "content/{$this->page}/{$section}.html";
			}
			else if (file_exists("content/{$this->page}/{$section}.php"))
			{
				$file = "content/{$this->page}/{$section}.php";
			}
			else
			{
				echo "<p>Page not found: {$section}</p>";
				continue;
			}
			
			echo "<section data-url=\"{$this->page}/{$section}\">";
			include $file;
			echo "</section>";
		}
	}
}

$content = new ContentManager();
?>