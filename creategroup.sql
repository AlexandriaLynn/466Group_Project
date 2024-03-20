--Ally
--Create tables
--User(ID (prim), name, phone_num, prim_addr, email, prim_card)
CREATE TABLE User(
    ID VARCHAR(5) primary key,
    fname CHAR(20) not null, 
    lname CHAR(20) not null, 
    prim_addr VARCHAR(50) not null, 
    prim_card BIGINT(16) not null, 
    email CHAR (100) not null
); 

-- Just has Emp_ID primary key referencing User Table
CREATE TABLE Employee(
    emp_id VARCHAR(5) not null,
    primary key(emp_id),
    foreign key(emp_id) references User(ID)
);

-- Just has Own_ID primary key referencing User Table
CREATE TABLE Owners(
    own_id VARCHAR(5) not null, 
    primary key(own_id), 
    foreign key(own_id) references User(ID)
);

-- Just has Cust_id primary key referencing User Table
CREATE TABLE Customer(
    cust_id VARCHAR(5) not null,
    primary key(cust_id),
    foreign key(cust_id) references User(ID)
);

--Orders(Order# (prim), ship_addr, billing_info, date, emp_id(foreign key), status)
CREATE TABLE Orders(
    order_num BIGINT(10) primary key, 
    ship_addr VARCHAR(50) not null,
    billing_info BIGINT(16) not null, 
    date_placed date not null, 
    note char(200) null,
    order_status char(10) not null, 
    emp_id varchar(5) not null,
    foreign key (emp_id) references Employee(emp_id) 
);

--ShoppingCart(cart_id(primary), cust_id(foreign key), cur_price)
CREATE TABLE Cart(
    cart_id int(10) primary key, 
    cur_price decimal(7,2) not null, 
    cust_id varchar(5) not null,
    foreign key(cust_id) references Customer(cust_id)
);

--Inventory(inv_id(primary key), quan_in_stock)
CREATE TABLE Inventory(
    inv_id int(5) primary key,
    inv_type char(5) not null
);

--Product(prod_id(primary key), name, price, type, inv_id(foreign key))
CREATE TABLE Product(
    prod_id BIGINT(20) primary key,
    prod_name char(50) not null,
    prod_price decimal(6,2) not null,
    prod_type char(5) not null,
    genre char(30) not null
);

--InCart(cart_id (foreign and primary), prod_id(foreign and primary), quan_of_prod, price_of_prod)
CREATE TABLE InCart(
    cart_id int(10) not null,
    prod_id BIGINT(20) not null,
    quan_of_prod int(4), 
    price_of_prod decimal(7,2) not null,
    primary key(cart_id, prod_id),
    foreign key(cart_id) references Cart(cart_id),
    foreign key(prod_id) references Product(prod_id)
);

CREATE TABLE PlacingOrder(
    cust_id VARCHAR(5) not null,
    cart_id int(10) not null,
    order_num BIGINT(10) not null,
    tracking_num BIGINT(20), 
    total_cart_price decimal(7,2),
    primary key(cart_id, order_num),
    foreign key(cust_id) references Customer(cust_id),
    foreign key (cart_id) references Cart(cart_id), 
    foreign key(order_num) references Orders(order_num)
);

CREATE TABLE Holds(
    prod_id BIGINT(20) not null, 
    inv_id int(5) not null,
    quan_in_stock int(4) not null, 
    primary key (prod_id, inv_id),
    foreign key (prod_id) references Product(prod_id),
    foreign key (inv_id) references Inventory(inv_id)
);

CREATE TABLE ProductOrder(
    prod_id BIGINT(20) not null,
    order_num BIGINT(10) not null, 
    quan_in_order int(4) not null,
    primary key(prod_id, order_num),
    foreign key (prod_id) references Product(prod_id), 
    foreign key(order_num) references Orders(order_num)
);