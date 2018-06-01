<li>Query2</li>,
for $b in doc('auction.xml')/site/regions/europe/item
return 
<li> { ($b//name) } { ($b//description) } </li>