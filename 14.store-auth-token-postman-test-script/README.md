# 14. script to store auth token postman environment

## Go to your Postman collection or individual request.
- Under the Tests tab of your login request, paste the script.
- Ensure the API response includes the token in a format like { "token": "your_token_value" }. Adjust the script if the token is located deeper in the JSON structure.
Run the request. If the login is successful, the token will be stored in an environment variable named token.
- Run the Script