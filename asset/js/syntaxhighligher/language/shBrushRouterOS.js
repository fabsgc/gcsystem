/**
 * SyntaxHighlighter
 * http://alexgorbatchev.com/
 *
 * This brush was originally provided by <unknown>
 * homepage:   http://www.minitw.com
 * brush page: http://www.minitw.com/archives/252
 */
 

SyntaxHighlighter.brushes.RouterOS = function()
{

    var keywords =  'global local do else for from to step in foreach ' +
                    'if put while environment nothing set';

    var commands =  'add comment disable enable export get move remove unset ' +
                    'delay edit blink monitor beep find led len  ' +
                    'list log pick resolve time print toid totime tonum';

        this.regexList = [
                { regex: /#.*$/gm, css: 'comments' },
                { regex: SyntaxHighlighter.regexLib.doubleQuotedString,                 css: 'string' },                // double quoted strings
                { regex: new RegExp(this.getKeywords(keywords), 'gm'),                  css: 'keyword' },               // keywords
                { regex: new RegExp(this.getKeywords(commands), 'gm'),                  css: 'functions' }              // commands
                ];
}

SyntaxHighlighter.brushes.RouterOS.prototype    = new SyntaxHighlighter.Highlighter();
SyntaxHighlighter.brushes.RouterOS.aliases      = ['ros'];