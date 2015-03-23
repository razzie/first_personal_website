$(document).ready(function()
{
	var navbar = $('nav');
	var content = $('main');
	
	function bindSectionLinks()
	{
		$('section').on('click', 'h1', function()
		{
			var url = $(this).closest('section').data('url');
			swapContent(url);
		});
	};

	function swapContent(url)
	{
		content.animate({'opacity': 0}, 'fast', 'swing', function()
		{
			$.ajax({
				'url': 'page.php?p=' + url,
				'dataType': 'html',
				'success': function(result)
				{
					content.html(result);
					content.animate({'opacity': 1}, 'fast');
					history.pushState({}, document.title, url);
					bindSectionLinks();
				}
			});
		});
	};

	navbar.on('click', 'a', function(event)
	{
		event.stopPropagation();
		event.preventDefault();
		
		var link = $(this);
		
		navbar.children('a').removeClass('selected');
		link.addClass('selected');
		
		swapContent(link.attr('href'));
	});
	
	bindSectionLinks();
});