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
        "isAdmin": false
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
          "first_name": "John",
          "last_name": "Doe",
          "phonenumber": "1234567890"
        }
        ```
   - **Response :**

   ```json
       {
         "message": "User created successfully",
         "statusCode": 201
       }
   ```

3. Create Organization: `/api/organization/create`
    - **Method:** POST
    - **Headers: `Authorization: Bearer <access_token>`**
    - **Request Body:**
      - lunch_price (decimal nullable) - Defaults to 1000.
      - organization_name (string required) - Name of the organisation
    ```json
      {
        "organization_name": "HNG",
        "lunch_price": 1000
      }
    ```
   - **Response :**

   ```json
       {
         "message": "Organisation created successfully",
         "statusCode": 201
       }
   ```
4. **Create Organization Invite (Admin Only)**
    - Endpoint: `/api/organization/invite`
    - Method: POST
    - **Headers: `Authorization: Bearer <access_token>`**
    - Description: Allows an admin user to send an invitation to join the organization.
    - **Request Body:**

        ```json
        {
          "email": "jane@example.com"
        }
        ```

    - **Response Body:**

        ```json
        {
          "message": "success",
          "statusCode": 200,
          "data": null
        }
        ```
5. **Staff Signup:** `/api/organization/staff/signup`   ( **Staff only** )
    - **Method:** POST
    - Description: A 6 digit `OTP` code would be sent to user email during invitation, the token sent would be used within the `otp_token` field
    - **Request Body:**

        ```json
        {
          "email": "user@example.com",
          "password": "password123",
          "otp_token": 134256,
          "first_name": "John",
          "last_name": "Doe",
          "phonenumber": "1234567890"
        }
        ```
      **Response:**

        ```json
        {
          "message": "Staff created successfully",
          "statusCode": 201,
          "data": {
            "email": "user@example.com",
            "password": "password123",
            "otp_token": 134256,
            "first_name": "John",
            "last_name": "Doe",
            "phonenumber": "1234567890"
          }
        }
        
        ```
      
## User Section

1. Endpoint: `/api/user/profile`
    - **Method:** GET
    - **Headers: `Authorization: Bearer <access_token>`**
    - **Response:**

        ```json
        {
          "message": "User data fetched successfully",
          "statusCode": 200,
          "data": {
            "first_name": "John",
            "last_name": "Doe",
            "phonenumber": "1234567890",
            "email": "john@mail.com",
            "profile_picture": "user-profile-picture-url",
            "bank_number": "1234-5678-9012-3456",
            "bank_code": "123456",
            "bank_name": "Bank Name",
            "isAdmin": true
          }
        }
        ```

2. Add Bank Account: `/api/user/bank`
    - **Method:** POST
    - **Headers: `Authorization: Bearer <access_token>`**
    - **Request Body:**

        ```json
        {
          "bank_number": "1234-5678-9012-3456",
          "bank_code": "123456",
          "bank_name": "Bank Name"
        }
        ```

    - **Response :**

        ```json
        {
          "message": "successfully created bank account",
          "statusCode": 200
        }
        ```


3. Get all Users: `/api/users`
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
                "first_name": "John",
                "last_name": "Doe",
                "email": "john@mail.com",
                "profile_picture": "user-profile-picture-url",
                "user_id": ""
              },
              {
                "first_name": "John",
                "last_name": "Doe",
                "email": "john@mail.com",
                "profile_picture": "user-profile-picture-url",
                "user_id": ""
              }
            ]
        }
        ```

4. Search Users: `/api/search/:param`
   - **Method:** GET
   - **Headers: `Authorization: Bearer <access_token>`**
   - **Parameters**:
     - `name|email` (path parameter, string) - The Name or Email of the person to search.
   - **Request Body: None**
   - Response :

   ```json
   {
     "message": "User found",
     "statusCode": 200,
     "data":
     {
       "first_name": "John",
       "last_name": "Doe",
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
    - **Headers:** `Authorization: Bearer <access_token>`
    - **Request Body:**

        ```json
        {
          "receivers": ["user_id"],
          "quantity": 5,
          "note": "Special instructions for the lunch"
        }
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
    - **Headers:** `Authorization: Bearer <access_token>`
    - **Parameters**:
      - `id` (path parameter, integer) - The ID of the lunch.
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
       ```json
        {
          "message": "Lunch not found",
          "statusCode": 404
        }
        
        ```


3. Get all Lunches

   **Endpoint:** `/api/lunch/all`

    - **Method:** GET
    - **Description:** Get all lunch requests available for that user
    - **Headers:** `Authorization: Bearer <access_token>`
    - **Request Body: None**

      **Response:**

        ```json
        {
          "message": "Lunch requests fetched successfully",
          "statusCode": 200,
          "data": [
            {
              "receiverId": 2,
              "senderId": 1,
              "quantity": 5,
              "redeemed": false,
              "note": "Special instructions for the lunch",
              "created_at": "",
              "id": 1
            },
            {
              "receiverId": 1,
              "senderId": 2,
              "quantity": 5,
              "redeemed": false,
              "note": "Special instructions for the lunch",
              "created_at": "",
              "id": 2
            }
          ]
        }
        
        ```

# Withdrawal Request

1. **Endpoint:** `/api/withdrawal/request`
    - **Method:** POST
    - **Description:** Create a withdrawal request.
    - **Headers:** `Authorization: Bearer <access_token>`
    - **Request Body:**

    ```json
    {
      "bank_name": "bank",
      "bank_number": "232113445",
      "bank_code": "1234",
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
        "status": "success",
        "amount": 100,
        "created_at": "2023-09-19T12:00:00Z"
      }
    }
    ```
