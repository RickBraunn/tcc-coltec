/*! 
 * jQuery Bootgrid v1.3.1 - 09/11/2015
 * Copyright (c) 2014-2015 Rafael Staib (http://www.jquery-bootgrid.com)
 * Licensed under MIT http://www.opensource.org/licenses/MIT
 */
;(function ($, window, undefined)
{
    /*jshint validthis: true */
    "use strict";

    $.extend($.fn.bootgrid.Constructor.defaults.css, {
        icon: "icon fa",
        iconColumns: "fa-th-list",
        iconDown: "fa-sort-desc",
        iconRefresh: "fa-refresh",
        iconSearch: "fa-search",
        iconUp: "fa-sort-asc"
	});
    $.extend($.fn.bootgrid.Constructor.defaults.labels, {
        all: "Todos",
        infos: "Exibindo \{\{ctx.start\}\} até \{\{ctx.end\}\} de \{\{ctx.total\}\}",
        loading: "Carregando...",
        noResults: "Resultados não encontrados!",
        refresh: "Recarregar",
        search: "Buscar"
    });
})(jQuery, window);