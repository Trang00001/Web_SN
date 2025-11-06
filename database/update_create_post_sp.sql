USE SocialNetworkDB;

DROP PROCEDURE IF EXISTS sp_CreatePost;

DELIMITER //
CREATE PROCEDURE sp_CreatePost(IN p_authorID INT, IN p_content TEXT, IN p_categoryID INT, OUT p_postID INT)
BEGIN
    INSERT INTO Post (AuthorID, Content, CategoryID) VALUES (p_authorID, p_content, p_categoryID);
    SET p_postID = LAST_INSERT_ID();
END //
DELIMITER ;
