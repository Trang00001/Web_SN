USE social_network;

-- Xóa dữ liệu cũ theo thứ tự phụ thuộc (dùng DELETE thay vì TRUNCATE)
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM Notification;
DELETE FROM Message;
DELETE FROM Follow;
DELETE FROM FriendRequest;
DELETE FROM `Like`;
DELETE FROM Comment;
DELETE FROM Post;
DELETE FROM Category;
DELETE FROM User;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Thêm User
INSERT INTO User (username, email, password) VALUES
('alice', 'alice@example.com', 'password123'),
('bob', 'bob@example.com', 'password456'),
('charlie', 'charlie@example.com', 'password789');

-- 2. Thêm Category
INSERT INTO Category (name) VALUES
('Technology'),
('Education'),
('Entertainment');

-- 3. Thêm Post
INSERT INTO Post (user_id, content, media_url, category_id) VALUES
(1, 'Xin chào! Đây là bài post đầu tiên của mình.', NULL, 1),
(2, 'Hôm nay học MySQL thấy khá thú vị.', NULL, 2),
(3, 'Tối nay đi xem phim Marvel!', 'image1.jpg', 3);

-- 4. Thêm Comment
INSERT INTO Comment (post_id, user_id, content) VALUES
(1, 2, 'Chào Alice! Rất vui được kết nối.'),
(1, 3, 'Welcome Alice!'),
(2, 1, 'Đúng rồi, mình cũng thích SQL.');

-- 5. Thêm Like
INSERT INTO `Like` (post_id, user_id, type) VALUES
(1, 2, 'like'),
(1, 3, 'like'),
(2, 1, 'like'),
(3, 1, 'dislike');

-- 6. Thêm FriendRequest
INSERT INTO FriendRequest (sender_id, receiver_id, status) VALUES
(1, 2, 'pending'),
(2, 3, 'accepted');

-- 7. Thêm Follow
INSERT INTO Follow (follower_id, following_id) VALUES
(1, 2),
(2, 1),
(3, 1);

-- 8. Thêm Message
INSERT INTO Message (sender_id, receiver_id, content) VALUES
(1, 2, 'Hello Bob!'),
(2, 1, 'Hi Alice, nice to meet you!'),
(3, 1, 'Alice ơi, đi xem phim không?');

-- 9. Thêm Notification
INSERT INTO Notification (user_id, type, reference_id) VALUES
(1, 'like', 1),       -- Alice nhận thông báo Bob like post 1
(1, 'comment', 1),    -- Alice nhận thông báo Bob comment post 1
(2, 'friend_request', 1); -- Bob nhận thông báo Alice gửi lời mời
