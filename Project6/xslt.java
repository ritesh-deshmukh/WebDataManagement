// Project 6
// Name: Ritesh Deshmukh

// package xslt;

import javax.xml.parsers.*;
import org.w3c.dom.*;
import javax.xml.transform.*;
import javax.xml.transform.dom.*;
import javax.xml.transform.stream.*;
import java.awt.*;
import java.io.*;


class XSLT {
    public static void main ( String argv[] ) throws Exception {
        File stylesheet = new File("recipe.xsl");
        File xmlfile  = new File("recipes.xml");
        DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
        DocumentBuilder db = dbf.newDocumentBuilder();
        Document document = db.parse(xmlfile);
        StreamSource stylesource = new StreamSource(stylesheet);
        TransformerFactory tf = TransformerFactory.newInstance();
        Transformer transformer = tf.newTransformer(stylesource);
        DOMSource source = new DOMSource(document);
        StreamResult result = new StreamResult("recipe_test.html");
        transformer.transform(source,result);
        File html = new File("recipe_test.html");
        Desktop.getDesktop().browse(html.toURI());
    }
}
