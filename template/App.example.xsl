<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
>

  <xsl:import href="template.html.xsl" />

  <xsl:template name="htmlBody">
    <xsl:variable name="clientKey" select="clientKey" />
    <xsl:variable name="clientSecret" select="clientSecret" />
    <xsl:variable name="snippetUrl" select="snippetUrl" />

    <h1>Example of page for injecting comments </h1>

    <script>
      const clientKey = "<xsl:value-of select="$clientKey" />";
      const clientSecret = "<xsl:value-of select="$clientSecret" />";
      const snippetSrc = "<xsl:value-of select="$snippetUrl" />"

      var comLayer = {
        user : {
          code: "APP-CODE-1", name: 'Novák Jan', emailAddress: 'jan.novak@example.com'
        }
      };

      function renderConfig(opt) {
        document.getElementById('config').innerText = JSON.stringify(opt, null, 2)
      }


      (function(win, d, e, layerKey, key, secret) {
        win[layerKey] = win[layerKey] || [];

        var f= d.getElementsByTagName(e)[0];
        var j= d.createElement(e)
        j.async = true;
        j.dataset.type = 'stromcom';
        j.dataset.dl = layerKey;
        j.dataset.ck = key;
        j.dataset.cs = secret;
        j.src = snippetSrc;
        f.parentNode.insertBefore(j,f);

      })(window, document, "script", "comLayer", clientKey, clientSecret);


      window.addEventListener('load', function() {
        renderConfig({
          comLayer: comLayer,
          clientKey: clientKey,
          snippetSrc : snippetSrc,
        });
      });
    </script>

    <div data-sc-code="ORDER-1" data-sc-name="Objednávka 123" style="width:900px; height:350px;"></div>

    <pre id="config"></pre>


  </xsl:template>

</xsl:stylesheet>