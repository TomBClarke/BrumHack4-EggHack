-- 1
INSERT INTO eggs VALUES (default, "The Start Address", "Where it all begins.", 0);

-- 2
INSERT INTO eggs VALUES (default, "54.84.108.88", "1) A programmer's best friend. 2) You may enjoy some other of the author's works...", 1); -- Stack overflow. Tom's site 

-- 3
INSERT INTO eggs VALUES (default, "stackoverflow.com", "Your gracious hosts.", 1); -- The university of birmingham

-- 4
INSERT INTO eggs VALUES (default, "tombclarke.co.uk", "I want it that way~ - Weird Al", 1); -- Ebay

-- 5
INSERT INTO eggs VALUES (default, "ebay.co.uk", "Some numbers 129.42", 1); -- Part 1 of ip address

-- 6
INSERT INTO eggs VALUES (default, "birmingham.ac.uk", "Some more numbers 38.1", 1); -- Part 2 of ip address

-- 7
INSERT INTO eggs VALUES (default, "ibm.com", "52.450920, -1.932224 | 10:30/12/3/2016-16:30/13/3/2106", 2); -- Coordinates and timings for brumhack.

-- 8
INSERT INTO eggs VALUES (default, "brumhack.co.uk", "Congratulations", 4);

INSERT INTO egglinks VALUES (1, 2);
INSERT INTO egglinks VALUES (2, 3);
INSERT INTO egglinks VALUES (2, 4);
INSERT INTO egglinks VALUES (3, 6);
INSERT INTO egglinks VALUES (4, 5);
INSERT INTO egglinks VALUES (5, 7);
INSERT INTO egglinks VALUES (6, 7);
INSERT INTO egglinks VALUES (7, 8);