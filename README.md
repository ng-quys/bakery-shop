# 🍰 Sweet Bakery - Hệ Thống Website Đặt Bánh Ngọt Trực Tuyến

> **Đồ án môn học:** Lập trình mã nguồn mở\
> **Kiến trúc:** Client - Server, RESTful API với PHP + MongoDB Atlas và
> Frontend thuần HTML5/CSS3/JavaScript ES6

------------------------------------------------------------------------

## 📌 1. Giới Thiệu Đề Tài

**Sweet Bakery** là hệ thống website thương mại điện tử dành cho tiệm
bánh ngọt, hỗ trợ khách hàng:

-   Duyệt thực đơn bánh theo danh mục.
-   Tìm kiếm và lọc sản phẩm.
-   Xem chi tiết bánh.
-   Chọn kích thước, số lượng và ghi chú.
-   Thêm sản phẩm vào giỏ hàng.
-   Đặt hàng trực tuyến.
-   Theo dõi lịch sử và trạng thái đơn hàng.
-   Sử dụng chương trình khuyến mãi.

Hệ thống đồng thời cung cấp phân hệ **Quản trị viên (Admin Panel)** hỗ
trợ:

-   Quản lý sản phẩm.
-   Quản lý danh mục.
-   Quản lý đơn hàng.
-   Quản lý khách hàng.
-   Quản lý nhân viên.
-   Quản lý chương trình khuyến mãi.
-   Theo dõi doanh thu và số liệu thống kê.

------------------------------------------------------------------------

## 🏗️ 2. Kiến Trúc Hệ Thống

Dự án được tổ chức theo mô hình **Monorepo**, tách biệt rõ ràng giữa
Backend và Frontend.

Backend được xây dựng theo hướng **Modular MVC + Service + Repository**:

``` text
Route
  ↓
Middleware
  ↓
Controller
  ↓
Request / Validation
  ↓
Service
  ↓
Repository
  ↓
MongoDB Atlas
```

Frontend không truy cập trực tiếp cơ sở dữ liệu. Mọi dữ liệu được trao
đổi với Backend thông qua RESTful API.

``` text
HTML / CSS / JavaScript
          ↓
      Fetch API
          ↓
    PHP REST API
          ↓
       Service
          ↓
     Repository
          ↓
    MongoDB Atlas
```

------------------------------------------------------------------------

## 📁 3. Cấu Trúc Thư Mục

