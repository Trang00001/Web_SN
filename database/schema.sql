-- Xóa database cũ nếu có
DROP DATABASE IF EXISTS SocialNetworkDB;
CREATE DATABASE SocialNetworkDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE SocialNetworkDB;

-- 1. Account
CREATE TABLE Account (
    AccountID INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(100) UNIQUE NOT NULL,
    PasswordHash VARCHAR(255) NOT NULL,
    Username VARCHAR(50) UNIQUE NOT NULL
);

-- 2. Profile
CREATE TABLE Profile (
    ProfileID INT AUTO_INCREMENT PRIMARY KEY,
    AccountID INT NOT NULL,
    FullName VARCHAR(100),
    Gender VARCHAR(10),
    BirthDate DATE,
    Hometown VARCHAR(100),
    AvatarURL VARCHAR(255),
    FOREIGN KEY (AccountID) REFERENCES Account(AccountID)
);
-- 4. PostCategory
CREATE TABLE PostCategory (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
    CategoryName VARCHAR(100) NOT NULL
);
CREATE TABLE Post (
    PostID INT AUTO_INCREMENT PRIMARY KEY,
    AuthorID INT NOT NULL,
    Content TEXT,
    PostTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    SharedFromPostID INT NULL,
    CategoryID INT NULL,
    FOREIGN KEY (AuthorID) REFERENCES Account(AccountID),
    FOREIGN KEY (SharedFromPostID) REFERENCES Post(PostID),
    FOREIGN KEY (CategoryID) REFERENCES PostCategory(CategoryID)
);




-- 5. SavedPost
CREATE TABLE SavedPost (
    AccountID INT,
    PostID INT,
    CategoryID INT,
    Type ENUM('Saved', 'Hidden'),
    SavedTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (AccountID, PostID),
    FOREIGN KEY (AccountID) REFERENCES Account(AccountID),
    FOREIGN KEY (PostID) REFERENCES Post(PostID),
    FOREIGN KEY (CategoryID) REFERENCES PostCategory(CategoryID)
);

-- 6. Image
CREATE TABLE Image (
    ImageID INT AUTO_INCREMENT PRIMARY KEY,
    PostID INT NOT NULL,
    ImageURL VARCHAR(255) NOT NULL,
    FOREIGN KEY (PostID) REFERENCES Post(PostID)
);

-- 7. Comment
CREATE TABLE Comment (
    CommentID INT AUTO_INCREMENT PRIMARY KEY,
    PostID INT NOT NULL,
    AccountID INT NOT NULL,
    Content TEXT,
    CommentTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (PostID) REFERENCES Post(PostID),
    FOREIGN KEY (AccountID) REFERENCES Account(AccountID)
);

-- 8. PostLike
CREATE TABLE PostLike (
    AccountID INT,
    PostID INT,
    LikeType ENUM('Like', 'Dislike'),
    LikeTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (AccountID, PostID),
    FOREIGN KEY (AccountID) REFERENCES Account(AccountID),
    FOREIGN KEY (PostID) REFERENCES Post(PostID)
);

-- 9. FriendRequest
CREATE TABLE FriendRequest (
    RequestID INT AUTO_INCREMENT PRIMARY KEY,
    SenderID INT NOT NULL,
    ReceiverID INT NOT NULL,
    Status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    SentTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (SenderID) REFERENCES Account(AccountID),
    FOREIGN KEY (ReceiverID) REFERENCES Account(AccountID)
);

-- 10. Friendship
CREATE TABLE Friendship (
    Account1ID INT,
    Account2ID INT,
    FriendshipDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (Account1ID, Account2ID),
    FOREIGN KEY (Account1ID) REFERENCES Account(AccountID),
    FOREIGN KEY (Account2ID) REFERENCES Account(AccountID)
);

