CREATE TABLE videos (
		id serial,
		UNIQUE (hash character(32)),
		path text,
		name text);
