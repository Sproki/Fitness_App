SET @userIDTest = 2;

INSERT INTO statistic(user_id, date, `key`, value)
VALUES
  (@userIDTest, SYSDATE(), 'steps', 4100),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 1 DAY), 'steps', 5300),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 2 DAY), 'steps', 1200),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 3 DAY), 'steps', 7500),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 4 DAY), 'steps', 3200),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 5 DAY), 'steps', 5300),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 6 DAY), 'steps', 1400),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 7 DAY), 'steps', 5300),
  (@userIDTest, SYSDATE(), 'kilometers', 4),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 1 DAY), 'kilometers', 5),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 2 DAY), 'kilometers', 1),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 3 DAY), 'kilometers', 7),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 4 DAY), 'kilometers', 3),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 5 DAY), 'kilometers', 5),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 6 DAY), 'kilometers', 1),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 7 DAY), 'kilometers', 5),
  (@userIDTest, SYSDATE(), 'calories_burned', 300),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 1 DAY), 'calories_burned', 200),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 2 DAY), 'calories_burned', 100),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 3 DAY), 'calories_burned', 400),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 4 DAY), 'calories_burned', 200),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 5 DAY), 'calories_burned', 100),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 6 DAY), 'calories_burned', 300),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 7 DAY), 'calories_burned', 100),
  (@userIDTest, SYSDATE(), 'calories_consumed', 350),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 1 DAY), 'calories_consumed', 300),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 2 DAY), 'calories_consumed', 500),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 3 DAY), 'calories_consumed', 300),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 4 DAY), 'calories_consumed', 120),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 5 DAY), 'calories_consumed', 750),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 6 DAY), 'calories_consumed', 240),
  (@userIDTest, DATE_SUB(SYSDATE(), INTERVAL 7 DAY), 'calories_consumed', 400);

INSERT INTO medals_user(user_id, medals_setting_id, date) VALUES
    (@userIDTest, 1, SYSDATE()),
    (@userIDTest, 2, SYSDATE()),
    (@userIDTest, 3, SYSDATE());