# K chat Application - v0.1
A basic chat application for sending and receiving messages in real-time.

### Features 
- Real-time message sending and receiving.

- User-to-user chat (1-on-1 chat).

- Message notifications (with sound).

- Responsive UI with modern design.

- User authentication (login and registration).

### Technologies Used

- Frontend: HTML, CSS (Tailwind CSS), JavaScript (with Notyf for notifications).

- Backend: Laravel (PHP).

- Database: MySQL .

- Real-time functionality: Pusher .

- Notifications: Notyf, with custom icons and sounds.

### Installation 

To get started with the project:

+ Clone the repository:

``` git clone https://github.com/karamk2k/Kchat.git ```

+ Navigate to the project directory:

``` cd Kchat ```
+ Install dependencies: 

``` composer install```  ```npm install ```

+ Set up your .env file: 

 - Copy the .env.example file to .env: 
 ``` cp .env.example .env ```

+ Generate the application key:
``` php artisan key:generate ```

+ Run the migrations: 
 ``` php artisan migrate ```

+ Start the development server:
    ``` composer run dev ```

+ For real-time functionality, make sure to set up and configure Pusher or Laravel Echo.

### Usage

+ Log in to the application using your credentials.

+ Start a chat by selecting a user.

+ Send and receive messages in real time.

+ Notifications will alert you when you receive a new message.

### TODO
- Add Group Chat functionality with Admin Privileges.

- Implement Message History for users.

- Add User Profiles with customizable avatars.

- ✅ Support for Dark Mode.

- Improve UI/UX Design for better accessibility.

- Support for File Attachments (images, files, etc.).

- Add End-to-End Encryption for message security.

- ✅ Implement Typing Indicators in real-time.

- ✅ Add User Blocking functionality.

- Expand Notification System (e.g., message read receipts).






