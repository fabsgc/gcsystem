<?xml version="1.0" encoding="utf-8"?>
<root>
	<roles name="role.{$src}">
		<role name="USER" />
	</roles>
	<config>
		<login>
			<source name="login" vars=""/>
		</login>
		<default>
			<source name="default" vars=""/>
		</default>
		<forbidden template=".app/error/firewall">
			<variable type="lang" name="title" value="system.firewall.forbidden.title"/>
			<variable type="lang" name="message" value="system.firewall.forbidden.message"/>
		</forbidden>
		<csrf name="token.{$src}" template=".app/error/firewall" enabled="true">
			<variable type="lang" name="title" value="system.firewall.csrf.title"/>
			<variable type="lang" name="message" value="system.firewall.csrf.message"/>
		</csrf>
		<logged name="logged.{$src}"/>
	</config>
</root>