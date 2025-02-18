<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="html" encoding="UTF-8" indent="yes" />

  <xsl:template match="/root">
    <html>
      <title>Test</title>
      <style>
        table {
          border: none;
          border-collapse: collapse;
        }
        table th, table td {
          padding: 10px 20px;
          border: 1px solid #aaa;
        }

        a {
          color: black;
        }
      </style>
      <body>

        <ul>
          <li>Objednávky do ID 500 existují a vrátí data</li>
          <li>Objednávka nad ID 500 neexistují</li>
          <li>U objednávky ID 2 vyhodí aplikace chybu</li>
          <li>Výstup (JSON/XML) lze ovlivnit query parametrem <em>format</em> nebo hlavičkou <em>Accept</em></li>
          <li><em>Produkce / Vývoj</em> je napevno nastaveno v <em>htdocs/index.php</em></li>
        </ul>

        <table>
          <thead>
            <tr>
              <th>URL</th>
              <th>Popis</th>
            </tr>
          </thead>
          <tr>
            <td>
              <a href="/order/1/">/order/1/</a>
            </td>
            <td>Existující objednávka #1</td>
          </tr>
          <tr>
            <td>
              <a href="/order/1/?format=xml">/order/1/?format=xml</a>
            </td>
            <td>objednávka #1 ve formátu XML</td>
          </tr>
          <tr>
            <td>
              <a href="/order/2/">/order/2/</a>
            </td>
            <td>Umělá chyba v aplikaci (RuntimeException), zkusit při zapnuté produkci</td>
          </tr>
          <tr>
            <td>
              <a href="/order/2/?format=xml">/order/2/?format=xml</a>
            </td>
            <td>Umělá chyba v aplikaci (RuntimeException), zkusit při zapnuté produkci (formát XML)</td>
          </tr>
          <tr>
            <td>
              <a href="/order/500/">/order/500/</a>
            </td>
            <td>Neexistující objednávka (RepositoryException)</td>
          </tr>
          <tr>
            <td>
              <a href="/order/110/">/order/110/</a>
            </td>
            <td>Chyba hodnoty Order->id. Platná hodnota pouze mezi 1 a 100.</td>
          </tr>
          <tr>
            <td>
              <a href="/no-url-here/">/no-url-here/</a>
            </td>
            <td>Chyba 404</td>
          </tr>
          <tr>
            <td>
              <a href="/order/3/">/order/3/</a>
            </td>
            <td>Existující objednávka #3</td>
          </tr>
          <tr>
            <td>
              <a href="/order/4/">/order/4/</a>
            </td>
            <td>Existující objednávka #4</td>
          </tr>
          <tr>
            <td>
              <a href="/order/5/">/order/5/</a>
            </td>
            <td>Existující objednávka #5</td>
          </tr>
        </table>

      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>