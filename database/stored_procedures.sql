USE SocialNetworkDB;

-- =============================
-- 1️⃣ NHÓM XÁC THỰC & TÀI KHOẢN
-- =============================

-- Đăng ký tài khoản mới
DELIMITER //
CREATE PROCEDURE sp_RegisterUser(
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255),
    IN p_username VARCHAR(50)
)
BEGIN
    IF EXISTS (SELECT 1 FROM Account WHERE Email = p_email OR Username = p_username) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email or username already exists';
    ELSE
        INSERT INTO Account (Email, PasswordHash, Username)
        VALUES (p_email, p_password, p_username);
    END IF;
END //
DELIMITER ;

-- Đăng nhập (kiểm tra thông tin)
DELIMITER //
CREATE PROCEDURE sp_LoginUser(
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255)
)
BEGIN
    SELECT AccountID, Email, Username
    FROM Account
    WHERE Email = p_email AND PasswordHash = p_password;
END //
DELIMITER ;

-- Kiểm tra email tồn tại
DELIMITER //
CREATE PROCEDURE sp_CheckEmailExists(
    IN p_email VARCHAR(100)
)
BEGIN
    SELECT COUNT(*) AS ExistsEmail
    FROM Account
    WHERE Email = p_email;
END //
DELIMITER ;

-- Lấy thông tin hồ sơ
DELIMITER //
CREATE PROCEDURE sp_GetUserProfile(IN p_accountID INT)
BEGIN
    SELECT a.AccountID, a.Email, a.Username,
           p.FullName, p.Gender, p.BirthDate, p.Hometown, p.AvatarURL
    FROM Account a
    LEFT JOIN Profile p ON a.AccountID = p.AccountID
    WHERE a.AccountID = p_accountID;
END //
DELIMITER ;

-- Cập nhật hồ sơ
DELIMITER //
CREATE PROCEDURE sp_UpdateUserProfile(
    IN p_accountID INT,
    IN p_fullname VARCHAR(100),
    IN p_gender VARCHAR(10),
    IN p_birth DATE,
    IN p_hometown VARCHAR(100),
    IN p_avatar VARCHAR(255)
)
BEGIN
    UPDATE Profile
    SET FullName = p_fullname,
        Gender = p_gender,
        BirthDate = p_birth,
        Hometown = p_hometown,
        AvatarURL = p_avatar
    WHERE AccountID = p_accountID;
END //
DELIMITER ;

-- Xóa tài khoản
DELIMITER //
CREATE PROCEDURE sp_DeleteUser(IN p_accountID INT)
BEGIN
    DELETE FROM Account WHERE AccountID = p_accountID;
END //
DELIMITER ;


-- =============================
-- 2️⃣ NHÓM BÀI VIẾT (POSTS)
-- =============================

DELIMITER //
CREATE PROCEDURE sp_CreatePost(IN p_authorID INT, IN p_content TEXT)
BEGIN
    INSERT INTO Post (AuthorID, Content) VALUES (p_authorID, p_content);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_UpdatePost(IN p_postID INT, IN p_content TEXT)
BEGIN
    UPDATE Post SET Content = p_content WHERE PostID = p_postID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_DeletePost(IN p_postID INT)
BEGIN
    DELETE FROM Post WHERE PostID = p_postID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_GetAllPosts()
BEGIN
    SELECT p.PostID, p.Content, p.PostTime, a.Username, pr.AvatarURL
    FROM Post p
    JOIN Account a ON p.AuthorID = a.AccountID
    LEFT JOIN Profile pr ON a.AccountID = pr.AccountID
    ORDER BY p.PostTime DESC;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_GetPostById(IN p_postID INT)
BEGIN
    SELECT p.*, a.Username, pr.AvatarURL
    FROM Post p
    JOIN Account a ON p.AuthorID = a.AccountID
    LEFT JOIN Profile pr ON a.AccountID = pr.AccountID
    WHERE p.PostID = p_postID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_GetUserPosts(IN p_accountID INT)
BEGIN
    SELECT * FROM Post WHERE AuthorID = p_accountID ORDER BY PostTime DESC;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_AddLike(IN p_accountID INT, IN p_postID INT)
BEGIN
    INSERT IGNORE INTO PostLike (AccountID, PostID, LikeType)
    VALUES (p_accountID, p_postID, 'Like');
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_RemoveLike(IN p_accountID INT, IN p_postID INT)
BEGIN
    DELETE FROM PostLike WHERE AccountID = p_accountID AND PostID = p_postID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_AddComment(IN p_postID INT, IN p_accountID INT, IN p_content TEXT)
BEGIN
    INSERT INTO Comment (PostID, AccountID, Content)
    VALUES (p_postID, p_accountID, p_content);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_GetCommentsByPostId(IN p_postID INT)
BEGIN
    SELECT c.*, a.Username, pr.AvatarURL
    FROM Comment c
    JOIN Account a ON c.AccountID = a.AccountID
    LEFT JOIN Profile pr ON a.AccountID = pr.AccountID
    WHERE c.PostID = p_postID
    ORDER BY c.CommentTime ASC;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_SavePost(IN p_accountID INT, IN p_postID INT)
BEGIN
    INSERT INTO SavedPost (AccountID, PostID, Type)
    VALUES (p_accountID, p_postID, 'Saved')
    ON DUPLICATE KEY UPDATE Type = 'Saved';
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_HidePost(IN p_accountID INT, IN p_postID INT)
BEGIN
    INSERT INTO SavedPost (AccountID, PostID, Type)
    VALUES (p_accountID, p_postID, 'Hidden')
    ON DUPLICATE KEY UPDATE Type = 'Hidden';
