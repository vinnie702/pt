
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
FROM trackingItems;

-- ALTER TABLE trackingItems ADD COLUMN `imgUrl` VARCHAR(300) DEFAULT NULL AFTER `description`;
-- ALTER TABLE trackingItems ADD COLUMN `lastUpdated` DATETIME DEFAULT NULL;

explain trackingItemsHtml;

select * from trackingItemsHtml

explain trackingItemUserAssign;

select * from trackingItemUserAssign;


-- truncate table trackingItemPrices;

explain trackingItemPrices;

SELECT * FROM trackingItemPrices;
