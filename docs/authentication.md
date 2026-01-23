# Hindu Takht App - Authentication System

## Overview

The application implements a dual authentication system:
1. **Member Authentication** - For community members accessing the mobile app
2. **Admin Authentication** - For administrators managing content via web interface

Both systems use JWT (JSON Web Tokens) for stateless authentication.

## Member Authentication Flow

### Registration
1. User submits registration form with name, email, password
2. Server validates input and checks for duplicate email
3. Password is hashed using bcrypt
4. Member ID is auto-generated (HT + 6 digits)
5. Member record is created in database
6. Welcome notification is sent
7. JWT token is generated and returned to client

### Login
1. User submits email and password
2. Server validates credentials
3. If valid, JWT token is generated with member claims
4. Token is returned to client for use in subsequent requests
5. Last login timestamp is updated

### Token Structure
```json
{
  "iss": "hindutakht-api",
  "iat": 1623456789,
  "exp": 1623460389,
  "nbf": 1623456789,
  "jti": "abc123",
  "sub": "member_id",
  "type": "member",
  "name": "Member Name"
}
```

### Token Refresh
1. Client sends refresh request with current token
2. Server validates token and checks if not expired
3. New token with extended expiration is generated
4. New token is returned to client

### Logout
1. Client sends logout request
2. Server invalidates token (add to blacklist/revocation list)
3. Client removes token from local storage

## Admin Authentication Flow

### Login
1. Admin submits username/email and password
2. Server validates credentials against admin table
3. If valid, JWT token is generated with admin claims
4. Token is returned to client
5. Last login timestamp is updated

### Token Structure
```json
{
  "iss": "hindutakht-api",
  "iat": 1623456789,
  "exp": 1623460389,
  "nbf": 1623456789,
  "jti": "def456",
  "sub": "admin_id",
  "type": "admin",
  "name": "Admin Name",
  "role": "super_admin"
}
```

## Authorization

### Member Permissions
- View public content (posts, events)
- Create and edit own posts
- Like and comment on posts
- RSVP to events
- View and edit own profile
- Change own password

### Admin Permissions
- Create, read, update, delete posts
- Create, read, update, delete events
- Manage members (view, edit, delete)
- Manage donations settings
- View analytics and statistics

### Role-Based Access Control (RBAC)
Admins have different roles with varying permissions:
- **Super Admin** - Full access to all features
- **Content Manager** - Manage posts and events
- **Community Manager** - Manage members and events
- **Finance Manager** - Manage donations

## Security Measures

### Password Security
- Passwords are hashed using bcrypt with cost factor 12
- Minimum password length of 6 characters
- Password confirmation required during registration and changes

### Token Security
- JWT tokens are signed using HS256 algorithm
- Tokens have short expiration (1 hour for access, 7 days for refresh)
- Tokens are validated on each authenticated request
- Revocation mechanism for logout

### Rate Limiting
- Login attempts are rate-limited (5 attempts per minute)
- Registration attempts are rate-limited
- API requests are rate-limited per user

### CORS Protection
- CORS policies restrict API access to authorized origins
- Headers are properly set for security

## API Authentication Middleware

### Member Authentication Middleware
- Validates JWT token
- Checks token type is "member"
- Verifies member exists and is active
- Attaches member object to request

### Admin Authentication Middleware
- Validates JWT token
- Checks token type is "admin"
- Verifies admin exists and is active
- Attaches admin object to request

## Token Storage

### Mobile App (React Native)
- Tokens are stored in AsyncStorage
- Tokens are sent in Authorization header as Bearer token
- Tokens are refreshed automatically when close to expiration

### Web Admin Panel
- Tokens are stored in HttpOnly cookies for security
- Tokens are sent automatically with each request
- Tokens are refreshed using refresh endpoint