``` text
bakery-shop/
│
├── .gitignore
├── README.md
│
├── backend/
│   ├── app/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── PromotionController.php
│   │   │   │   ├── CustomerController.php
│   │   │   │   └── EmployeeController.php
│   │   │   └── Client/
│   │   │       ├── AuthController.php
│   │   │       ├── ProductController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── CartController.php
│   │   │       ├── OrderController.php
│   │   │       └── PromotionController.php
│   │   │
│   │   ├── Models/
│   │   │   ├── Product.php
│   │   │   ├── Category.php
│   │   │   ├── Order.php
│   │   │   ├── Customer.php
│   │   │   ├── Employee.php
│   │   │   ├── Promotion.php
│   │   │   └── Favorite.php
│   │   │
│   │   ├── Services/
│   │   │   ├── AuthService.php
│   │   │   ├── ProductService.php
│   │   │   ├── CategoryService.php
│   │   │   ├── OrderService.php
│   │   │   ├── PromotionService.php
│   │   │   ├── CustomerService.php
│   │   │   └── DashboardService.php
│   │   │
│   │   ├── Repositories/
│   │   │   ├── ProductRepository.php
│   │   │   ├── CategoryRepository.php
│   │   │   ├── OrderRepository.php
│   │   │   ├── CustomerRepository.php
│   │   │   ├── EmployeeRepository.php
│   │   │   ├── PromotionRepository.php
│   │   │   └── FavoriteRepository.php
│   │   │
│   │   ├── Requests/
│   │   │   ├── LoginRequest.php
│   │   │   ├── RegisterRequest.php
│   │   │   ├── ProductRequest.php
│   │   │   ├── CategoryRequest.php
│   │   │   ├── OrderRequest.php
│   │   │   └── PromotionRequest.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── AuthMiddleware.php
│   │   │   └── AdminMiddleware.php
│   │   │
│   │   └── Helpers/
│   │       ├── Response.php
│   │       ├── Validator.php
│   │       └── UploadHelper.php
│   │
│   ├── config/
│   │   ├── app.php
│   │   └── database.php
│   │
│   ├── routes/
│   │   ├── api.php
│   │   ├── admin.php
│   │   └── auth.php
│   │
│   ├── public/
│   │   └── index.php
│   │
│   ├── uploads/
│   │   └── products/
│   │
│   ├── vendor/
│   ├── .env
│   ├── .env.example
│   └── composer.json
│
└── frontend/
    ├── assets/
    │   ├── css/
    │   │   ├── base.css
    │   │   ├── layout.css
    │   │   ├── components.css
    │   │   └── variables.css
    │   ├── js/
    │   │   ├── config.js
    │   │   ├── api.js
    │   │   ├── auth-helper.js
    │   │   └── cart-helper.js
    │   └── images/
    │       ├── logo/
    │       ├── products/
    │       ├── banners/
    │       └── icons/
    │
    ├── pages/
    │   ├── index.html
    │   ├── menu.html
    │   ├── product-detail.html
    │   ├── cart.html
    │   ├── checkout.html
    │   ├── order-success.html
    │   ├── login.html
    │   └── profile.html
    │
    ├── admin/
    │   ├── pages/
    │   │   ├── dashboard/index.html
    │   │   ├── products/index.html
    │   │   ├── categories/index.html
    │   │   ├── orders/index.html
    │   │   ├── promotions/index.html
    │   │   ├── customers/index.html
    │   │   └── employees/index.html
    │   ├── components/
    │   │   ├── sidebar.js
    │   │   ├── navbar.js
    │   │   ├── modal.js
    │   │   └── table.js
    │   ├── js/
    │   │   ├── dashboard/dashboard.js
    │   │   ├── products/
    │   │   │   ├── product-api.js
    │   │   │   ├── product-list.js
    │   │   │   └── product-form.js
    │   │   ├── categories/
    │   │   │   ├── category-api.js
    │   │   │   └── category-list.js
    │   │   ├── orders/
    │   │   │   ├── order-api.js
    │   │   │   └── order-list.js
    │   │   └── promotions/
    │   │       ├── promotion-api.js
    │   │       ├── promotion-list.js
    │   │       └── promotion-form.js
    │   └── css/
    │       ├── admin.css
    │       ├── dashboard/dashboard.css
    │       ├── products/product.css
    │       ├── orders/order.css
    │       └── promotions/promotion.css
    │
    └── js/
        └── client/
            ├── home.js
            ├── menu.js
            ├── product-detail.js
            ├── cart.js
            ├── checkout.js
            ├── profile.js
            └── auth.js
```

------------------------------------------------------------------------

## 🧱 4. Vai Trò Các Layer Backend

### Route

Xác định endpoint, HTTP method và Controller tương ứng.

### Middleware

Kiểm tra xác thực và phân quyền trước khi request được chuyển đến
Controller.

### Controller

-   Nhận HTTP Request.
-   Đọc query parameter/request body.
-   Gọi Service.
-   Trả JSON Response.
-   Không trực tiếp truy cập MongoDB.

### Request / Validation

Kiểm tra và chuẩn hóa dữ liệu đầu vào trước khi xử lý nghiệp vụ.

### Service

Chứa business logic của hệ thống, ví dụ kiểm tra mã trùng, tính toán
khuyến mãi, kiểm tra trạng thái đơn hàng và chuẩn hóa dữ liệu.

### Repository

Lớp duy nhất chịu trách nhiệm truy vấn và ghi dữ liệu vào MongoDB.

### Model

Biểu diễn cấu trúc dữ liệu/domain object của từng module.

------------------------------------------------------------------------

## 🗄️ 5. Cơ Sở Dữ Liệu

Hệ thống sử dụng **MongoDB Atlas**.

Database:

``` text
QL_CakeShop
```

Các collection chính:

``` text
DanhMucs
DonHangs
Gios
KhachHangs
KhuyenMais
NhanViens
SanPhams
YeuThichs
```

Thông tin kết nối được lưu trong:

``` text
backend/.env
```

Ví dụ:

``` env
MONGODB_URI=mongodb+srv://username:password@cluster.mongodb.net/
MONGODB_DATABASE=QL_CakeShop
```

