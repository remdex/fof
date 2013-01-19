// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// BBCode tags example
// http://en.wikipedia.org/wiki/Bbcode
// ----------------------------------------------------------------------------
// Feel free to add more tags
// ----------------------------------------------------------------------------
mySettings = {
	previewParserPath:	'', // path to your BBCode parser
	markupSet: [
		{name:'Bold', key:'B', openWith:'[b]', closeWith:'[/b]'},
		{name:'Italic', key:'I', openWith:'[i]', closeWith:'[/i]'},
		{name:'Underline', key:'U', openWith:'[u]', closeWith:'[/u]'},	
		{separator:'---------------' },		
		{name:'Bulleted list', openWith:'[list]\n', closeWith:'\n[/list]'},
		{name:'Numeric list', openWith:'[list=[![Starting number]!]]\n', closeWith:'\n[/list]'}, 
		{name:'List item', openWith:'[*] '},
		{separator:'---------------' },
		{name:'Quotes', openWith:'[quote]', closeWith:'[/quote]'},		
		{name:'Paypal donate button', openWith:'[paypal]', closeWith:'[/paypal]', placeHolder:'Your paypal email address'},	
		{name:'Flattr', openWith:'[flattr]', closeWith:'[/flattr]',placeHolder:'Link to flattr page...'},	
		{name:'Youtube', openWith:'[youtube]', closeWith:'[/youtube]',placeHolder:'Link to youtube video...'},		
		{name:'Picture', key:'P', replaceWith:'[img][![Url]!][/img]'},
		{name:'Link', key:'L', openWith:'[url=[![Url]!]]', closeWith:'[/url]', placeHolder:'Your text to link here...'},	
		{separator:'---------------' },				
        {name:':)', replaceWith:':) '},
        {name:':D', replaceWith:':D '},
        {name:':(', replaceWith:':( '},
        {name:':o', replaceWith:':o '},
        {name:':p', replaceWith:':p '},
        {name:';)', replaceWith:';) '},
		{separator:'---------------' },
		{name:'Clean', className:"clean", replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
		{name:'Preview', className:"preview", call:'preview' }
	]
}