END //
DELIMITER ;


-- =============================
-- 3️⃣ NHÓM BẠN BÈ (FRIENDS)
-- =============================

DELIMITER //
CREATE PROCEDURE sp_SendFriendRequest(IN p_senderID INT, IN p_receiverID INT)
BEGIN
    INSERT INTO FriendRequest (SenderID, ReceiverID, Status)
    VALUES (p_senderID, p_receiverID, 'Pending');
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_CancelFriendRequest(IN p_senderID INT, IN p_receiverID INT)
BEGIN
    DELETE FROM FriendRequest
    WHERE SenderID = p_senderID AND ReceiverID = p_receiverID AND Status = 'Pending';
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_AcceptFriendRequest(IN p_requestID INT)
BEGIN
    UPDATE FriendRequest SET Status = 'Accepted' WHERE RequestID = p_requestID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_RejectFriendRequest(IN p_requestID INT)
BEGIN
    UPDATE FriendRequest SET Status = 'Rejected' WHERE RequestID = p_requestID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_RemoveFriend(IN p_user1 INT, IN p_user2 INT)
BEGIN
    DELETE FROM Friendship
    WHERE (Account1ID = p_user1 AND Account2ID = p_user2)
       OR (Account1ID = p_user2 AND Account2ID = p_user1);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_GetFriendList(IN p_accountID INT)
BEGIN
    SELECT a.AccountID, a.Username, p.AvatarURL
    FROM Friendship f
    JOIN Account a ON (a.AccountID = f.Account2ID OR a.AccountID = f.Account1ID)
    LEFT JOIN Profile p ON p.AccountID = a.AccountID
    WHERE (f.Account1ID = p_accountID OR f.Account2ID = p_accountID)
      AND a.AccountID <> p_accountID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_GetFriendRequests(IN p_accountID INT)
BEGIN
    SELECT fr.RequestID, s.Username AS SenderName, s.AccountID AS SenderID
    FROM FriendRequest fr
    JOIN Account s ON fr.SenderID = s.AccountID
    WHERE fr.ReceiverID = p_accountID AND fr.Status = 'Pending';
END //
DELIMITER ;


-- =============================
-- 4️⃣ NHÓM TIN NHẮN (MESSAGES)
-- =============================

DELIMITER //
CREATE PROCEDURE sp_CreateChatBox(IN p_user1 INT, IN p_user2 INT)
BEGIN
    INSERT INTO ChatBox (Account1ID, Account2ID, Status)
    VALUES (p_user1, p_user2, 'active');
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_GetChatList(IN p_accountID INT)
BEGIN
    SELECT * FROM ChatBox
    WHERE Account1ID = p_accountID OR Account2ID = p_accountID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_GetMessagesByChatId(IN p_chatID INT)
BEGIN
    SELECT m.*, a.Username, p.AvatarURL
    FROM Message m
    JOIN Account a ON m.SenderID = a.AccountID
    LEFT JOIN Profile p ON a.AccountID = p.AccountID
    WHERE m.ChatBoxID = p_chatID
    ORDER BY m.SentTime ASC;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_SendMessage(IN p_chatID INT, IN p_senderID INT, IN p_content TEXT)
BEGIN
    INSERT INTO Message (ChatBoxID, SenderID, Content)
    VALUES (p_chatID, p_senderID, p_content);
    UPDATE ChatBox SET LastMessageTime = NOW() WHERE ChatBoxID = p_chatID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_DeleteMessage(IN p_messageID INT)
BEGIN
    DELETE FROM Message WHERE MessageID = p_messageID;
END //
DELIMITER ;


-- =============================
-- 5️⃣ NHÓM THÔNG BÁO (NOTIFICATIONS)
-- =============================

DELIMITER //
CREATE PROCEDURE sp_CreateNotification(IN p_receiverID INT, IN p_type VARCHAR(50), IN p_content VARCHAR(255))
BEGIN
    INSERT INTO Notification (ReceiverID, Type, Content)
    VALUES (p_receiverID, p_type, p_content);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_GetNotifications(IN p_accountID INT)
BEGIN
    SELECT * FROM Notification
    WHERE ReceiverID = p_accountID
    ORDER BY CreatedAt DESC;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_MarkNotificationAsRead(IN p_notificationID INT)
BEGIN
    UPDATE Notification SET IsRead = TRUE WHERE NotificationID = p_notificationID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_DeleteNotification(IN p_notificationID INT)
BEGIN
    DELETE FROM Notification WHERE NotificationID = p_notificationID;
END //
DELIMITER ;


-- =============================
-- 6️⃣ NHÓM TÌM KIẾM (SEARCH)
-- =============================

DELIMITER //
CREATE PROCEDURE sp_SearchUsers(IN p_keyword VARCHAR(100))
BEGIN
    SELECT a.AccountID, a.Username, p.AvatarURL
    FROM Account a
    LEFT JOIN Profile p ON a.AccountID = p.AccountID
    WHERE a.Username LIKE CONCAT('%', p_keyword, '%')
       OR a.Email LIKE CONCAT('%', p_keyword, '%');
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_SearchPosts(IN p_keyword VARCHAR(255))
BEGIN
    SELECT p.*, a.Username
    FROM Post p
    JOIN Account a ON p.AuthorID = a.AccountID
    WHERE p.Content LIKE CONCAT('%', p_keyword, '%')
    ORDER BY p.PostTime DESC;
END //
DELIMITER ;
