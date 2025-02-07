-- for find a table engines
show engines;

-- find database engine type
show table status;

-- above 5.5 default create InnoDB
create table t1(id int, name char(30)) 

-- create table with myisam engine
create table t1(id int, name char(30)) engine = myisam 

-- create table with memory engine
create table t1(id int, name char(30)) engine = memory
Hash based, stored in memeory, useful for temporary tables. 

-- create table with archive engine
create table t1(id int, name char(30)) engine = archive

-- create table with csv engine
create table t1(id int, name char(30)) engine = csv;
1. csv wont suports null values only not null, 
2. import and export easy comparing than other.

-- create table with blackhole engine
create table t1(id int, name char(30)) engine = blackhole;
1. null storage engine
2. aything you write to it disappears

-- create table with MERGE engine
create table t1(EMPID int NOT NULL auto increment, name char(20), INDEX(EMPID)) ENGINE = MERGE UNION = (EMP1,EMP2)
1. for merging two table with have same column name, same data type.
