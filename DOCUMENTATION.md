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

3. **Staff Signup:** `/api/organization/staff/signup`   ( **Staff only** )
    - **Method:** POST
    - Description: An  `OTP` code would be sent to user email, the token sent would be used within the `otp_token` field
    - **Request Body:**

        ```json
        {
          "email": "user@example.com",
          "password": "password123",
          "otp_token": "", // 6-digit token sent to inbox
          "first_name": "",
          "last_name": "",
          "phone_number": ""
        }
        ```

4. Create Organization: `/api/organization/create`
    - **Method:** POST
    - **Headers: `Authorization: Bearer <access_token>`**
    - **Request Body:**

        ```json
        {
          "organization_name": "",
          "lunch_price": "" // default to "#1000" if not set
        }
        ```

5. **Create Organization Invite (Admin Only)**
    - Endpoint: `/api/organization/invite`
    - Method: POST
    - **Headers: `Authorization: Bearer <access_token>`**
    - Description: Allows an admin user to send an invitation to join the organization.
    - Request Body:

        ```json
        {
          "email": "jane@example.com"
        }
        ```

    - Response Body:

        ```json
        {
          "message": "success",
          "statusCode": 200,
          "data": null
        }
        ```

## User Section

1. Endpoint: `/api/user/profile`
    - **Method:** GET
    - **Headers: `Authorization: Bearer <access_token>`**
    - **Request Response:**

        ```json
        // you could choose to return just the username or email depending on 
        // your usecase
        {
          "message": "User data fetched successfully",
          "statusCode": 200,
          "data": {
            "name": "John Doe",
            "email": "john@mail.com",
            "profile_picture": "user-profile-picture-url",
            "phonenumber": "1234567890",
            "bank_number": "1234-5678-9012-3456",
            "bank_code": "123456",
            "bank_name": "Bank Name",
            "isAdmin": true | false
          }
        }
        ```

2. Add Bank Account: `/api/user/bank`
    - **Method:** POST
    - **Headers: `Authorization: Bearer <access_token>`**
    - **Request Body:**

        ```json
        // you could choose to return just the username or email depending on 
        // your usecase
        {
          "bank_number": "1234-5678-9012-3456",
          "bank_code": "123456",
          "bank_name": "Bank Name"
        }
        ```

    - Response :

        ```json
        {
          "message": "successfully created bank account",
          "statusCode": 200
        }
        ```


1. Get all Users: `/api/users`
    - **Method:** GET
    - **Headers: `Authorization: Bearer <access_token>`**
    - **Request Body: None**
    - Response :

        ```json
        {
          "message": "successfully created bank account",
          "statusCode": 200,
          "data": [
              {
                "name": "John Doe",
                "email": "john@mail.com",
                "profile_picture": "user-profile-picture-url",
                "user_id": ""
              },
              {
                "name": "John Doe",
                "email": "john@mail.com",
                "profile_picture": "user-profile-picture-url",
                "user_id": ""
              }
            ]
        }
        ```

2. Search Users: `/api/search/<nameoremail>`
   - **Method:** GET
   - **Headers: `Authorization: Bearer <access_token>`**
   - **Request Body: None**
   - Response :

   ```json
   {
     "message": "User found",
     "statusCode": 200,
     "data":
     {
       "name": "John Doe",
       "email": "john@mail.com",
       "profile_picture": "user-profile-picture-url",
       "user_id": ""
     }
   }
   ```

# Lunch Section

1. Send a Lunch

   **Endpoint:** `/api/lunch/send`

    - **Method:** POST
    - **Description:** Create a new lunch request.
    - **Headers:** `Authorization: Bearer your-auth-token-here`
    - **Request Body:**

        ```json
        {
          "receivers": ["user_id"], // this could contain multiple users
          "quantity": 5,
          "note": "Special instructions for the lunch"
        }
        
        // prevent the user from sending lunch to him/herself.
        ```

      **Response:**

        ```json
        {
          "message": "Lunch request created successfully",
          "statusCode": 201,
          "data": {}
        }
        
        ```


2. Get a Lunch

   **Endpoint:** `/api/lunch/:id`

    - **Method:** GET
    - **Description:** Get a specific lunch
    - **Headers:** `Authorization: Bearer your-auth-token-here`
    - **Request Body: None**

      **Response:**

        ```json
        {
          "message": "Lunch request fetched successfully",
          "statusCode": 200,
          "data": {
            "receiverId": "",
            "senderId": "",
            "quantity": 5,
            "redeemed": false,
            "note": "Special instructions for the lunch",
            "created_at": "",
            "id": ""
          }
        }
        
        ```


3. Get all Lunches

   **Endpoint:** `/api/lunch/all`

    - **Method:** GET
    - **Description:** Get all lunch requests available for that user
    - **Headers:** `Authorization: Bearer your-auth-token-here`
    - **Request Body: None**

      **Response:**

        ```json
        {
          "message": "Lunch requests fetched successfully",
          "statusCode": 200,
          "data": [
            {
              "receiverId": "",
              "senderId": "",
              "quantity": 5,
              "redeemed": false,
              "note": "Special instructions for the lunch",
              "created_at": "",
              "id": ""
            },
            {
              "receiverId": "",
              "senderId": "",
              "quantity": 5,
              "redeemed": false,
              "note": "Special instructions for the lunch",
              "created_at": "",
              "id": ""
            }
          ]
        }
        
        ```


4. Redeem a specific user lunch

   **Endpoint:** `/api/lunch/redeem/:id`

    - **Method:** PUT
    - **Description:** Redeem a specific user's lunch
    - **Headers:** `Authorization: Bearer your-auth-token-here`
    - **Request Body: None**
    - **Response:**

        ```json
        {
          "message": "Lunch redeemed successfully",
          "statusCode": 200,
          "data": null
        }
        ```

# Withdrawal Request

1. **Endpoint:** `/api/withdrawal/request`
    - **Method:** POST
    - **Description:** Create a withdrawal request.
    - **Headers:** `Authorization: Bearer your-auth-token-here`
    - **Request Body:**

    ```json
    {
      "bank_name": "bank",
      "bank_number": "",
      "bank_code": "",
      "amount": 100
    }
    ```

   **Response:**

    ```json
    {
      "message": "Withdrawal request created successfully",
      "statusCode": 201,
      "data": {
        "id": "unique-withdrawal-id",
        "user_id": "user-id",
        "status": "success", // the status should be updated from pending to successful state assuming we integrated a payment provider.
        "amount": 100,
        "created_at": "2023-09-19T12:00:00Z"
      }
    }
    
    ```
