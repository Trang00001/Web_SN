# Social Network Website

Một ứng dụng mạng xã hội đơn giản được xây dựng bằng HTML, CSS, JavaScript, PHP và MySQL theo mô hình MVC.

## 🚀 Tính năng

### Frontend (Đã hoàn thành)
- ✅ **Trang chủ (Home)**: Newsfeed, tạo bài viết, stories, thao tác nhanh
- ✅ **Trang hồ sơ (Profile)**: Thông tin cá nhân, bài viết, ảnh, bạn bè
- ✅ **Trang tìm kiếm (Search)**: Tìm kiếm nâng cao, bộ lọc, gợi ý
- ✅ **Trang đăng nhập (Login)**: Đăng nhập, đăng ký, quên mật khẩu

### Tính năng tương tác
- ✅ Tạo và đăng bài viết
- ✅ Like, comment, share bài viết
- ✅ Tìm kiếm người dùng, bài viết, nhóm
- ✅ Giao diện responsive (mobile-friendly)
- ✅ Thông báo realtime
- ✅ Upload ảnh/video (UI)

### Backend (Cần phát triển thêm)
- ✅ Cấu trúc database MySQL
- ✅ Model User cơ bản
- ⏳ API endpoints cho frontend
- ⏳ Xác thực và phân quyền người dùng
- ⏳ Upload và xử lý file

## 📁 Cấu trúc thư mục

```
WEB SOCIAL NETWORK/
├── assets/
│   ├── css/
│   │   └── style.css          # CSS chính
│   └── js/
│       └── main.js            # JavaScript chính
├── views/
│   ├── home.html              # Trang chủ
│   ├── profile.html           # Trang hồ sơ
│   ├── search.html            # Trang tìm kiếm
│   └── login.html             # Trang đăng nhập
├── models/
│   ├── Database.php           # Kết nối database
│   └── User.php               # Model User
├── controllers/               # Controllers (cần phát triển)
└── index.html                 # Trang chủ chính
```

## 🛠️ Cài đặt

### Yêu cầu hệ thống
- Web server (Apache/Nginx)
- PHP 7.4+
- MySQL 5.7+
- phpMyAdmin (tùy chọn)

### Bước 1: Clone dự án
```bash
git clone [repository-url]
cd "WEB SOCIAL NETWORK"
```

### Bước 2: Cấu hình database
1. Tạo database MySQL:
```sql
CREATE DATABASE social_network;
```

2. Import schema từ file `models/Database.php` vào phpMyAdmin hoặc MySQL command line

3. Cập nhật thông tin kết nối database trong `models/Database.php`:
```php
private $host = 'localhost';
private $db_name = 'social_network';
private $username = 'root';
private $password = '';
```

### Bước 3: Chạy ứng dụng
1. Đặt thư mục dự án vào web root (htdocs/www)
2. Truy cập: `http://localhost/WEB SOCIAL NETWORK/`
3. Sử dụng tài khoản demo:
   - Email: `demo@example.com`
   - Password: `123456`

## 🎨 Thiết kế

### Màu sắc chính
- **Primary Green**: `#4a7c59` - Màu xanh chính
- **Secondary Green**: `#42b883` - Màu xanh phụ
- **Background**: `#f0f2f5` - Màu nền
- **Card Background**: `#ffffff` - Màu nền card
- **Text Primary**: `#333333` - Màu chữ chính
- **Text Secondary**: `#65676b` - Màu chữ phụ

### Typography
- Font: Segoe UI, Tahoma, Geneva, Verdana, sans-serif
- Responsive design với breakpoint 768px

## 📱 Responsive Design

Website được thiết kế responsive hoàn toàn:
- **Desktop**: Layout 3 cột (sidebar + content + right sidebar)
- **Tablet**: Layout 2 cột (collapsible sidebar + content)
- **Mobile**: Layout 1 cột (hamburger menu + content)

## 🔧 Công nghệ sử dụng

### Frontend
- **HTML5**: Cấu trúc trang web
- **CSS3**: Styling và animations
- **JavaScript ES6+**: Tương tác và xử lý logic
- **Font Awesome**: Icons
- **CSS Grid & Flexbox**: Layout responsive

### Backend
- **PHP**: Server-side logic
- **MySQL**: Database
- **PDO**: Database abstraction layer

## 📋 Todo List

### Frontend
- [ ] Thêm trang Messages (Tin nhắn)
- [ ] Thêm trang Groups (Nhóm)
- [ ] Thêm trang Events (Sự kiện)
- [ ] Cải thiện UI/UX
- [ ] Thêm dark mode
- [ ] PWA support

### Backend
- [ ] Tạo API RESTful
- [ ] Xác thực JWT
- [ ] Upload file system
- [ ] Real-time messaging (WebSocket)
- [ ] Email notifications
- [ ] Admin panel

### Database
- [ ] Indexing optimization
- [ ] Backup system
- [ ] Migration scripts

## 🤝 Đóng góp

1. Fork dự án
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

## 👥 Tác giả

- **Developer**: [Tên của bạn]
- **Email**: [Email của bạn]
- **GitHub**: [GitHub profile]

## 🙏 Acknowledgments

- Font Awesome cho icons
- Gradient backgrounds inspiration
- Modern social media UI/UX patterns

## 📞 Hỗ trợ

Nếu bạn gặp vấn đề hoặc có câu hỏi, vui lòng tạo issue trong GitHub repository hoặc liên hệ qua email.

---

**Happy Coding! 🚀**