-- 11. ChatBox
CREATE TABLE ChatBox (
    ChatBoxID INT AUTO_INCREMENT PRIMARY KEY,
    Account1ID INT NOT NULL,
    Account2ID INT NOT NULL,
    Status VARCHAR(20),
    LastMessageTime DATETIME,
    FOREIGN KEY (Account1ID) REFERENCES Account(AccountID),
    FOREIGN KEY (Account2ID) REFERENCES Account(AccountID)
);

-- 12. Message
CREATE TABLE Message (
    MessageID INT AUTO_INCREMENT PRIMARY KEY,
    ChatBoxID INT NOT NULL,
    SenderID INT NOT NULL,
    Content TEXT,
    Emotion VARCHAR(50),
    SentTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ChatBoxID) REFERENCES ChatBox(ChatBoxID),
    FOREIGN KEY (SenderID) REFERENCES Account(AccountID)
);

-- 13. Notification
CREATE TABLE Notification (
    NotificationID INT AUTO_INCREMENT PRIMARY KEY,
    ReceiverID INT NOT NULL,
    Type VARCHAR(50),
    Content VARCHAR(255),
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    IsRead BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (ReceiverID) REFERENCES Account(AccountID)
);

-- ======================
-- 🌸 DỮ LIỆU MẪU
-- ======================

-- Account
INSERT INTO Account (Email, PasswordHash, Username) VALUES
('alice@gmail.com', '123456', 'Alice'),
('bob@gmail.com', '123456', 'Bob'),
('charlie@gmail.com', '123456', 'Charlie');

-- Profile
INSERT INTO Profile (AccountID, FullName, Gender, BirthDate, Hometown, AvatarURL)
VALUES
(1, 'Alice Nguyễn', 'Female', '2004-03-15', 'TP.HCM', 'uploads/avatars/alice.jpg'),
(2, 'Bob Trần', 'Male', '2003-11-21', 'Hà Nội', 'uploads/avatars/bob.jpg'),
(3, 'Charlie Lê', 'Male', '2002-06-09', 'Đà Nẵng', 'uploads/avatars/charlie.jpg');

-- PostCategory
INSERT INTO PostCategory (CategoryName)
VALUES ('Life'), ('Study'), ('Entertainment');

-- Post
INSERT INTO Post (AuthorID, Content) VALUES
(1, 'Hôm nay trời đẹp quá!'),
(2, 'Mới hoàn thành project PHP đầu tiên 😎'),
(3, 'Ai có tài liệu học MySQL hay không?');

-- Comment
INSERT INTO Comment (PostID, AccountID, Content)
VALUES
(1, 2, 'Chuẩn luôn, trời đẹp mà đi học thì buồn 😅'),
(2, 1, 'Giỏi quá Bob!'),
(3, 2, 'Mình có, để gửi qua nhé!');

-- PostLike
INSERT INTO PostLike (AccountID, PostID, LikeType)
VALUES
(1, 2, 'Like'),
(2, 1, 'Like'),
(3, 2, 'Like');

-- FriendRequest
INSERT INTO FriendRequest (SenderID, ReceiverID, Status)
VALUES
(1, 2, 'Accepted'),
(2, 3, 'Pending');

-- Friendship
INSERT INTO Friendship (Account1ID, Account2ID)
VALUES
(1, 2);

-- ChatBox
INSERT INTO ChatBox (Account1ID, Account2ID, Status, LastMessageTime)
VALUES
(1, 2, 'active', NOW());

-- Message
INSERT INTO Message (ChatBoxID, SenderID, Content, Emotion)
VALUES
(1, 1, 'Chào Bob! Dạo này sao rồi?', '😊'),
(1, 2, 'Vẫn ổn, cảm ơn Alice!', '👍');

-- Notification
INSERT INTO Notification (ReceiverID, Type, Content)
VALUES
(2, 'FriendRequest', 'Alice đã gửi lời mời kết bạn cho bạn'),
(3, 'Comment', 'Bob đã bình luận bài viết của bạn');
