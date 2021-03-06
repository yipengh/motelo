CREATE TABLE "solution" (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`filename`	TEXT NOT NULL UNIQUE,
	`created_at`	DATETIME NOT NULL,
	`has_solution`	INTEGER NOT NULL,
	`z`	REAL,
	`t`	REAL,
	`model_id`	INTEGER NOT NULL,
	`instance_id`	INTEGER NOT NULL,
	`status`	INTEGER NOT NULL DEFAULT 1
);
CREATE TABLE "model" (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`filename`	TEXT,
	`created_at`	DATETIME NOT NULL,
	`label`	TEXT NOT NULL UNIQUE,
	`status`	INTEGER NOT NULL DEFAULT 1
);
CREATE TABLE "instance" (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`filename`	TEXT NOT NULL UNIQUE,
	`created_at`	DATETIME NOT NULL,
	`label`	TEXT NOT NULL UNIQUE,
	`nb_nodes`	INTEGER NOT NULL,
	`nb_edges`	INTEGER NOT NULL,
	`blockage_o`	INTEGER,
	`blockage_d`	INTEGER,
	`status`	INTEGER NOT NULL DEFAULT 1
);
