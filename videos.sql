CREATE TABLE videos (
		id integer,
		hash character(32) unique,
		path text,
		name text
	);
CREATE TABLE settings (
		name character(255) unique,
		value character(255)
	);
