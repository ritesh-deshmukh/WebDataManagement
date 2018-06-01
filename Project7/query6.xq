<li>Query6</li>,
let $person := doc('auction.xml')//people/person
let $auction_item_buy := doc('auction.xml')//closed_auctions/closed_auction
let $europe_item := doc('auction.xml')//regions/europe/item
for $a in $person
	for $b in $auction_item_buy
		for $c in $europe_item
			where $b/itemref/@item = $c/@id 
			where $b/buyer/@person = $a/@id
			group by $a/@id
			return <li> Person name = {$a/name}, Items = { $c/name } </li>