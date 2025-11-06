USE SocialNetworkDB;

-- Cập nhật stored procedure để kiểm tra email/username case-insensitive
DROP PROCEDURE IF EXISTS sp_RegisterUser;

DELIMITER //
CREATE PROCEDURE sp_RegisterUser(
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255),
    IN p_username VARCHAR(50)
)
BEGIN
    -- Normalize email to lowercase for comparison
    SET p_email = LOWER(TRIM(p_email));
    SET p_username = TRIM(p_username);
    
    -- Check case-insensitive for both email and username
    IF EXISTS (SELECT 1 FROM Account WHERE LOWER(Email) = p_email OR LOWER(Username) = LOWER(p_username)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email or username already exists';
    ELSE
        INSERT INTO Account (Email, PasswordHash, Username)
        VALUES (p_email, p_password, p_username);
    END IF;
END //
DELIMITER ;

