jQuery(function($) {
	
	// Return a helper with preserved width of cells
	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			var $cell = $(this);
			$cell.width($cell.width());
		});
		return ui;
	};
	
	var sortUpdate = function(event, ui) {
		var query = $(this).sortable('serialize');
		query += '&' + $.param(paginator);
		$.post(baseUrl + '/' + paginator['controller'] + '/sort', query);
	};
	
	$('.sortable table tbody')
		.sortable({
			'helper': fixHelper,
			'containment': $('.index table'),
			'forcePlaceholderSize': true,
			'distance': 10,
			'update': sortUpdate
		})
		.disableSelection();
});

/*jQuery(function($) {
	var CakePHP = function() {
		this._namedParams = false;
	};
	
	// This is a *ridiculously* simplified Javascript implementation. It really only works with very basic use cases.
	CakePHP.namedParameter = function(name) {
		if (this._namedParams === false) {
			this._parseNamedParams();
		}
		return this._namedParams[name];
	}
	
	CakePHP._parseNamedParams = function() {
		this._namedParams = {};
		var params = window.location.pathname.split('/');
		for (var i in params) {
			var parts = params[i].replace('[').split(/[\]:]+/);
			if (parts.length > 1) {
				var param;
				for (var x = 0; x < parts.length - 2; x++) {
					param
				}
				this._namedParams[parts[parts.length - 2]] = parts[parts.length - 1];
			}
		}
	};
});*/

(function($) {

	var RelatedModel = function($related, options) {
        var settings = $.extend({}, $.fn.relatedModel.defaults, options);
		
		var openModel = function() {
			var $link = $(this);
			RelatedModel.load($link.attr('href'), $related);
			return false;
		};
		
		var deleteRecord = function(event) {
			if (event.isDefaultPrevented()) {
				return false;
			}
			var $link = $(this);
			if (!RelatedModel.submitting) {
				RelatedModel.submitting = true;
				$.post($link.attr('href'), false, function(data) {
					$related.html(data.content);
					RelatedModel.submitting = false;
				}, 'json');
			}
			return false;
		};
		
		$related.on('click', '.actions a[href*="/edit/"], .actions a[href*="/add"]', openModel);
		$related.on('click', '.actions a[href*="/delete/"]', deleteRecord);
	};
	
	// Static
	RelatedModel.isInitialized = false;
	RelatedModel.open = false;
	RelatedModel.submitting = false;
	RelatedModel.div = false;
	
	RelatedModel.initialize = function() {
		if (RelatedModel.isInitialized) {
			return;
		}
		RelatedModel.isInitialized = true;
		
		RelatedModel.div = $('<div></div>');
		RelatedModel.div.appendTo('body');
		RelatedModel.div.dialog({
			modal: true,
			width: '76%',
			autoOpen: false,
			close: function(event, ui) {
				RelatedModel.open = false;
			}
		});
	};
	
	RelatedModel.load = function(url, $related) {
		RelatedModel.initialize();
		if (!RelatedModel.open) {
			RelatedModel.open = true;
			RelatedModel.div.load(url, false, function() {
				RelatedModel.div.dialog('open');
				var $form = RelatedModel.div.find('form');
				$form.submit(function() {
					if (!RelatedModel.submitting) {
						RelatedModel.submitting = true;
						$.post($form.attr('action'), $form.serialize(), function(data) {
							RelatedModel.submitting = false;
							if (data.success) {
								$related.html(data.content);
								RelatedModel.div.dialog('close');
							} else {
								RelatedModel.div.html(data.content);
							}
						}, 'json');
					}
					return false;
				});
			});
		}
	};
	
	$.fn.relatedModel = function(options) {
		return this.each(function(key, value){
            var $element = $(this);
            if ($element.data('relatedModel')) {
				return $element.data('relatedModel');
			}
            var relatedModel = new RelatedModel($element, options);
            $element.data('relatedModel', relatedModel);
        });
	};
	
	$.fn.relatedModel.defaults = {
	};

})(jQuery);

/*jQuery(function($){
	
	var createForm = function() {
		var $link = $(this);
		var $div = $('<div></div>');
		var open = false;
		var $iframe = $('<iframe></iframe>');
		
		$div.attr({
				'title': $link.attr('title'),
				'class': 'sub-form'
			})
			.appendTo('body');
		
		$iframe
			.load(function (){
				var html = $('.form, .index', $iframe.get(0).contentWindow.document).html();
				$div.html(html);
			})
			.attr({
				src: baseUrl + 'piece_o_cake/blank.html',
				name: 'sub-form-iframe'
			})
			.css('display', 'none')
			.appendTo('body');
		
		$div.dialog({
			modal: true,
			width: '76%',
			autoOpen: false
		});
		
		$div.on('sub-form-update', function() {
			open = true;
			$div.dialog('close');
			$('.sub-form-index').find('tr').not(':first-child').remove();
			$('.sub-form-index').append($div.find('tr').not(':first-child'));
			$div.remove();
			$iframe.remove();
		});
		
		var load = function(url, data) {
			$div.load(url, data, function() {
				if (!open) {
					$div.dialog('open');
					open = true;
				}
				var $form = $div.find('form');
				$form.attr('target', 'sub-form-iframe');
			});
		};
		
		load($link.attr('href'), false);
		
		return false;
	};
	
	$('.sub-form-button').click(createForm);
	$('.sub-form-index').on('click', 'a[href*="/edit/"], a[href*="/delete/"]', createForm);
});*/