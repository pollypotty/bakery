# Registration Flowchart

Below is the flowchart for the registration functionality, including both frontend and backend processes:

```mermaid
graph TD
    subgraph Frontend
        A[User navigates to registration page]
        B{Choose registration method}
        C[Fill out form]
        D[Submit form only if vee-validate allows it]
        E[Initiate Google OAuth registration]
    end

    subgraph Backend
        F[Validate data in RegistrationRequest class]
        G{Is data valid?}
        H[RegistrationController register method gets the request]
        I[RegistrationService register method gets the form data]
        J[Save user to database]
        K[Log in user]
        L[Return to RegistrationController]
        M[Redirect to the user's profile page]
    end

    subgraph ErrorHandling
        P[Return to registration page with error message]
        Q[Handle backend exceptions]
        R[Log error]
    end

    A --> B
    B -->|Form| C --> D --> F --> G
    G -->|No| P
    P --> C
    G -->|Yes| H
    H --> I
    I --> J
    J --> K
    K --> L
    L --> M
    I -->|Exception| Q --> R --> P
    J -->|Exception| Q
    K -->|Exception| Q


