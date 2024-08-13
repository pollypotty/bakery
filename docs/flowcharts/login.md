# Login Flowchart

Below is the flowchart for the login functionality, including both frontend and backend processes:

```mermaid
graph TD
    subgraph Frontend
        A[Navigate to login page]
        B{Choose login method}
        C[Fill out form]
        D[Submit if Vee-Validate allows it]
    end

    subgraph Backend
        E[Validate data in LoginRequest class]
        F{Is data valid?}
        G[AuthController login function gets the request]
        H[AuthService login function checks credentials]
        I{Is the user present in users table?}
        J[Authenticate user and return success]
        K[Return failure to the controller]
        L[Redirect to user's profile page]
    end

    subgraph ErrorHandling
        M[Redirect to login page with error message]
        N[Handle backend exceptions]
        O[Log error]
    end

    A --> B
    B -->|Form| C --> D --> E --> F
    F -->|No| M
    F -->|Yes| G --> H --> I
    I -->|Yes| J --> L
    I -->|No| K --> M
    M -->|Display error message| C
    H -->|Exception| N --> O --> M
    K -->|Exception| N
    J -->|Exception| N
   
   
   
   
   
