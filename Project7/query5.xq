<li>Query5</li>,
for $a in doc('auction.xml')//categories/category 
return <li> Interest Category Name = {$a/name} </li>, 
for $b in distinct-values(doc('auction.xml')/site/people/person/profile/interest/@category)
return  <li> Interest Category ID = { $b } </li> ,
let $text := <grp> Size of Group = </grp>
return  $text/text(),
for $c in doc('auction.xml')//categories/category
let $d := {$c/@id}
return
count( for $e in doc('auction.xml')//people/person
where $e/profile/interest/@category = $d
return { { $e/name } } )