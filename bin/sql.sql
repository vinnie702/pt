
show tables;


SELECT * FROM companies;




-- truncate table trackingItems;

SELECT * FROM userCompanies;

    explain trackingItems;

select * FROM trackingItems;

select
id
, datestamp
, userid
, company
, url
, itemID
, itemName
, imgUrl
, status
,lastUpdated
FROM trackingItems

-- ALTER TABLE trackingItems ADD COLUMN `imgUrl` VARCHAR(300) DEFAULT NULL AFTER `description`;
-- ALTER TABLE trackingItems ADD COLUMN `lastUpdated` DATETIME DEFAULT NULL;

explain trackingItemsHtml;

select * from trackingItemsHtml

explain trackingItemUserAssign;

select * from trackingItemUserAssign;


SELECT * FROM trackingItemPrices WHERE trackingItemID = 7;


DELETE FROM trackingItemPrices WHERE trackingItemID = 7;


-- truncate table trackingItemPrices;

explain trackingItemPrices;

SELECT * FROM trackingItemPrices;


SELECT * FROM trackingItemPrices
WHERE trackingItemID = 2
ORDER BY datestamp DESC

SELECT price
FROM trackingItemPrices
WHERE trackingItemID = 2
AND priceDay = '2014-01-05'
