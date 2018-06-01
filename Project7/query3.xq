<li>Query3</li>,
for $b in doc('auction.xml')/site/people/person
return 
<li> Name =  { ($b//name) } , No. of items bought =  { count($b//watches/watch) }  </li>