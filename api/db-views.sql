CREATE OR REPLACE VIEW user_view AS
SELECT

A.user_id,
A.first_name,
A.last_name,
A.email,
A.password,
A.role_id,
A.region_id,
B.name AS region_name,
B.code,
A.date_created,
A.active

FROM user A
LEFT JOIN region B ON B.region_id = A.region_id;


CREATE OR REPLACE VIEW admin_user_view AS
SELECT

A.user_id,
A.first_name,
A.last_name,
A.email,
A.password,
A.role_id,
B.machine_name AS role_machine_name,
B.label AS role_label,
A.region_id,
C.name AS region,
C.code AS region_code,
B.permission_list,
A.date_created,
A.active

FROM admin_user A 
LEFT JOIN admin_role B ON B.role_id = A.role_id
LEFT JOIN region C ON C.region_id = A.region_id;









CREATE OR REPLACE VIEW district_view AS
SELECT

A.district_id,
A.name,
A.region_id,
B.name AS region,
B.code AS region_code,
A.active

FROM app_district A 
LEFT JOIN region B ON B.region_id = A.region_id;



CREATE OR REPLACE VIEW sub_county_view AS
SELECT

A.sub_county_id,
A.district_id,
B.name AS district,
C.region_id,
C.name AS region,
C.code AS region_code,
A.name,
A.active

FROM app_sub_county A 
LEFT JOIN app_district B ON B.district_id = A.district_id
LEFT JOIN region C ON C.region_id = B.region_id;




CREATE OR REPLACE VIEW parish_view AS
SELECT

A.parish_id,
A.sub_county_id,
B.name AS sub_county,
C.district_id,
C.name AS district,
D.region_id,
D.name AS region,
D.code AS region_code,
A.name,
A.active

FROM app_parish A 
LEFT JOIN app_sub_county B ON B.sub_county_id = A.sub_county_id
LEFT JOIN app_district C ON C.district_id = B.district_id
LEFT JOIN region D ON D.region_id = C.region_id;



CREATE OR REPLACE VIEW village_view AS
SELECT

A.village_id,
A.parish_id,
B.name AS parish,
C.sub_county_id,
C.name AS sub_county,
D.district_id,
D.name AS district,
E.region_id,
E.name AS region,
E.code AS region_code,
A.name,
A.active

FROM app_village A 
LEFT JOIN app_parish B ON B.parish_id = A.parish_id
LEFT JOIN app_sub_county C ON C.sub_county_id = B.sub_county_id
LEFT JOIN app_district D ON D.district_id = C.district_id
LEFT JOIN region E ON E.region_id = D.region_id;




CREATE OR REPLACE VIEW response_with_location_view AS
SELECT

A.response_id,
A.form_id,
B.region_id,
B.region,
B.district_id,
B.district,
B.sub_county_id,
B.sub_county,
B.parish_id,
B.parish,
B.village_id,
B.name AS village,
A.entry_form_id,
A.creator_id,
C.first_name,
C.last_name,

A.fu_creator_id,
F.first_name AS fu_first_name,
F.last_name AS fu_last_name,
D.name as creator_region,
A.title,
A.sub_title,
A.json_response,
A.json_followup,
JSON_LENGTH(A.json_followup) AS followup_count,
JSON_EXTRACT(A.json_followup, CONCAT("$[", IF(JSON_LENGTH(A.json_followup) > 0, JSON_LENGTH(A.json_followup)-1, 0),"]") ) AS recent_followup,
E.is_followup,
A.date_created,
A.date_modified,
A.active

FROM response A 
LEFT JOIN village_view B ON( B.name = A.json_response->"$.qn_9" OR  B.name = A.json_response->"$.qn9")
LEFT JOIN user C ON C.user_id = A.creator_id
LEFT JOIN user F ON F.user_id = A.fu_creator_id
LEFT JOIN region D ON D.region_id = C.region_id
LEFT JOIN question_form E ON E.form_id = A.form_id
WHERE (B.name = A.json_response->"$.qn_9" AND B.parish = A.json_response->"$.qn_8") OR  (B.name = A.json_response->"$.qn9" AND B.parish = A.json_response->"$.qn8");


-- WHERE B.name = JSON_EXTRACT(A.json_response, "$.qn_9") AND B.parish = JSON_EXTRACT(A.json_response, "$.qn_8");





-- 4 District
-- 7 Sub county
-- 8 Parish
-- 9 Village








CREATE OR REPLACE VIEW response_with_parish_location_view AS
SELECT

A.response_id,
A.form_id,
B.region_id,
B.region,
B.district_id,
B.district,
B.sub_county_id,
B.sub_county,
B.parish_id,
B.name AS parish,
-- B.village_id,
-- B.name AS village,
A.entry_form_id,
A.creator_id,
C.first_name,
C.last_name,

A.fu_creator_id,
F.first_name AS fu_first_name,
F.last_name AS fu_last_name,
D.name as creator_region,
A.title,
A.sub_title,
A.json_response,
A.json_followup,
JSON_LENGTH(A.json_followup) AS followup_count,
JSON_EXTRACT(A.json_followup, CONCAT("$[", IF(JSON_LENGTH(A.json_followup) > 0, JSON_LENGTH(A.json_followup)-1, 0),"]") ) AS recent_followup,
E.is_followup,
A.date_created,
A.date_modified,
A.active

