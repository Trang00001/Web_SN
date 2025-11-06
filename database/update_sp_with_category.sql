USE SocialNetworkDB;

-- Add CategoryID column to Post table if not exists
ALTER TABLE Post 
ADD COLUMN IF NOT EXISTS CategoryID INT DEFAULT 1;

-- Add foreign key constraint

ALTER TABLE Post
ADD CONSTRAINT fk_post_category
FOREIGN KEY (CategoryID) REFERENCES PostCategory(CategoryID);


-- Update sp_GetAllPosts to include category
DROP PROCEDURE IF EXISTS sp_GetAllPosts;
DELIMITER //
CREATE PROCEDURE sp_GetAllPosts()
BEGIN
    SELECT 
        p.PostID,
        p.Content,
        p.PostTime AS CreatedAt,
        p.CategoryID,
        pc.CategoryName,
        (SELECT i.ImageURL FROM Image i WHERE i.PostID = p.PostID LIMIT 1) AS ImageUrl,
        a.Username,
        pr.AvatarURL,
        (SELECT COUNT(*) FROM PostLike pl WHERE pl.PostID = p.PostID) AS LikeCount,
        (SELECT COUNT(*) FROM Comment c WHERE c.PostID = p.PostID) AS CommentCount
    FROM Post p
    JOIN Account a ON p.AuthorID = a.AccountID
    LEFT JOIN Profile pr ON a.AccountID = pr.AccountID
    LEFT JOIN PostCategory pc ON p.CategoryID = pc.CategoryID
    ORDER BY p.PostTime DESC;
END //
DELIMITER ;

-- Update sp_GetPostById to include category
DROP PROCEDURE IF EXISTS sp_GetPostById;
DELIMITER //
CREATE PROCEDURE sp_GetPostById(IN p_postID INT)
BEGIN
    SELECT 
        p.*, 
        a.Username, 
        pr.AvatarURL,
        pc.CategoryName,
        (SELECT COUNT(*) FROM PostLike pl WHERE pl.PostID = p.PostID) AS LikeCount,
        (SELECT COUNT(*) FROM Comment c WHERE c.PostID = p.PostID) AS CommentCount
    FROM Post p
    JOIN Account a ON p.AuthorID = a.AccountID
    LEFT JOIN Profile pr ON a.AccountID = pr.AccountID
    LEFT JOIN PostCategory pc ON p.CategoryID = pc.CategoryID
    WHERE p.PostID = p_postID;
END //
DELIMITER ;

-- Also update sp_CreatePost to accept CategoryID (if not already done)
DROP PROCEDURE IF EXISTS sp_CreatePost;
DELIMITER //
CREATE PROCEDURE sp_CreatePost(IN p_authorID INT, IN p_content TEXT, IN p_categoryID INT, OUT p_postID INT)
BEGIN
    INSERT INTO Post (AuthorID, Content, CategoryID) VALUES (p_authorID, p_content, p_categoryID);
    SET p_postID = LAST_INSERT_ID();
END //
DELIMITER ;
