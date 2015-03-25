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
		$('section').on('click', 'h1', function()
		{
			var url = $(this).closest('section').data('url');
			swapContent(url);
		});
		
		var articles = $('article');
		if (!articles.exists())
			articles = $('section');
		
		articles.on('mouseenter', function()
		{
			articles.stop();
			articles.not(this).animate({opacity: 0.5}, 'slow');
			$(this).animate({opacity: 1}, 'slow');
		});
		
		articles.on('mouseleave', function()
		{
			articles.stop();
			articles.animate({opacity: 1}, 'slow');
		});
	};

	function swapContent(url)
	{
		content.animate({opacity: 0}, 'fast', 'swing', function()
		{
			$.ajax(
			{
				'url': 'page.php?p=' + url,
				'dataType': 'html',
				'success': function(result)
				{
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