> **Lưu ý:** Không commit file `.env` hoặc connection string chứa mật
> khẩu lên GitHub.

Repository cung cấp `.env.example` để các thành viên tự cấu hình môi
trường.

------------------------------------------------------------------------

## 🌐 6. RESTful API

### Authentication

``` http
POST /api/auth/login
POST /api/auth/register
```

### Products

``` http
GET    /api/products
GET    /api/products/{MaSP}

GET    /api/admin/products
POST   /api/admin/products
PUT    /api/admin/products/{MaSP}
DELETE /api/admin/products/{MaSP}
```

### Categories

``` http
GET    /api/categories

GET    /api/admin/categories
POST   /api/admin/categories
PUT    /api/admin/categories/{MaDM}
DELETE /api/admin/categories/{MaDM}
```

### Orders

``` http
POST /api/orders
GET  /api/orders/my-orders

GET /api/admin/orders
GET /api/admin/orders/{MaDH}
PUT /api/admin/orders/{MaDH}
```

### Promotions

``` http
GET /api/promotions

GET    /api/admin/promotions
GET    /api/admin/promotions/{MaKM}
POST   /api/admin/promotions
PUT    /api/admin/promotions/{MaKM}
DELETE /api/admin/promotions/{MaKM}
```

------------------------------------------------------------------------

## 🎨 7. Frontend

Frontend sử dụng:

-   HTML5
-   CSS3
-   JavaScript ES6
-   Fetch API
-   LocalStorage

Địa chỉ API được cấu hình tập trung tại:

``` text
frontend/assets/js/config.js
```

Ví dụ:

``` javascript
export const API_BASE_URL = 'http://localhost:8000/api';
```

Không hard-code URL API ở từng module JavaScript.

### Ví dụ module Admin Promotion

``` text
frontend/admin/js/promotions/
├── promotion-api.js
├── promotion-list.js
└── promotion-form.js
```

-   `promotion-api.js`: giao tiếp với REST API.
-   `promotion-list.js`: bảng dữ liệu, tìm kiếm, lọc, phân trang và thao
    tác.
-   `promotion-form.js`: form thêm/sửa, validation phía client.

------------------------------------------------------------------------

## 🔐 8. Xác Thực & Phân Quyền

Backend sử dụng:

``` text
AuthMiddleware
AdminMiddleware
```

Ví dụ một request Admin:

``` text
DELETE /api/admin/products/SP001
```

Luồng xử lý:

``` text
Request
   ↓
AuthMiddleware
   ↓
AdminMiddleware
   ↓
ProductController
   ↓
ProductService
   ↓
ProductRepository
   ↓
MongoDB Atlas
```

------------------------------------------------------------------------

## 🔄 9. Ví Dụ Luồng Xử Lý Khuyến Mãi

Admin tạo chương trình khuyến mãi:

``` text
Admin nhập form
      ↓
promotion-form.js
      ↓
promotion-api.js
      ↓
POST /api/admin/promotions
      ↓
PromotionController
      ↓
PromotionRequest
      ↓
PromotionService
      ↓
PromotionRepository
      ↓
MongoDB Atlas
      ↓
QL_CakeShop.KhuyenMais
```

Response:

``` text
MongoDB
   ↓
Repository
   ↓
Service
   ↓
Controller
   ↓
JSON Response
   ↓
Frontend
   ↓
Cập nhật giao diện
```

------------------------------------------------------------------------

## ⚙️ 10. Yêu Cầu Môi Trường

Cần cài đặt:

-   PHP
-   Composer
-   PHP MongoDB Extension
-   MongoDB Atlas account
-   Trình duyệt hiện đại
-   VS Code Live Server (khuyến nghị để chạy frontend)

------------------------------------------------------------------------

## 🚀 11. Cài Đặt & Chạy Dự Án

### 11.1 Clone repository

``` bash
git clone <repository-url>
cd bakery-shop
```

### 11.2 Cài dependency Backend

``` bash
cd backend
composer install
```

### 11.3 Cấu hình môi trường

Sao chép:

``` text
.env.example
```

thành:

``` text
.env
```

Sau đó cấu hình MongoDB Atlas:

