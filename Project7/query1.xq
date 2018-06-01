<li>Query1</li>,
for $b in doc('auction.xml')/site/regions
return 
<li> Query 1: { count($b//item) } </li>