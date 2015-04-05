(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-61561301-1', 'auto');
ga('send', 'pageview');

$(document).ready(function()
{
	// decrease font size for IE10 and IE11
	if ((navigator.appVersion.indexOf("MSIE 10") !== -1) ||
		(navigator.userAgent.indexOf("Trident") !== -1 && navigator.userAgent.indexOf("rv:11") !== -1))
	{
		$('body').css({fontSize: '16px'});
	}


	$(window).bind('statechange', function()
	{
		var state = History.getState();
		var url = (state.data.page) ? state.data.page : '/';
		swapContent(url);
	});

	var navlinks = $('nav').children('a');
	var content = $('main');

	navlinks.on('click', function(event)
	{
		event.stopPropagation();
		event.preventDefault();
		loadPage($(this).attr('href'));
	});


	function loadPage(page)
	{
		History.pushState({page: page}, document.title, page);
	}

	function initContent()
	{
		var sections = content.find('section');

		sections.on('click', 'h1, h2', function()
		{
			var url = content.data('page') + '/' +
				$(this).closest('section').data('id') + '/';
			loadPage(url);
		});


		var leafSections = sections.filter(function(index)
		{
			var isLeaf = $(this).children('section').length === 0;
			return isLeaf;
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


		var links = content.find('a');

		links.filter('.ajax').on('click', function(event)
		{
			event.stopPropagation();
			event.preventDefault();
			loadPage($(this).attr('href'));
		});

		links.filter('.scroll').on('click', function(event)
		{
			event.stopPropagation();
			event.preventDefault();
			var anchor = $(this).attr('href').split('#', 2)[1];
			var position = links.filter('[name=' + anchor + ']').offset().top;
			$('html, body').animate({ scrollTop: position }, 'slow');
		});

		links.filter('.img-box').on('click', function(event)
		{
			event.stopPropagation();
			event.preventDefault();
			$.featherlight('', {image: $(this).attr('href')});
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
					var page = url.split('/', 1);

					content.data('page', page);
					content.html(result);
					content.animate({opacity: 1}, 'fast');
					initContent();

					navlinks.removeClass('selected');
					navlinks.filter('[href=\'' + page + '/\']').addClass('selected');

					//History.pushState({page: url}, document.title, url);
					ga('send', 'pageview', url);
				}
			});
		});
	};

	initContent();
});
