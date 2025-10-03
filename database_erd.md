# E-commerce Database ERD

## Entity Relationship Diagram

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        enum role "admin,user"
        string phone
        timestamp created_at
        timestamp updated_at
    }

    CATEGORIES {
        bigint id PK
        string name
        string slug UK
        text description
        string image
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    SUBCATEGORIES {
        bigint id PK
        bigint category_id FK
        string name
        string slug UK
        text description
        string image
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    PRODUCTS {
        bigint id PK
        bigint category_id FK
        bigint subcategory_id FK
        string name
        string slug UK
        text description
        decimal price
        decimal sale_price
        integer stock_quantity
        string sku UK
        json images
        boolean is_active
        boolean is_featured
        decimal weight
        string dimensions
        timestamp created_at
        timestamp updated_at
    }

    CARTS {
        bigint id PK
        bigint user_id FK
        timestamp created_at
        timestamp updated_at
    }

    CART_ITEMS {
        bigint id PK
        bigint cart_id FK
        bigint product_id FK
        integer quantity
        decimal price
        timestamp created_at
        timestamp updated_at
    }

    WISHLISTS {
        bigint id PK
        bigint user_id FK
        timestamp created_at
        timestamp updated_at
    }

    WISHLIST_ITEMS {
        bigint id PK
        bigint wishlist_id FK
        bigint product_id FK
        timestamp created_at
        timestamp updated_at
    }

    ADDRESSES {
        bigint id PK
        bigint user_id FK
        string type "billing,shipping"
        string first_name
        string last_name
        string company
        string address_line_1
        string address_line_2
        string city
        string state
        string postal_code
        string country
        string phone
        boolean is_default
        timestamp created_at
        timestamp updated_at
    }

    ORDERS {
        bigint id PK
        bigint user_id FK
        bigint address_id FK
        string order_number UK
        enum status "pending,processing,shipped,delivered,cancelled"
        decimal subtotal
        decimal tax_amount
        decimal shipping_amount
        decimal discount_amount
        decimal total_amount
        string notes
        timestamp created_at
        timestamp updated_at
    }

    ORDER_ITEMS {
        bigint id PK
        bigint order_id FK
        bigint product_id FK
        string product_name
        string product_sku
        integer quantity
        decimal price
        decimal total
        timestamp created_at
        timestamp updated_at
    }

    PAYMENTS {
        bigint id PK
        bigint order_id FK
        string payment_method "stripe,paypal,cash"
        string transaction_id
        decimal amount
        enum status "pending,completed,failed,refunded"
        json payment_details
        timestamp created_at
        timestamp updated_at
    }

    COUPONS {
        bigint id PK
        string code UK
        string name
        enum type "percentage,fixed"
        decimal value
        decimal minimum_amount
        integer usage_limit
        integer used_count
        date valid_from
        date valid_until
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    COUPON_USAGE {
        bigint id PK
        bigint coupon_id FK
        bigint user_id FK
        bigint order_id FK
        timestamp created_at
    }

    REVIEWS {
        bigint id PK
        bigint user_id FK
        bigint product_id FK
        bigint order_id FK
        integer rating
        text comment
        boolean is_approved
        timestamp created_at
        timestamp updated_at
    }

    SHIPMENTS {
        bigint id PK
        bigint order_id FK
        string tracking_number
        string carrier
        enum status "pending,shipped,in_transit,delivered"
        date shipped_at
        date delivered_at
        timestamp created_at
        timestamp updated_at
    }

    NOTIFICATIONS {
        bigint id PK
        string type
        bigint notifiable_id
        string notifiable_type
        text data
        timestamp read_at
        timestamp created_at
        timestamp updated_at
    }

    USERS ||--o{ CARTS : has
    USERS ||--o{ WISHLISTS : has
    USERS ||--o{ ADDRESSES : has
    USERS ||--o{ ORDERS : places
    USERS ||--o{ REVIEWS : writes
    USERS ||--o{ COUPON_USAGE : uses

    CATEGORIES ||--o{ SUBCATEGORIES : contains
    CATEGORIES ||--o{ PRODUCTS : contains
    SUBCATEGORIES ||--o{ PRODUCTS : contains

    CARTS ||--o{ CART_ITEMS : contains
    CART_ITEMS }o--|| PRODUCTS : references

    WISHLISTS ||--o{ WISHLIST_ITEMS : contains
    WISHLIST_ITEMS }o--|| PRODUCTS : references

    ORDERS ||--o{ ORDER_ITEMS : contains
    ORDERS ||--o{ PAYMENTS : has
    ORDERS ||--o{ SHIPMENTS : has
    ORDERS }o--|| ADDRESSES : ships_to
    ORDER_ITEMS }o--|| PRODUCTS : references

    COUPONS ||--o{ COUPON_USAGE : used_in

    PRODUCTS ||--o{ REVIEWS : receives
    ORDERS ||--o{ REVIEWS : enables
```

## Key Relationships

1. **Users** can have multiple carts, wishlists, addresses, orders, and reviews
2. **Categories** contain subcategories and products
3. **Products** belong to categories and subcategories
4. **Carts** contain multiple cart items (products with quantities)
5. **Wishlists** contain multiple wishlist items (products)
6. **Orders** contain multiple order items and have one payment and shipment
7. **Addresses** are linked to users and orders
8. **Coupons** can be used multiple times by different users
9. **Reviews** are linked to users, products, and orders
10. **Notifications** are polymorphic and can be sent to any model
