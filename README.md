## API 實作測驗

## AsiaYo Exercise API

### 專案建立說明

透過下述 docker 指令啟用服務：

```bash
docker compose up 
```

可透過 curl 指令測試服務回應：

```bash
curl -X POST http://localhost:8000/api/orders \
     -H "Content-Type: application/json" \
     -d '{
           "id": "123",
           "name": "John Doe",
           "address": {
               "city": "Taipei",
               "district": "Xinyi",
               "street": "123 Main St"
           },
           "price": 1500,
           "currency": "USD"
         }'

```

### 專案中的 SOLID 原則

- **單一職責原則 (Single Responsibility Principle, SRP)**
    - 每個類別和函數都只負責一項職責。例如：`Request` 負責請求的欄位及資料型別驗證、 `Service` 負責處理業務邏輯，而 `Controller` 則管理請求及回應的整個流程。每個函數又在依職責劃分，例如在 `TWDOrderService` 中，`validateOrderData` 負責業務邏輯的資料檢查，而 `processOrder` 負責處理訂單的資料轉換。

- **開放封閉原則 (Open/Closed Principle, OCP)**
    - `OrderServiceInterface` 定義了一個介面，使得可以在不修改現有程式碼的情況下新增新的訂單處理服務。例如，若需要新增一個不同的貨幣處理邏輯，可以創建一個新的實現類別，如 `USDOrderService`，並實現其函數。

- **介面隔離原則 (Interface Segregation Principle, ISP)**
    - `OrderServiceInterface` 只包含兩個方法 `validateOrderData` 和 `processOrder`，這些方法是具體服務所需要的。沒有定義過多的方法，使得實現這個介面的類別不會因為不需要的方法而變得複雜。

- **依賴倒置原則 (Dependency Inversion Principle, DIP)**
    - `OrderController` 依賴於 `OrderServiceInterface` 這個抽象介面，而不是具體的 `TWDOrderService` 類別。這樣可以更容易替換具體的實現，使程式更加靈活和可測試。


## 資料庫測驗

### 題目一

> 請寫出一條查詢語句 (SQL)，列出在 2023 年 5 月下訂的訂單，使用台幣付款且5月總金額最
多的前 10 筆的旅宿 ID (bnb_id), 旅宿名稱 (bnb_name), 5 月總金額 (may_amount)

下方為根據題目所下的 SQL 查詢指令：

```
SELECT 
    o.bnb_id,
    b.name AS bnb_name,
    SUM(o.amount) AS may_amount
FROM 
    orders o
JOIN 
    bnbs b ON o.bnb_id = b.id
WHERE 
    o.created_at BETWEEN '2023-05-01' AND '2023-05-31'
    AND o.currency = 'TWD'
GROUP BY 
    o.bnb_id, b.name
ORDER BY 
    may_amount DESC
LIMIT 10;
```

### 題目二

> 在題目一的執行下，我們發現 SQL 執行速度很慢，您會怎麼去優化？請闡述您怎麼判斷與優化的方式。

要優化 SQL 查詢，以下是我會採取的幾個檢查步驟：

### 1. **分析查詢執行狀況**

首先，使用 `EXPLAIN` 來分析這個查詢的執行狀況，進一步了解查詢是如何被執行的，並找出潛在的瓶頸。

```sql
EXPLAIN SELECT 
    o.bnb_id,
    b.name AS bnb_name,
    SUM(o.amount) AS may_amount
FROM 
    orders o
JOIN 
    bnbs b ON o.bnb_id = b.id
WHERE 
    o.created_at BETWEEN '2023-05-01' AND '2023-05-31'
    AND o.currency = 'TWD'
GROUP BY 
    o.bnb_id, b.name
ORDER BY 
    may_amount DESC
LIMIT 10;
```

### 2. **檢查和添加索引**

根據分析結果，可以檢查是否需要為查詢中的表添加索引。例如：

- 為 `orders` 表的 `created_at`、`currency` 和 `bnb_id` 列添加複合索引。

```sql
CREATE INDEX idx_orders_created_at_currency_bnb_id ON orders (created_at, currency, bnb_id);
```

### 3. **調整資料庫參數**

根據伺服器的硬體配置和負載情況，調整資料庫參數（例如緩衝區大小、查詢快取等）來提高性能。

### 4. **檢查硬體資源**

檢查資料庫硬體的 CPU、記憶體使用狀況以及硬碟容量，例如：確保硬碟使用量在 70% 以下等。

### 5. **考慮資料庫分區**

如果 `orders` 表的數據量非常大，可以考慮資料將表分區，例如按日期分區，以提高查詢效率。
