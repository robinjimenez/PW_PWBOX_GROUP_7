USE PWbox;

DROP TABLE closure;
DROP TABLE user_element;
DROP TABLE element;
DROP TABLE user;

CREATE TABLE IF NOT EXISTS user (
  username varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  email varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  password char(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  birthdate datetime NOT NULL,
  PRIMARY KEY (username),
  UNIQUE KEY `UNIQUE_EMAIL` (email),
  UNIQUE KEY `UNIQUE_USER` (username)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE IF NOT EXISTS element (
  id bigint NOT NULL AUTO_INCREMENT,
  parent bigint,
  name varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  owner varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  FOREIGN KEY (parent) REFERENCES element(id),
  FOREIGN KEY (owner) REFERENCES user(username),
  UNIQUE KEY `UNIQUE_NAME` (name)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE IF NOT EXISTS user_element (
  user varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  element bigint,
  role varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  PRIMARY KEY (user,element),
  FOREIGN KEY (user) REFERENCES user(username),
  FOREIGN KEY (element) REFERENCES element(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE IF NOT EXISTS closure (
  parent bigint,
  child bigint,
  depth int DEFAULT 0,
  PRIMARY KEY (parent,child),
  FOREIGN KEY (parent) REFERENCES element(id),
  FOREIGN KEY (child) REFERENCES element(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
