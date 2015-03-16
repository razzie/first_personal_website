<?php
class ContentManager
{
	// stores navigation menu items
	protected $nav = array(
		"home"		=>	"Home",
		"resume"	=>	"Résumé",
		"projects"	=>	"Pet projects",
		"contact"	=>	"Contact"
	);

	// stores content pages
	protected $content = array(
		"home"		=>	array("about"),
		"resume"	=>	array(
			"objective", "achievements", "competence",
			"education", "other"),
		"projects"	=>	array(
			"process_manager", "window_manager", "logic_circuit_simulator", 
			"razzie_messenger", "server-client", "prepi",
			"cube_test", "labyrinth", "this_website",
			"gglib", "deut_bomb"),
		"contact"	=>	array("contact")
	);
	
	protected $page = '404';
	protected $subpages = array('404');
	
	public function __construct()
	{
		if (isset($_GET['p']))
		{
			if (1 === preg_match('/^([a-z_]+)\/?$/', $_GET['p'], $matches))
			{
				if (array_key_exists($matches[1], $this->content))
				{
					$this->page = $matches[1];
					$this->subpages = $this->content[$this->page];
				}
			}
			else if (1 === preg_match('/^([a-z_]+)\/([a-z_]+)\/?$/', $_GET['p'], $matches))
			{
				if (array_key_exists($matches[1], $this->content) and
					in_array($matches[2], $this->content))
				{
					$this->page = $matches[1];
					$this->subpages = array($matches[2]);
				}
			}
		}
		else
		{
			// if not specified go to default page
			$this->page = array_keys($this->nav)[0];
			$this->subpages = $this->content[$this->page];
		}
	}
	
	public function displayNavLinks()
	{
		foreach($this->nav as $link => $name)
		{
			$selected = ($link === $this->page) ? ' class="selected"' : '';
			echo "<a href=\"{$link}\"{$selected}>{$name}</a>\n";
		}
	}
	
	public function displayContent()
	{
		$pages_count = count($this->subpages);
		
		foreach($this->subpages as $page)
		{
			if (file_exists("content/{$page}.html"))
			{
				$file = "content/{$page}.html";
			}
			else if (file_exists("content/{$page}.php"))
			{
				$file = "content/{$page}.php";
			}
			else
			{
				echo "<p>Page not found: {$page}</p>";
				continue;
			}
			
			echo "<section data-url=\"{$page}\">";
			include $file;
			echo "</section>";
		}
	}
}

$content = new ContentManager();
?>