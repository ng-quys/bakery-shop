# 🍰 Sweet Bakery - Hệ Thống Website Đặt Bánh Ngọt Trực Tuyến

> **Đồ án môn học:** Lập trình mã nguồn mở  
> **Kiến trúc:** Tách biệt Client - Server (RESTful API với PHP PDO & Frontend thuần HTML5/CSS3/JavaScript ES6)

---

## 📌 1. Giới Thiệu Đề Tài
Hệ thống website thương mại điện tử dành cho tiệm bánh ngọt, hỗ trợ khách hàng duyệt thực đơn theo danh mục (bánh sinh nhật, cupcake, tiramisu, bánh mì...), tìm kiếm, tùy chỉnh kích cỡ/ghi chú bánh, đặt hàng trực tuyến và theo dõi lịch sử đơn hàng. Hệ thống tích hợp phân hệ Quản trị viên (Admin) để quản lý danh mục, sản phẩm, đơn hàng và thống kê doanh thu.

---

## 📁 2. Tổ Chức Cấu Trúc Dự Án (Monorepo)

Dự án được tổ chức khoa học theo mô hình phân tách độc lập giữa **Backend (API)** và **Frontend (Giao diện người dùng & Admin)**:

```text
bakery-shop/
├── .gitignore                     # Bỏ qua các file rác, file nhạy cảm, log
├── README.md                      # Tài liệu hướng dẫn cài đặt & vận hành
│
├── backend/                       # BACKEND (PHP RESTful API & Database)
│   ├── config/
│   │   └── database.php           # Kết nối CSDL MySQL qua PDO & cấu hình CORS
│   ├── api/                       # Các điểm cuối API (Endpoints) trả về JSON
│   │   ├── auth/                  # Xác thực người dùng
│   │   │   ├── login.php          # API Đăng nhập (Khách hàng & Admin)
│   │   │   └── register.php       # API Đăng ký tài khoản
│   │   ├── products/              # Quản lý bánh ngọt
│   │   │   ├── list.php           # Lấy danh sách bánh, phân trang, lọc theo danh mục
│   │   │   ├── detail.php         # Chi tiết sản phẩm theo ID
│   │   │   ├── create.php         # Thêm bánh mới (Admin)
│   │   │   ├── update.php         # Sửa thông tin bánh (Admin)
│   │   │   └── delete.php         # Xóa bánh (Admin)
│   │   ├── categories/            # Quản lý phân loại bánh
│   │   │   └── list.php           # Lấy danh sách danh mục
│   │   └── orders/                # Quản lý đơn đặt hàng
│   │       ├── create.php         # Đặt hàng (lưu đơn và chi tiết đơn hàng)
│   │       ├── my_orders.php      # Lịch sử đơn hàng của khách
│   │       └── admin_orders.php   # Duyệt & cập nhật trạng thái đơn (Admin)
│   ├── helpers/
│   │   └── response.php           # Hàm chuẩn hóa cấu trúc phản hồi JSON
│   └── uploads/                   # Lưu trữ hình ảnh bánh tải lên từ Admin
│
└── frontend/                      # FRONTEND (Giao diện người dùng & Admin Panel)
    ├── assets/                    # Tài nguyên tĩnh dùng chung
    │   ├── css/
    │   │   ├── base.css           # Reset CSS, bảng màu, typography Inter font
    │   │   ├── layout.css         # Khung giao diện: Header, Navbar, Footer
    │   │   └── components.css     # Nút bấm, modal, badge trạng thái, card sản phẩm
    │   ├── js/
    │   │   ├── config.js          # Khai báo tập trung API_BASE_URL & hằng số hệ thống
    │   │   ├── api.js             # Hàm wrapper fetch() xử lý HTTP Request & Response
    │   │   └── cart-helper.js     # Tiện ích quản lý giỏ hàng qua LocalStorage
    │   └── images/                # Biểu tượng, logo, banner
    │
    ├── pages/                     # Giao diện phía Khách hàng (Client Portal)
    │   ├── index.html             # Trang chủ (Banner, Bánh nổi bật, Ưu đãi)
    │   ├── menu.html              # Thực đơn bánh + Bộ lọc danh mục & Tìm kiếm
    │   ├── product-detail.html    # Chi tiết bánh (chọn kích thước, số lượng, ghi chú)
    │   ├── cart.html              # Quản lý giỏ hàng
    │   ├── checkout.html          # Nhập thông tin giao bánh & phương thức thanh toán
    │   ├── order-success.html     # Thông báo đặt bánh thành công
    │   ├── login.html             # Đăng nhập / Đăng ký tài khoản
    │   └── profile.html           # Thông tin khách hàng & Lịch sử đơn mua
    │
    ├── admin/                     # Phân hệ Quản trị viên (Admin Panel)
    │   ├── index.html             # Dashboard (Tổng quan doanh thu, số đơn, biểu đồ)
    │   ├── products.html          # Bảng quản lý bánh (Thêm, sửa, xóa, upload ảnh)
    │   ├── categories.html        # Quản lý các danh mục bánh
    │   └── orders.html            # Tiếp nhận, duyệt đơn và đổi trạng thái giao hàng
    │
    └── js/                        # Logic JavaScript điều khiển từng màn hình cụ thể
        ├── client/
        │   ├── menu.js            # Gọi API hiển thị danh sách bánh, phân trang, lọc
        │   ├── product-detail.js  # Tải chi tiết bánh theo tham số URL (?id=...), add to cart
        │   ├── cart.js            # Hiển thị giỏ hàng từ LocalStorage, cập nhật số lượng
        │   └── checkout.js        # Gửi payload đơn hàng sang API backend
        └── admin/
            ├── dashboard.js       # Gọi API thống kê số liệu đổ về thẻ báo cáo
            ├── products.js        # Thao tác CRUD sản phẩm, gửi form dữ liệu kèm hình ảnh
            └── orders.js          # Gọi API cập nhật tiến độ xử lý đơn hàng