``` env
MONGODB_URI=mongodb+srv://username:password@cluster.mongodb.net/
MONGODB_DATABASE=QL_CakeShop
```

### 11.4 Chạy Backend

Từ thư mục `backend/public`:

``` bash
php -S localhost:8000
```

Backend:

``` text
http://localhost:8000
```

Ví dụ API:

``` text
http://localhost:8000/api/admin/promotions
```

### 11.5 Chạy Frontend

Có thể mở `frontend` bằng VS Code Live Server.

Ví dụ:

``` text
http://127.0.0.1:5500/frontend/pages/index.html
```

Frontend giao tiếp với:

``` text
http://localhost:8000/api
```

------------------------------------------------------------------------

## 🧪 12. Kiểm Tra API

Ví dụ lấy danh sách khuyến mãi:

``` http
GET http://localhost:8000/api/admin/promotions
```

Thêm khuyến mãi:

``` http
POST http://localhost:8000/api/admin/promotions
Content-Type: application/json
```

Ví dụ body:

``` json
{
  "MaKM": "KM001",
  "TenKM": "Giảm 10% bánh kem",
  "MoTa": "Khuyến mãi bánh kem sinh nhật",
  "LoaiKM": "Phần trăm",
  "GiaTri": 10,
  "GiaTriToiDa": 100000,
  "DonToiThieu": 300000,
  "PhamViApDung": "Danh mục",
  "DanhMuc": ["DM01", "DM02"],
  "NgayBatDau": "2026-09-17T00:00",
  "NgayKetThuc": "2026-09-30T23:59",
  "SoLuong": 100,
  "TrangThai": "Đang hoạt động"
}
```

Có thể kiểm tra API bằng Postman hoặc Thunder Client.

------------------------------------------------------------------------

## 📏 13. Quy Ước Phát Triển

### PHP

Class sử dụng **PascalCase**:

``` text
PromotionController
PromotionService
PromotionRepository
PromotionRequest
```

### JavaScript

Tên file module sử dụng **kebab-case**:

``` text
promotion-api.js
promotion-list.js
promotion-form.js
```

### REST API

Endpoint sử dụng danh từ số nhiều:

``` text
/products
/categories
/orders
/promotions
```

Không sử dụng:

``` text
/createPromotion
/deleteProduct
/updateOrder
```

Thao tác được thể hiện bằng HTTP Method:

``` text
GET
POST
PUT
DELETE
```

------------------------------------------------------------------------

## 🔒 14. Bảo Mật Repository

Các file/thư mục không nên đưa lên Git:

``` gitignore
backend/.env
backend/vendor/
*.log
.DS_Store
Thumbs.db
.vscode/
```

Không commit:

-   MongoDB username/password.
-   Connection string chứa thông tin đăng nhập.
-   Token hoặc secret key.
-   File cấu hình cá nhân của từng thành viên.

------------------------------------------------------------------------

## 🧩 15. Định Hướng Mở Rộng

Kiến trúc cho phép bổ sung module mà không thay đổi toàn bộ hệ thống.

Ví dụ module Voucher:

``` text
VoucherController
VoucherService
VoucherRepository
VoucherRequest
```

Module Review:

``` text
ReviewController
ReviewService
ReviewRepository
ReviewRequest
```

Các module sử dụng chung:

``` text
Database
Router
Middleware
Response
Validator
```

------------------------------------------------------------------------

## 👥 16. Phát Triển Nhóm

Khuyến nghị mỗi chức năng được phát triển trên branch riêng:

``` text
feature/products
feature/orders
feature/promotions
feature/admin-dashboard
```

Sau khi hoàn thành và kiểm tra, tạo Pull Request để merge vào branch
chính.

------------------------------------------------------------------------

## 📚 17. Công Nghệ Sử Dụng

  Thành phần           Công nghệ
  -------------------- -----------------------------
  Frontend             HTML5, CSS3, JavaScript ES6
  Backend              PHP
  API                  RESTful API / JSON
  Database             MongoDB Atlas
  Dependency Manager   Composer
  Version Control      Git / GitHub
  Local Frontend       VS Code Live Server
  Local Backend        PHP Development Server

------------------------------------------------------------------------

## 📄 License

Dự án được xây dựng phục vụ mục đích học tập trong môn **Lập trình mã
nguồn mở**.
