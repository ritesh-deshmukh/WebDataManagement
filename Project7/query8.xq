<li>Query8</li>,
let $auc := doc("auction.xml")/site/open_auctions//open_auction
for $bidder in $auc
return
{
let $x := $bidder/bidder/personref/@person
let $y := $bidder/bidder/personref/@person
where( (index-of(($x),'person3')) < (index-of(($y),'person6')) ) 
return <li> { $bidder/@id, $bidder/reserve } </li>
}
