<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version = "1.0"
        xmlns:xsl ="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <body>
                <h1>SOME RECIPES</h1>
                <table border="1">
                    <tr>
                        <th width="250px">Title/Date</th>
                        <th>Ingredients</th>
                        <th>Method</th>
                        <th>Related</th>
                        <th>Comments</th>
                        <th>Nutrition</th>
                    </tr>
                    <xsl:for-each select="collection/recipe">
                        <tr>
                        <td><b><xsl:value-of select="title"/></b>&#160;&#160;
                            <xsl:value-of select="date"/></td>
                        <td><ol><xsl:apply-templates select="ingredient"/></ol></td>
                        <td><xsl:value-of select="preparation"/></td>
                        <td><xsl:value-of select="related"/></td>
                        <td><b>Comments - </b><i><xsl:value-of select="comment"/></i></td>
                        <td>
                            <p><b>Calories: </b><xsl:value-of select="nutrition/@calories"/></p>
                            <p><b>Fat: </b><xsl:value-of select="nutrition/@fat"/></p>
                            <p><b>Carbohydrates: </b><xsl:value-of select="nutrition/@carbohydrates"/></p>
                            <p><b>Protein: </b><xsl:value-of select="nutrition/@protein"/></p>
                        </td>
                        </tr>
                    </xsl:for-each>
                </table>

            </body>
        </html>
    </xsl:template>
    <xsl:template match="ingredient">
        <xsl:choose>
            <xsl:when test="@amount">
                <li>
                    <b><xsl:value-of select="@amount"/></b>&#160;
                    <xsl:value-of select="@unit"/>&#160;
                    <b><xsl:value-of select="@name"/></b>
                </li>
            </xsl:when>
            <xsl:otherwise>
                <b><li><xsl:value-of select="@name"/></li></b>
                <ol><xsl:apply-templates select="ingredient"/></ol>
                <xsl:apply-templates select="preparation"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
</xsl:stylesheet>