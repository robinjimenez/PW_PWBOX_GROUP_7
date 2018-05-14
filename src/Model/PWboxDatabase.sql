USE PWbox;
CREATE TABLE user (
  username varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  email varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  password char(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  birthdate datetime NOT NULL,
  PRIMARY KEY (username),
  UNIQUE KEY `UNIQUE_EMAIL` (email),
  UNIQUE KEY `UNIQUE_USER` (username)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE element (
  id bigint NOT NULL AUTO_INCREMENT,
  parent bigint,
  name varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  owner varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  PRIMARY KEY (id,parent),
  FOREIGN KEY (parent) REFERENCES element(id),
  FOREIGN KEY (owner) REFERENCES user(username)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE user_element (
  user varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  element bigint,
  role varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  PRIMARY KEY (user,element),
  FOREIGN KEY (user) REFERENCES user(username),
  FOREIGN KEY (element) REFERENCES element(id)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE closing (
  parent bigint,
  child bigint,
  depth varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  PRIMARY KEY (parent,child),
  FOREIGN KEY (parent) REFERENCES element(id),
  FOREIGN KEY (child) REFERENCES element(id)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

/*DROP TABLE user;
DROP TABLE element;
DROP TABLE user_element;
DROP TABLE closing;*/