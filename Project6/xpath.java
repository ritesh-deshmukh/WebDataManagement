// Project 6
// Name: Ritesh Deshmukh

// package xpath;

import javax.xml.xpath.*;
import org.xml.sax.InputSource;
import org.w3c.dom.*;

class XPATH {

    static void print ( Node e ) {
        if (e instanceof Text)
            System.out.print(((Text) e).getData());
        else {
            NodeList c = e.getChildNodes();
            System.out.print("<"+e.getNodeName());
            NamedNodeMap attributes = e.getAttributes();
            for (int i = 0; i < attributes.getLength(); i++)
                System.out.print(" "+attributes.item(i).getNodeName()
                        +"=\""+attributes.item(i).getNodeValue()+"\"");
            System.out.print(">");
            for (int k = 0; k < c.getLength(); k++)
                print(c.item(k));
            System.out.print("</"+e.getNodeName()+">");
        }
    }

    static void eval ( String query, String document ) throws Exception {
        XPathFactory xpathFactory = XPathFactory.newInstance();
        XPath xpath = xpathFactory.newXPath();
        InputSource inputSource = new InputSource(document);
        NodeList result = (NodeList) xpath.evaluate(query,inputSource,XPathConstants.NODESET);
        // String newLine = System.getProperty("line.separator");
        System.out.println("XPath query: "+query);
        for (int i = 0; i < result.getLength(); i++){
            print(result.item(i));
            System.out.println();
        }
        System.out.println("----------------------------------------------");
    }

    public static void main ( String[] args ) throws Exception {
        // Example Query >>
        // eval("//gradstudent[name/lastname='Aboulnaga']/phone/text()","cs.xml");

        // Query 1: Print the titles of all articles whose one of the authors is David Maier. >>
        eval("//issue/articles/article[authors/author='David Maier']/title/text()","SigmodRecord.xml");

        // Query 2: Print the titles of all articles whose first author is David Maier. >>
        eval("//issue/articles/article[authors/author [@position=00]='David Maier']/title/text()","SigmodRecord.xml");

        // Query 3: Print the titles of all articles whose authors include David Maier and Stanley B. Zdonik. >>
        eval("//issue/articles/article[authors/author='David Maier' and authors/author='Stanley B. Zdonik.' ]/title/text()","SigmodRecord.xml");

        // Query 4: Print the titles of all articles in volume 19/number 2. >>
        eval("//issue[volume='19' and number='2']/articles/article/title/text()","SigmodRecord.xml");

        // Query 5: Print the titles and the init/end pages of all articles in volume 19/number 2 whose authors include Jim Gray. >>
        eval("//issue[contains(volume,'19') and contains(number, '2')]/articles/article/authors[author='Jim Gray']/parent::article/*[self::title or self::initPage or self::endPage]/text()","SigmodRecord.xml");

        // Query 6: Print the volume and number of all articles whose authors include David Maier. (note: we need the number entry of an article, not the number of articles). >>
        eval("//issue/articles/article/authors[author='David Maier']/parent::article/parent::articles/parent::issue/*[self::volume or self::number]/text()","SigmodRecord.xml");

    }
}
