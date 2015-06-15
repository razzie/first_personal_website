(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-61561301-1', 'auto');
ga('send', 'pageview');

$(document).ready(function()
{
	var title = $('title:first');
	var navlinks = $('nav li').children('a');
	var content = $('main:first');

	function initContent()
	{
		content.find('script').each(function()
		{
			eval( $( this ).html() );
		});
		
		var sections = content.find('section');
		var leafSections = sections.filter(function(index)
		{
			var isLeaf = $(this).children('section').length === 0;
			return isLeaf;
		});
		leafSections.on('mouseenter', function()
		{
			if ($(window).width() < 800) return;
			leafSections.not(this).css({opacity: 0.5});
			$(this).css({opacity: 1});
		});
		leafSections.on('mouseleave', function()
		{
			if ($(window).width() < 800) return;
			leafSections.css({opacity: 1});
		});

		content.find('a').filter('.ajax, .scroll, .img-box')
			.on('click', function(event)
		{
			event.stopPropagation();
			event.preventDefault();
			
			var link = $(this);
			
			if (link.hasClass('ajax'))
				loadPage(link.attr('href'));
			else if (link.hasClass('img-box'))
				$.featherlight('', {image: $(this).attr('href')});
			else if (link.hasClass('scroll'))
			{
				var anchor = link.attr('href').split('#', 2)[1];
				var position = sections.filter('[id=' + anchor + ']').offset().top;
				$('html, body').animate({ scrollTop: position }, 'slow');
			}
		});
	
		title.text(content.find('h1:first').text() + ' - Gábor Görzsöny');
	};

	function loadContent(url)
	{
		content.animate({opacity: 0}, 'fast', 'swing', function()
		{
			$.ajax(
			{
				'url': 'core/page.php?p=' + url,
				'dataType': 'html',
				'success': function(result)
				{
					var page = url.split('/', 1);

					content.data('page', page);
					content.html(result);
					content.animate({opacity: 1}, 'fast');
					initContent();

					navlinks.removeClass('selected');
					navlinks.filter('[href=\'' + page + '/\']').addClass('selected');

					ga('send', 'pageview', url);
				}
			});
		});
	};

	function loadPage(page)
	{
		History.pushState({page: page}, 'Loading..' /*document.title*/, page);
	}
	
	navlinks.on('click', function(event)
	{
		event.stopPropagation();
		event.preventDefault();
		loadPage($(this).attr('href'));
	});

	$(window).bind('statechange', function()
	{
		var state = History.getState();
		var url = (state.data.page) ? state.data.page : '/';
		loadContent(url);
	});

	initContent();
});
