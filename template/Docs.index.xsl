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

  <xsl:template match="/root">
    <html lang="en">
      <head>
        <meta charset="utf-8" />
        <title>SwaggerUI</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="SwaggerUI" />
        <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
        <link rel="shortcut icon" href="/public/favicon.ico" type="image/x-icon" />
      </head>

      <body>
        <div id="swagger-ui"></div>

        <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js" crossorigin=""></script>
        <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-standalone-preset.js" crossorigin=""></script>
        <script>
          window.onload = () => {
            window.ui = SwaggerUIBundle({
            url: 'http://localhost:8000/docs/swagger.json',
            dom_id: '#swagger-ui',
            presets: [
              SwaggerUIBundle.presets.apis,
              SwaggerUIStandalonePreset
            ],
              layout: "StandaloneLayout",
            });
          };
        </script>
      </body>
    </html>
  </xsl:template>


</xsl:stylesheet>