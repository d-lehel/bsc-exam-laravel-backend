# ShareApp Backend REST API

The ShareApp Backend REST API provides the necessary functionality to support the [ShareApp mobile application](https://github.com/d-lehel/bsc-exam-android-client). Built with the Laravel framework and utilizing Laravel Passport for authentication, this API serves as the bridge between the frontend user interface and the database.

## Key Features

1. **Token-Based Authentication**: The API leverages Laravel Passport for secure token-based authentication. Users can register, log in, and obtain access tokens that are used to authenticate subsequent API requests, ensuring secure and authenticated communication between the frontend and backend.

2. **Food Management**: The API includes endpoints to manage shared food items, enabling users to create, update, and delete food listings. The integration with Laravel Passport ensures that only authenticated users can perform these actions, maintaining data integrity and security.

3. **Location-based Filtering**: The API supports filtering of shared food items based on the user's location. Users can easily discover and access food listings that are closest to them, enhancing the convenience and usability of the ShareApp platform.

4. **Request and Exchange**: The API provides endpoints for users to send requests for specific shared food items and facilitate the pickup or delivery process. The token-based authentication ensures that these requests are securely processed and authorized.

5. **Data Storage**: The API utilizes Laravel's robust database capabilities, ensuring secure storage of user profiles, food listings, and related metadata. The integration with Laravel Passport guarantees that user data remains confidential and accessible only to authorized users.

## Technologies Used

The ShareApp Backend REST API is developed using Laravel, a popular PHP framework known for its elegant syntax, scalability, and extensive feature set. Laravel Passport, a Laravel package for API authentication, is employed for token-based authentication and security.
