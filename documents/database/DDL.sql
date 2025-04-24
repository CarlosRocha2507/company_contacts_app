-- Create database table schema
CREATE TABLE App_Contacts (contact_id int(10) NOT NULL AUTO_INCREMENT, contry_number varchar(255) NOT NULL, contact int(10) NOT NULL, extension int(10), email varchar(255), department_id int(10) NOT NULL, department_only bit(1) DEFAULT 1, is_active bit(1) DEFAULT 1 NOT NULL, PRIMARY KEY (contact_id), INDEX (contact_id));
CREATE TABLE App_Departments (department_id int(10) NOT NULL AUTO_INCREMENT, department varchar(255) NOT NULL, department_code int(11) DEFAULT 0000 NOT NULL UNIQUE, is_active bit(1) DEFAULT 1 NOT NULL, PRIMARY KEY (department_id), INDEX (department_id));
CREATE TABLE App_Departments_Extensions (extension int(11) NOT NULL, department_id int(10) NOT NULL, PRIMARY KEY (extension, department_id));
CREATE TABLE App_Users (user_id int(10) NOT NULL AUTO_INCREMENT, person_name varchar(255), user_name varchar(255) NOT NULL UNIQUE, user_password varchar(255) NOT NULL, is_active bit(1) DEFAULT 1 NOT NULL, PRIMARY KEY (user_id), INDEX (user_id));
ALTER TABLE App_Departments_Extensions ADD CONSTRAINT FKApp_Depart572157 FOREIGN KEY (department_id) REFERENCES App_Departments (department_id);
ALTER TABLE App_Contacts ADD CONSTRAINT FKApp_Contac849870 FOREIGN KEY (department_id) REFERENCES App_Departments (department_id);

-- Drop database table schema
ALTER TABLE App_Departments_Extensions DROP FOREIGN KEY FKApp_Depart572157;
ALTER TABLE App_Contacts DROP FOREIGN KEY FKApp_Contac849870;
DROP TABLE IF EXISTS App_Contacts;
DROP TABLE IF EXISTS App_Departments;
DROP TABLE IF EXISTS App_Departments_Extensions;
DROP TABLE IF EXISTS App_Users;


