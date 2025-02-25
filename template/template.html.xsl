<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:php="http://php.net/xsl"
  extension-element-prefixes="php"
  exclude-result-prefixes="php"
>

  <xsl:output
    method="html"
    version="5"
    encoding="utf-8"
    indent="yes"
    omit-xml-declaration="yes"
    standalone="no"

  />


  <xsl:template name="htmlBody">
    
  </xsl:template>


  <xsl:template name="htmlHead">
    <title><xsl:value-of select="head/title" /></title>
    <link rel="shortcut icon" href="/public/favicon.ico" type="image/x-icon" />

    <xsl:for-each select="head/elements/node">
      <xsl:call-template name="element"/>
    </xsl:for-each>
  </xsl:template>



  <xsl:template match="/root">
    <html>
      <head>
        <xsl:call-template name="htmlHead"/>
      </head>
      <body>
        <xsl:call-template name="htmlBody"/>

        <xsl:for-each select="body/elements/node">
          <xsl:call-template name="element"/>
        </xsl:for-each>
      </body>
    </html>
  </xsl:template>



  <xsl:template name="element">
    <xsl:param name="elementName" select="elementName" />
    <xsl:param name="attributes" select="attributes/*" />
    <xsl:param name="content" select="content/text()" />

    <xsl:element name="{$elementName}">
      <xsl:for-each select="$attributes">
        <xsl:attribute name="{name()}"><xsl:value-of select="text()" /></xsl:attribute>
      </xsl:for-each>
      <xsl:value-of select="$content" />
    </xsl:element>
  </xsl:template>


</xsl:stylesheet>