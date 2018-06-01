<li>Query7</li>,
for $a in doc('auction.xml')/site/regions//item
order by $a/name
return <li>Item name = { $a/name/text() } Item location = { $a/location/text() } </li>