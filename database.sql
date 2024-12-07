-- Table: Admin
CREATE TABLE Admin (
    adminID INT AUTO_INCREMENT PRIMARY KEY,
    adminName VARCHAR(100) NOT NULL,
    adminEmail VARCHAR(150) NOT NULL UNIQUE,
    adminPassword VARCHAR(255) NOT NULL,
    adminPasswordSalt VARCHAR(255) NOT NULL,
    adminPhoneNo VARCHAR(15),
    adminPic VARCHAR(255),
    adminRole ENUM('Manager', 'Admin') NOT NULL
);

-- Table: Category
CREATE TABLE Category (
    categoryID INT AUTO_INCREMENT PRIMARY KEY,
    categoryName VARCHAR(100) NOT NULL UNIQUE,
    categoryDesc VARCHAR(255)
);

-- Table: Product
CREATE TABLE Product (
    prodID INT AUTO_INCREMENT PRIMARY KEY,
    prodName VARCHAR(200) NOT NULL,
    prodAuthor VARCHAR(100),
    prodDesc VARCHAR(255),
    prodPrice FLOAT NOT NULL,
    prodStock INT NOT NULL,
    prodStatus ENUM('Available', 'OutOfStock') NOT NULL,
    categoryID INT NOT NULL,
    FOREIGN KEY (categoryID) REFERENCES Category(categoryID)
);

-- Table: ProductImage
CREATE TABLE ProductImage (
    imageID INT AUTO_INCREMENT PRIMARY KEY,
    imageURL VARCHAR(255) NOT NULL,
    imageAltText VARCHAR(200),
    prodID INT NOT NULL,
    FOREIGN KEY (prodID) REFERENCES Product(prodID)
);

-- Table: User
CREATE TABLE User (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    userName VARCHAR(100) NOT NULL,
    userEmail VARCHAR(150) NOT NULL UNIQUE,
    userPassword VARCHAR(255) NOT NULL,
    userPasswordSalt VARCHAR(255) NOT NULL,
    userPhoneNo VARCHAR(15),
    userPic VARCHAR(255),
    userStatus ENUM('Active', 'Inactive') DEFAULT 'Active'
);

-- Table: Address
CREATE TABLE Address (
    addressID INT AUTO_INCREMENT PRIMARY KEY,
    addressLine VARCHAR(255) NOT NULL,
    postalCode VARCHAR(10) NOT NULL,
    userID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES User(userID),
    state VARCHAR(100) NOT NULL
);

-- Table: Cart
CREATE TABLE Cart (
    cartID INT AUTO_INCREMENT PRIMARY KEY,
    modifiedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    userID INT UNIQUE NOT NULL,
    FOREIGN KEY (userID) REFERENCES User(userID)
);

-- Table: Cart_Item
CREATE TABLE Cart_Item (
    cartID INT NOT NULL,
    prodID INT NOT NULL,
    cartItemsQty INT NOT NULL,
    PRIMARY KEY (cartID, prodID),
    FOREIGN KEY (cartID) REFERENCES Cart(cartID),
    FOREIGN KEY (prodID) REFERENCES Product(prodID)
);

-- Table: DiscountCode
CREATE TABLE DiscountCode (
    discountCodeID INT AUTO_INCREMENT PRIMARY KEY,
    discountCode VARCHAR(50) NOT NULL UNIQUE,
    discountPercentage FLOAT NOT NULL CHECK (discountPercentage >= 0 AND discountPercentage <= 100),
    discountDesc VARCHAR(255),
    startDate DATETIME NOT NULL,
    endDate DATETIME NOT NULL
);

-- Table: ShippingMethod
CREATE TABLE ShippingMethod (
    shippingMethodID INT AUTO_INCREMENT PRIMARY KEY,
    shippingName VARCHAR(100) NOT NULL,
    shippingDescription VARCHAR(255),
    shippingCost FLOAT NOT NULL,
    estimatedDeliveryDay INT NOT NULL
);

-- Table: Order
CREATE TABLE `Order` (
    orderID INT AUTO_INCREMENT PRIMARY KEY,
    discountTotal FLOAT DEFAULT 0,
    grandTotal FLOAT NOT NULL,
    orderDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    orderStatus ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') NOT NULL,
    paymentMethod ENUM('CreditCard', 'Tng', 'BankTransfer') NOT NULL,
    shippingAddress VARCHAR(255) NOT NULL,
    discountCodeID INT,
    shippingMethodID INT NOT NULL,
    userID INT NOT NULL,
    FOREIGN KEY (discountCodeID) REFERENCES DiscountCode(discountCodeID),
    FOREIGN KEY (shippingMethodID) REFERENCES ShippingMethod(shippingMethodID),
    FOREIGN KEY (userID) REFERENCES User(userID)
);

-- Table: Order_Item
CREATE TABLE Order_Item (
    orderID INT NOT NULL,
    prodID INT NOT NULL,
    orderItemsQty INT NOT NULL CHECK (orderItemsQty > 0),
    PRIMARY KEY (orderID, prodID),
    FOREIGN KEY (orderID) REFERENCES `Order`(orderID),
    FOREIGN KEY (prodID) REFERENCES Product(prodID)
);


