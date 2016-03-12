-- add user
INSERT INTO user VALUES (default, username, name, password, public);

-- add egg
INSERT INTO eggs VALUES (default, www, redirect, riddle, val);

-- add egg link
INSERT INTO egglinks VALUES (from, to);

-- add found
INSERT INTO found VALUES (user, egg, now());