FROM response A 
LEFT JOIN parish_view B ON( B.name = A.json_response->"$.qn_8" OR  B.name = A.json_response->"$.qn8")
LEFT JOIN user C ON C.user_id = A.creator_id
LEFT JOIN user F ON F.user_id = A.fu_creator_id
LEFT JOIN region D ON D.region_id = C.region_id
LEFT JOIN question_form E ON E.form_id = A.form_id
WHERE (B.name = A.json_response->"$.qn_8" AND B.sub_county = A.json_response->"$.qn_7") OR  (B.name = A.json_response->"$.qn8" AND B.sub_county = A.json_response->"$.qn7");
-- WHERE (B.name = A.json_response->"$.qn_8" AND B.parish = A.json_response->"$.qn_7") OR  (B.name = A.json_response->"$.qn8" AND B.parish = A.json_response->"$.qn7");








CREATE OR REPLACE VIEW response_with_sub_county_location_view AS
SELECT

A.response_id,
A.form_id,
B.region_id,
B.region,
B.district_id,
B.district,
B.sub_county_id,
B.name AS sub_county,
-- B.parish_id,
-- B.parish,
-- B.village_id,
-- B.name AS village,
A.entry_form_id,
A.creator_id,
C.first_name,
C.last_name,

A.fu_creator_id,
F.first_name AS fu_first_name,
F.last_name AS fu_last_name,
D.name as creator_region,
A.title,
A.sub_title,
A.json_response,
A.json_followup,
JSON_LENGTH(A.json_followup) AS followup_count,
JSON_EXTRACT(A.json_followup, CONCAT("$[", IF(JSON_LENGTH(A.json_followup) > 0, JSON_LENGTH(A.json_followup)-1, 0),"]") ) AS recent_followup,
E.is_followup,
A.date_created,
A.date_modified,
A.active

FROM response A 
LEFT JOIN sub_county_view B ON( B.name = A.json_response->"$.qn_7" OR B.name = A.json_response->"$.qn7")
LEFT JOIN user C ON C.user_id = A.creator_id
LEFT JOIN user F ON F.user_id = A.fu_creator_id
LEFT JOIN region D ON D.region_id = C.region_id
LEFT JOIN question_form E ON E.form_id = A.form_id
WHERE (B.name = A.json_response->"$.qn_7" AND B.district = A.json_response->"$.qn_4") OR (B.name = A.json_response->"$.qn7" AND B.district = A.json_response->"$.qn4");











CREATE OR REPLACE VIEW response_with_district_location_view AS
SELECT

A.response_id,
A.form_id,
B.region_id,
B.region,
B.district_id,
B.name AS district,
-- B.sub_county_id,
-- B.sub_county,
-- B.parish_id,
-- B.parish,
-- B.village_id,
-- B.name AS village,
A.entry_form_id,
A.creator_id,
C.first_name,
C.last_name,

A.fu_creator_id,
F.first_name AS fu_first_name,
F.last_name AS fu_last_name,
D.name as creator_region,
A.title,
A.sub_title,
A.json_response,
A.json_followup,
JSON_LENGTH(A.json_followup) AS followup_count,
JSON_EXTRACT(A.json_followup, CONCAT("$[", IF(JSON_LENGTH(A.json_followup) > 0, JSON_LENGTH(A.json_followup)-1, 0),"]") ) AS recent_followup,
E.is_followup,
A.date_created,
A.date_modified,
A.active

FROM response A 
LEFT JOIN district_view B ON( B.name = A.json_response->"$.qn_4" OR B.name = A.json_response->"$.qn4")
LEFT JOIN user C ON C.user_id = A.creator_id
LEFT JOIN user F ON F.user_id = A.fu_creator_id
LEFT JOIN region D ON D.region_id = C.region_id
LEFT JOIN question_form E ON E.form_id = A.form_id
WHERE (B.name = A.json_response->"$.qn_4") OR (B.name = A.json_response->"$.qn4");











CREATE OR REPLACE VIEW response_with_location_view AS
SELECT

A.response_id,
A.form_id,
B.region_id,
B.region,
B.district_id,
B.district,
B.sub_county_id,
B.sub_county,
B.parish_id,
B.parish,
B.village_id,
B.name AS village,
A.entry_form_id,
A.creator_id,
C.first_name,
C.last_name,
D.name as creator_region,
A.title,
A.sub_title,
A.json_response,
A.json_followup,
JSON_LENGTH(A.json_followup) AS followup_count,
JSON_EXTRACT(A.json_followup, CONCAT("$[", IF(JSON_LENGTH(A.json_followup) > 0, JSON_LENGTH(A.json_followup)-1, 0),"]") ) AS recent_followup,
E.is_followup,
A.date_created,
A.date_modified,
A.active

FROM response A 
-- LEFT JOIN village_view B ON B.name IN (A.json_response->"$.qn_9",A.json_response->"$.qn9")
LEFT JOIN village_view B ON B.name IN (SELECT JSON_EXTRACT(A.json_response, "$.qn_9", "$.qn9"))
-- LEFT JOIN village_view B ON B.name = JSON_EXTRACT(A.json_response, "$.qn_9", "$.qn9")
LEFT JOIN user C ON C.user_id = A.creator_id
LEFT JOIN region D ON D.region_id = C.region_id
LEFT JOIN question_form E ON E.form_id = A.form_id
WHERE B.name IN (A.json_response->"$.qn_9",A.json_response->"$.qn9") AND B.parish IN (A.json_response->"$.qn_8", A.json_response->"$.qn8");



-- value is in bytes
-- Input : disk_free_space("D:");
-- Output : 10969328844
-- disk_free_space ( string $directory )
-- disk_total_space ( string $directory )