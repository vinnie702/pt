SHOW TABLES;


SELECT * FROM companies;

explain companyUsers;

SELECT trackingItemID, COUNT(*) as cnt
FROM trackingItemUserAssign
GROUP BY trackingItemID
order by cnt desc
limit 4



SELECT DISTINCT userid FROM trackingItemUserAssign WHERE trackingItemID = 1;


SELECT * FROM codes;

SELECT * FROM users ORDER BY id DESC;

SELECT id, firstname, lastName, status, pptViewType FROM users
WHERE id = 1

select * from trackingItemUserAssign

select * FROM trackingItems WHERE id = 34;


explain users;

SELECT *
-- truncate table trackingItems;

SELECT * FROM userCompanies;

SELECT * FROM userCompanyPositions
WHERE userid = 105

SELECT * FROM positions WHERE id = 44;


SELECT name FROM positions;

SELECT * FROM userPositions;



SELECT * FROM userCompanies WHERE userid = 105;

SELECT * FROM trackingItemPrices
WHERE trackingItemID = 2
ORDER BY datestamp DESC 
LIMIT 1


explain trackingItems;

select * FROM trackingItems;

select
id
, datestamp
, userid
, company
, itemID
, itemName
, imgUrl
, status
,lastUpdated
FROM trackingItems
WHERE id = 34
ORDER BY id desc;

-- UPDATE trackingItems SET itemName = '' WHERE id = 34


-- ALTER TABLE trackingItems ADD COLUMN `imgUrl` VARCHAR(300) DEFAULT NULL AFTER `description`;
-- ALTER TABLE trackingItems ADD COLUMN `lastUpdated` DATETIME DEFAULT NULL;

ALTER TABLE users ADD COLUMN `pptViewType` SMALLINT(5) UNSIGNED DEFAULT 0;

explain users;

SELECT * FROM users;

explain trackingItemsHtml;

select * from trackingItemsHtml
WHERE trackingItemID = 34
ORDER BY datestamp desc

explain trackingItemUserAssign;

SELECT * FROM trackingItemUserAssign 

WHERE userid = 105;

select * from trackingItemUserAssign;


SELECT *
FROM trackingItemPrices
WHERE trackingItemID = 3
AND price <> '56.63'
ORDER BY datestamp DESC
LIMIT 1;


SELECT *
FROM trackingItemPrices
WHERE trackingItemID = 3
-- AND price <> '56.63'
ORDER BY datestamp DESC
LIMIT 1, 1;


SELECT *
FROM trackingItemPrices
WHERE trackingItemID = 3
AND priceDay <> '2014-01-18'
ORDER BY datestamp DESC
LIMIT 1

SELECT *
FROM (`trackingItemPrices`)
WHERE `trackingItemID` =  3
AND `priceDay` <> '2014-01-18'
ORDER BY `datestamp` desc
LIMIT 1


explain trackingItemPrices
SELECT * FROM trackingItemPrices;


-- DELETE FROM trackingItemsHtml;

DELETE FROM trackingItemsHtml WHERE trackingItemID = 34;


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


SELECT * FROM trackingItemPrices WHERE trackingItemID = 34;

-- DELETE FROM trackingItemPrices WHERE id = 1119;
