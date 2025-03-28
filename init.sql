DROP TABLE IF EXISTS exercises;
DROP TABLE IF EXISTS statistic;
DROP TABLE IF EXISTS meals;
DROP TABLE IF EXISTS recipes;
DROP TABLE IF EXISTS medals_settings;
DROP TABLE IF EXISTS users;

CREATE TABLE users
(
    id                     INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    firstname              VARCHAR(255)        NOT NULL,
    lastname               VARCHAR(255)        NOT NULL,
    email                  VARCHAR(255) UNIQUE NOT NULL,
    password               VARCHAR(255)        NOT NULL,
    created_at             DATETIME            NOT NULL,
    profilePictureLocation VARCHAR(255),
    energy_expenditure INT
);

CREATE TABLE exercises
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    title         VARCHAR(255) NOT NULL,
    description   TEXT         NOT NULL,
    kcal DOUBLE NOT NULL,
    exerciseImage VARCHAR(255)
);

CREATE TABLE statistic
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT  NOT NULL,
    `date`  DATE NOT NULL DEFAULT NOW(),
    `key`   ENUM('steps', 'kilometers', 'calories_burned'),
    value   INT  NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    UNIQUE (user_id, `date`, `key`)
);

CREATE TABLE medals_settings
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    `key`      ENUM('steps', 'kilometers', 'calories_burned'),
    stage      ENUM('bronze', 'silver', 'gold', 'platinum', 'diamond'),
    `value`    INT          NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    UNIQUE (`key`, stage, `value`)
);

CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(255) NOT NULL UNIQUE,
    description LONGTEXT,
    calories DECIMAL(6, 2) NOT NULL,
    amount_in_grams DECIMAL(5, 2) NOT NULL CHECK ( amount_in_grams > 0 ),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE meals (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT NOT NULL,
    recipes_id INT NOT NULL,
    meal_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (recipes_id) REFERENCES recipes (id) ON DELETE CASCADE
);

INSERT INTO exercises(title, description, kcal)
VALUES ("Leg Power & Endurance Boost",
        "5 km Lauf auf mittlerer Geschwindigkeit, gefolgt von 3x10 Kniebeugen mit Zusatzgewicht. Perfekt für Ausdauer und Beinkraft!",
        "200");
INSERT INTO exercises(title, description, kcal)
VALUES ("Upper Body Strength",
        "3x12 Klimmzüge, 3x10 Bankdrücken, 3x15 Dips. Ideal für Muskelaufbau im Oberkörper.",
        350);
INSERT INTO exercises(title, description, kcal)
VALUES ("HIIT Sprint & Jump",
        "10x 30 Sekunden Sprint mit 30 Sekunden Pause, gefolgt von 3x20 Sprungkniebeugen. Perfekt zur Fettverbrennung!",
        450);
INSERT INTO exercises(title, description, kcal)
VALUES ("Full Body Burn",
        "3x10 Kreuzheben, 3x15 Liegestütze, 3x20 Kettlebell-Swings. Trainiert den ganzen Körper!",
        400);
INSERT INTO exercises(title, description, kcal)
VALUES ("Core Blaster",
        "3x20 Crunches, 3x30 Sekunden Plank, 3x15 Beinheben. Perfekt für starke Bauchmuskeln.",
        250);
INSERT INTO exercises(title, description, kcal)
VALUES ("Beginner Home Workout",
        "3x15 Kniebeugen, 3x10 Liegestütze, 3x20 Hampelmänner. Kein Equipment nötig!",
        180);
INSERT INTO exercises(title, description, kcal)
VALUES ("Yoga Flow",
        "45 Minuten Vinyasa Yoga mit Fokus auf Flexibilität und Entspannung.",
        120);
INSERT INTO exercises(title, description, kcal)
VALUES ("Leg Day Fire",
        "4x12 Kniebeugen, 3x15 Ausfallschritte, 3x20 Wadenheben. Perfekt für starke Beine und Po.",
        300);

INSERT INTO medals_settings(`key`, stage, `value`, image_path)
VALUES
    ('steps', 'bronze', 100000, 'img/medals/bronze.png'),
    ('steps', 'silver', 150000, 'img/medals/silver.png'),
    ('steps', 'gold', 200000, 'img/medals/gold.png'),
    ('steps', 'platinum', 250000, 'img/medals/platinum.png'),
    ('steps', 'diamond', 300000, 'img/medals/diamond.png');