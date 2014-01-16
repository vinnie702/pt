

show tables;


SELECT * FROM companies;

explain companyUsers;

SELECT trackingItemID, COUNT(*) as cnt
FROM trackingItemUserAssign
GROUP BY trackingItemID
order by cnt desc
limit 4


SELECT * FROM codes;

SELECT * FROM users ORDER BY id DESC;

SELECT status FROM users;

select * from trackingItemUserAssign

select * FROM trackingItems WHERE id = 34;


SELECT *
-- truncate table trackingItems;

SELECT * FROM userCompanies;

SELECT * FROM userCompanyPositions
WHERE userid = 105

SELECT * FROM positions WHERE id = 44;


SELECT name FROM positions;

SELECT * FROM userPositions;

SELECT * FROM userCompanies WHERE userid = 105;


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
ORDER BY id desc;


-- ALTER TABLE trackingItems ADD COLUMN `imgUrl` VARCHAR(300) DEFAULT NULL AFTER `description`;
-- ALTER TABLE trackingItems ADD COLUMN `lastUpdated` DATETIME DEFAULT NULL;

explain trackingItemsHtml;

select * from trackingItemsHtml
WHERE trackingItemID = 34
ORDER BY datestamp desc

explain trackingItemUserAssign;

SELECT * FROM trackingItemUserAssign 

WHERE userid = 105;

select * from trackingItemUserAssign;


SELECT * FROM trackingItemPrices 
WHERE trackingItemID = 5;

explain trackingItemPrices
SELECT * FROM trackingItemPrices;


-- DELETE FROM trackingItemsHtml;

DELETE FROM trackingItemsHtml WHERE trackingItemID = 44;


SELECT * FROM trackingItemsHtml

DELETE FROM trackingItemPrices WHERE trackingItemID = 3;


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
