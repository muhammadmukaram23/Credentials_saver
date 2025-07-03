🔐 Password & Credential Manager API
This API allows you to securely save and manage passwords, emails, credentials, credit card info, and secure notes.

🚀 Setup Instructions
Clone the repository

bash
Copy
Edit
git clone https://github.com/your-repo/password-manager-api.git
cd password-manager-api
Create a .env file
In the root directory, create a .env file with the following:

env
Copy
Edit
SECRET_KEY=your_super_secret_key_here
🔒 Must be at least 8 characters long

Import Database

Open phpMyAdmin via XAMPP

Import database.sql to create necessary tables

Install Dependencies

bash
Copy
Edit
pip install -r req.txt
Run the Server

bash
Copy
Edit
python -m uvicorn main:app --reload --port 8000
Access Swagger API Docs

Visit: http://127.0.0.1:8000/docs

📂 API Endpoints
🧑‍💻 Auth
Method	Endpoint	Description
POST	/api/v1/auth/login	Login
POST	/api/v1/auth/logout	Logout

📚 Categories
Method	Endpoint	Description
GET	/api/v1/categories	Read all categories
POST	/api/v1/categories	Create category
GET	/api/v1/categories/{category_id}	Read one category
PUT	/api/v1/categories/{category_id}	Update category
DELETE	/api/v1/categories/{category_id}	Delete category

🔐 Credentials
Method	Endpoint	Description
GET	/api/v1/credentials	Read all credentials
POST	/api/v1/credentials	Create credential
GET	/api/v1/credentials/{credential_id}	Read credential by ID
PUT	/api/v1/credentials/{credential_id}	Update credential
DELETE	/api/v1/credentials/{credential_id}	Delete credential
GET	/api/v1/credentials/search/{query}	Search credentials by keyword
GET	/api/v1/categories/{category_id}/credentials	Read credentials by category

💳 Credit Cards
Method	Endpoint	Description
GET	/api/v1/credit-cards	Read all cards
POST	/api/v1/credit-cards	Create card
GET	/api/v1/credit-cards/{card_id}	Read card by ID
PUT	/api/v1/credit-cards/{card_id}	Update card
DELETE	/api/v1/credit-cards/{card_id}	Delete card

📝 Secure Notes
Method	Endpoint	Description
GET	/api/v1/secure-notes	Read all notes
POST	/api/v1/secure-notes	Create note
GET	/api/v1/secure-notes/{note_id}	Read note by ID
PUT	/api/v1/secure-notes/{note_id}	Update note
DELETE	/api/v1/secure-notes/{note_id}	Delete note

📎 Notes
Make sure your XAMPP server is running when testing the database.

Swagger Docs makes it easy to test endpoints without writing any code.

Always keep your .env file and SECRET_KEY private.

