CREATE DATABASE librarydb;
USE librarydb;


CREATE TABLE Member (
  MemberID INT NOT NULL Auto_INCREMENT,
  UserPassword VARCHAR(50) NOT NULL,
  Username VARCHAR(255) NOT NULL,
  UserEmail VARCHAR(255) NOT NULL,
  ContactNo VARCHAR(20) NOT NULL,
  date_of_birth DATE NOT NULL,
  date_joined DATE NOT NULL,
  active BOOLEAN NOT NULL,
  Role VARCHAR(50), /*check first*/
  PRIMARY KEY (MemberID)
);

CREATE TABLE Book(
  BookID INT NOT NULL Auto_INCREMENT,
  PublisherID INT NOT NULL,
  LibrarianID INT NOT NULL,
  ISBN VARCHAR(255),
  Title VARCHAR(255),
  Description VARCHAR(255),
  BookImage VARCHAR(255),
  PublishedYear VARCHAR(50),
  ShelfNo VARCHAR(50),
  PRIMARY KEY (BookID)
);

CREATE TABLE BookCopy(  /*check y need junction table*/
  BookCopyID INT NOT NULL Auto_INCREMENT,
  BookID INT,
  Status VARCHAR(50),
  CreatedAt DATE,
  PRIMARY KEY (BookCopyID)
);

CREATE TABLE Publisher(
  PublisherID INT NOT NULL Auto_INCREMENT,
  PublisherName VARCHAR(255),
  PublisherAddress VARCHAR(255),
  ContactNo VARCHAR(20),
  PublisherEmail VARCHAR(50)
);

CREATE TABLE Author(
  AuthorID INT NOT NULL Auto_INCREMENT,
  AuthorName VARCHAR(50),
  PRIMARY KEY (AuthorID)
);

CREATE TABLE BookAuthor( /*check y need junction table*/
  BookAuthorID INT NOT NULL Auto_INCREMENT,
  BookID INT NOT NULL,
  AuthorID INT NOT NULL,
  PRIMARY KEY (BookAuthorID)
);

CREATE TABLE Genre(
  GenreID INT NOT NULL Auto_INCREMENT,
  GenreName VARCHAR(50),
  PRIMARY KEY (GenreID)
);

CREATE TABLE BookGenre( /*check y need junction table*/
  BookGenreID INT NOT NULL Auto_INCREMENT,
  BookID INT NOT NULL,
  GenreID INT NOT NULL,
  PRIMARY KEY (BookGenreID)
);

CREATE TABLE Reservation(
  ReservationID INT NOT NULL Auto_INCREMENT,
  BookCopyID INT NOT NULL,
  MemberID INT NOT NULL,
  ReservedDate DATE,
  PickupDate DATE,
  ReturnDate DATE,
  Status VARCHAR(50),
  PRIMARY Key (ReservationID)
);

CREATE TABLE Cart (
  CartID INT NOT NULL Auto_INCREMENT,
  MemberID INT NOT NULL,
  PRIMARY KEY (CartID)
);

CREATE TABLE CartItems(
  CartItemID INT NOT NULL Auto_INCREMENT,
  CartID INT NOT NULL,
  BookCopyID INT NOT NULL,
  ReservationID INT NOT NULL,
  Status VARCHAR(50),
  PRIMARY KEY (CartItemID)
);

CREATE TABLE Reviews( 
  ReviewID INT NOT NULL Auto_INCREMENT,
  BookID INT NOT NULL,
  MemberID INT NOT NULL,
  Description VARCHAR(255),
  IdeaAnonymous TINYINT(1),
  Is_Hidden TINYINT(1),
  DateReview DATETIME,
  PRIMARY KEY (ReviewID)
);