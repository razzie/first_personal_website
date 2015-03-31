$(document).ready(function()
{
	var navlinks = $('nav').children('a');
	var content = $('main');
	
	function initContent()
	{
		var sections = content.find('section');
		var leafSections = sections.filter(function(index)
		{
			var isLeaf = $(this).children('section').length === 0;
			return isLeaf;
		});
		
		sections.on('click', 'h1, h2', function()
		{
			var url = content.data('page') + '/' +
				$(this).closest('section').data('id') + '/';
			swapContent(url);
		});
		
		leafSections.on('mouseenter', function()
		{
			leafSections.stop();
			leafSections.not(this).animate({opacity: 0.5}, 'slow');
			$(this).animate({opacity: 1}, 'slow');
		});
		
		leafSections.on('mouseleave', function()
		{
			leafSections.stop();
			leafSections.animate({opacity: 1}, 'slow');
		});
		
		content.find('a.ajax').on('click', function(event)
		{
			event.stopPropagation();
			event.preventDefault();
			
			var url = $(this).attr('href');
			swapContent(url);
			
			navlinks.removeClass('selected');
			navlinks.filter('[href=\'' + url + '\']').addClass('selected');
		});
		
		content.find('a.img-box').on('click', function(event)
		{
			event.stopPropagation();
			event.preventDefault();
			$.featherlight('', {image: $(this).attr('href')});
		})
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

	navlinks.on('click', function(event)
	{
		event.stopPropagation();
		event.preventDefault();
		
		var link = $(this);
		
		navlinks.removeClass('selected');
		link.addClass('selected');
		
		swapContent(link.attr('href'));
	});
	
	initContent();
});