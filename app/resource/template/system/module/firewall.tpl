<?xml version="1.0" encoding="utf-8"?>
<root>
	<roles name="role.{$src}">
		<role name="USER" />
	</roles>
	<config>
		<login>
			<source name="" vars=""/>
		</login>
		<default>
			<source name="" vars=""/>
		</default>
		<forbidden template=".app/error/firewall">
			<variable type="lang" name="title" value="system.firewall.forbidden.title"/>
			<variable type="lang" name="message" value="system.firewall.forbidden.content"/>
		</forbidden>
		<csrf name="token.gcs" template=".app/error/firewall" enabled="true">
			<variable type="lang" name="title" value="system.firewall.csrf.title"/>
			<variable type="lang" name="message" value="system.firewall.csrf.content"/>
		</csrf>
		<logged name="logged.{$src}"/>
	</config>
</root>