/**
 *
 * IBM ILE RPG brush file.
 * Current for version V6R1
 *
 * Written by Loyd Goodbar <loyd@blackrobes.net>, 2009.
 * getFunctions() code from helen at alexgorbatchev.com forums.
 *
 */
SyntaxHighlighter.brushes.Rpgle = function()
{
	var bifs = '%abs %addr %alloc %bit(and|not|or|xor) %char %check(r)? %date %days '+
		'%dec(h|pos)? %diff %div %edit(c|flt|w) %elem %eof %equal %error %fields %float '+
		'%found %graph %handler %hours %int %kds %len %lookup(lt|ge|gt|le)? %minutes '+
		'%months %mseconds %nullind %occur %open %paddr %parms %realloc %rem %replace '+
		'%scan %seconds %shtdn %size %sqrt %status %str %subarr %subdt %subst %this '+
		'%time(stamp)? %tlookup(lt|ge|gt|le)? %trim(l|r)? %ucs2 %uns %unsh %xfoot '+
		'%xlate %xml %years';

	var opcodes = 'acq add(dur)? alloc and(gt|lt|eq|ne|ge|le)? begsr bit(off|on) '+
		'cab(gt|lt|eq|ne|ge|le) call(b|p)? cas(gt|lt|eq|ne|ge|le) cat chain check(r)? '+
		'clear close commit comp dealloc define delete div do dou(gt|lt|eq|ne|ge|le)? '+
		'dow(gt|lt|eq|ne|ge|le)? dsply dump else(if)? end(cs|do|for|if|mon|sl|sr)? '+
		'eval(r|-corr)? except exfmt exsr extrct feod for force free goto '+
		'if(gt|lt|eq|ne|ge|le)? in iter kfld klist leave(sr)? lookup m(h|l){2}zo monitor '+
		'move(a|l)? mult mvr next occur on-error open or(gt|lt|eq|ne|ge|le)? other out '+
		'parm plist post read(c|e|p|pe)? realloc rel reset return rolbk scan select '+
		'set(gt|ll|off|on) shtdn sorta sqrt sub(dur|st)? tag test(b|n|z)? time unlock '+
		'update when(gt|lt|eq|ne|ge|le)? write xfoot xlate xml-(into|sax) z-(add|sub)';

	var ckeywords = 'actgrp altseq alwnull aut bnddir ccsid copy(nest|right) cursym '+
		'cvtopt dat(edit|fmt) debug dec(edit|prec) dft(actgrp|name) enbprfcol expropts '+
		'extbinint fixnbr fltdiv formsalign ftrans genlvl indent intprec langid (no)?main '+
		'openopt optimize option pgminfo prfdta srtseq text thread timfmt truncnbr usrprf';

	var fkeywords = 'block commit datfmt devid ext(desc|file|ind|mbr) form(len|ofl) ignore '+
		'include indds infsr keyloc likefile maxdev oflind pass pgmname plist prefix '+
		'prtctl qualified rafdata recno rename saveds saveind sfile sln static template '+
		'timfmt usropn';

	var dkeywords = 'align alt(seq)? ascend based ccsid class const ctdata datfmt '+
		'descend dim dtaara export ext(fld|fmt|name|pgm|proc) fromfile import inz '+
		'like(ds|file|rec)? noopt occurs opdesc options overlay packeven perrcd prefix '+
		'procptr qualified static template timfmt tofile value varying';

	var pkeywords = 'export serialize';

	var figuratives = '[*]{2}ctdata [*]blanks? [*]zeros? [*](hi|lo)val [*]null [*]on [*]off '+
		'[*]all(x|g)? [*]start [*]end';

	var directives = '[/](end-)?(free|exec) [/](copy|eject|else|eof|include|space|title) '+
		'[/](un)?define [/](else|end)?if';

	var indicators = '[*]?in([01-99]|lr|(h|l)[1-9])';

	// Comments starting at column 7 for fixed format.
	var fixedcomments = '^.{6}[*].*$';

	this.getFunctions = function(list)
	{
		return "(?:" + list.replace(/\s+/g, "|") + ")\\b";
	};

	this.regexList = [
		{ regex: SyntaxHighlighter.regexLib.singleLineCComments, css: 'comments' },
		{ regex: new RegExp(fixedcomments, 'gm'), css: 'comments' },
		{ regex: new RegExp(this.getFunctions(directives), 'gmi'), css: 'color1' },
		{ regex: new RegExp(this.getFunctions(bifs), 'gmi'), css: 'functions' },
		{ regex: new RegExp(this.getKeywords(opcodes), 'gmi'), css: 'keyword' },
		{ regex: new RegExp(this.getFunctions(indicators), 'gmi'), css: 'color3' },
		{ regex: new RegExp(this.getFunctions(figuratives), 'gmi'), css: 'color3' },
		{ regex: new RegExp(this.getKeywords(ckeywords), 'gmi'), css: 'color3' },
		{ regex: new RegExp(this.getKeywords(fkeywords), 'gmi'), css: 'color3' },
		{ regex: new RegExp(this.getKeywords(dkeywords), 'gmi'), css: 'color3' },
		{ regex: new RegExp(this.getKeywords(pkeywords), 'gmi'), css: 'color3' }
	];
};

SyntaxHighlighter.brushes.Rpgle.prototype = new SyntaxHighlighter.Highlighter();
SyntaxHighlighter.brushes.Rpgle.aliases = ['rpgle','rpg4'];