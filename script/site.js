$(document).ready(function()
{
	var navbar = $('nav');
	var content = $('main');
	
	var swapContent = function(url, location)
	{
		content.animate({'opacity': 0}, 'fast', 'swing', function()
		{
			$.ajax({
				'url': url,
				'dataType': 'html',
				'success': function(result)
				{
					content.html(result);
					content.animate({'opacity': 1}, 'fast');
					history.pushState({}, document.title, location);
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
		
		var page = link.attr('href');
		var url = 'page.php?p=' + page + '&show';
		swapContent(url, page);
	});
});