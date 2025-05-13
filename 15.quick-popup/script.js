pm.test("Login successful", function () {
    // Check if the response has a status code of 200
    pm.response.to.have.status(200);

    // Parse the JSON response
    const response = pm.response.json();

    // Extract the token from the response (adjust the path based on your API's response structure)
    const token = response.token || response.data?.token;

    // Check if the token exists
    pm.expect(token).to.be.a("string");

    // Save the token to the environment variable
    pm.environment.set("token", token);

    console.log("Token stored in environment variable: ", token);
});