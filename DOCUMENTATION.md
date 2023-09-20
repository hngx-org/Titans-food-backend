# Free Lunch Endpoint

## Authentication

1. **Login:** `/api/auth/login`  ( **organization / user** )
    - **Method:** POST
    - **Request Body:**

    ```json
    {
      "email": "user@example.com",
      "password": "password123"
    }
    ```

   **Response:**

    ```json
    {
      "message": "User authenticated successfully",
      "statusCode": 200,
      "data": {
        "access_token": "your-auth-token-here",
        "email": "email@mail.com",
        "id": "random_id",
        "isAdmin": true | false
      }
    }
    ```

2. **User Signup:** `/api/auth/user/signup`   ( **organization only** )
    - **Method:** POST
    - **Request Body:**

        ```json
        {
          "email": "user@example.com",
          "password": "password123",
          "first_name": "",
          "last_name": "",
          "phone_number": ""
        }
        ```

3. Create Organization: `/api/organization/create`
    - **Method:** POST
    - **Headers: `Authorization: Bearer <access_token>`**
    - **Request Body:**

        ```json
        {
          "organization_name": "",
          "lunch_price": "" // default to "#1000" if not set
        }
        ```
