jQuery(function ($) {
    
    if (!window.Awecms) {
        window.Awecms = {};
    }
    
    Awecms.templates = {};
    Awecms.getTemplate = function getHandlebarsTemplate(selector) {
        if (!Awecms.templates[selector]) {
            var source = $(selector).html();
            Awecms.templates[selector] = Handlebars.compile(source);
        }
        return Awecms.templates[selector];
    };

    Handlebars.registerHelper('template', function (selector) {
        var template = Awecms.getTemplate(selector);
        return new Handlebars.SafeString(template(this));
    });

    Handlebars.registerHelper('url', function (url) {
        if (arguments.length > 2) {
            var args = Array.prototype.slice.call(arguments);
            args.pop();
            url = args.join('/');
        }
        url = APP.BASE_URL + url;
        if (!url) {
            url = '#';
        }
        return url.replace(/[\/]+/g, '/');
    });

    Handlebars.registerHelper('_d', function (domain, key) {
        return key;
    });
});