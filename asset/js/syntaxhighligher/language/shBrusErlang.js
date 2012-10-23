/**
 * SyntaxHighlighter
 * http://alexgorbatchev.com/
 *
 * This brush was originally created by Will Larson
 * homepage:   http://lethain.com/
 * brush page: http://lethain.com/entry/2009/sep/15/erlang-brush-for-syntaxhighlighter/
 * profile:    http://lethain.com/author/will-larson/
 */
 
SyntaxHighlighter.brushes.Erlang = function() {
    this.regexList = [
        { regex: /&lt;|\&gt;/gm ,css: 'plain' },
        { regex: /[a-z](a-zA-Z0-9)*/gm, css: "color2"},
        { regex: /%(.*)$/gm, css: "comments"},
        { regex: /"(.*)"/gm, css: "string"},
        { regex: /[A-Z](\w*)/gm, css: "value"},
        { regex: /\??[A-Z][A-Z0-9]+/gm, css: 'keyword' }
    ];
}
SyntaxHighlighter.brushes.Erlang.prototype = new SyntaxHighlighter.Highlighter();
SyntaxHighlighter.brushes.Erlang.aliases = ['erlang', 'erl'];