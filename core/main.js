$.fn.exists = function ()
{
    return this.length !== 0;
}

$(document).ready(function()
{
	var navbar = $('nav');
	var content = $('main');
	
	function initContent()
	{
		$('section').on('click', 'h1, h2', function()
		{
			var url = content.data('page') + '/' +
				$(this).closest('section').data('id') + '/';
			swapContent(url);
		});
		
		var sections = $('section');
		/*if (!sections.exists())
			sections = $('article');*/
		
		sections.on('mouseenter', function()
		{
			sections.stop();
			sections.not(this).animate({opacity: 0.5}, 'slow');
			$(this).animate({opacity: 1}, 'slow');
		});
		
		sections.on('mouseleave', function()
		{
			sections.stop();
			sections.animate({opacity: 1}, 'slow');
		});
	};

	function swapContent(url)
	{
		content.animate({opacity: 0}, 'fast', 'swing', function()
		{
			$.ajax(
			{
				'url': 'core/page.php?p=' + url,
				'dataType': 'html',
				'success': function(result)
				{
					content.data('page', url.split('/', 1));
					content.html(result);
					content.animate({opacity: 1}, 'fast');
					history.pushState({}, document.title, url);
					initContent();
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
	
	initContent();
});