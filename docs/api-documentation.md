# Shree Hindutakht API Documentation

## Overview

This document provides detailed information about the RESTful API endpoints available in the Shree Hindutakht platform. The API follows standard REST conventions and returns JSON responses.

## Authentication

### Member Authentication
Most member endpoints require authentication via JWT tokens.

**Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```

### Admin Authentication
Admin endpoints use session-based authentication and require login through the admin login endpoint.

## Member API Endpoints

### Authentication

#### Login
```
POST /api/auth/login
```

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "jwt_token_here",
    "member": {
      "id": 1,
      "member_id": "HT2025001",
      "name": "John Doe",
      "email": "user@example.com",
      // ... other member fields
    }
  }
}
```

#### Register
```
POST /api/auth/register
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "user@example.com",
  "phone": "1234567890",
  "password": "password",
  "password_confirmation": "password",
  "address": "123 Main St"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "token": "jwt_token_here",
    "member": {
      // ... member data
    }
  }
}
```

#### Get Profile
```
GET /api/auth/profile
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "member_id": "HT2025001",
    "name": "John Doe",
    "email": "user@example.com",
    "phone": "1234567890",
    "address": "123 Main St",
    "photo": "path/to/photo.jpg",
    "full_photo_url": "https://example.com/storage/path/to/photo.jpg",
    "status": "active",
    "created_at": "2025-09-20T10:30:00.000000Z"
  }
}
```

#### Logout
```
POST /api/auth/logout
```

**Response:**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

#### Change Password
```
POST /api/auth/change-password
```

**Request Body:**
```json
{
  "current_password": "old_password",
  "new_password": "new_password",
  "new_password_confirmation": "new_password"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Password changed successfully"
}
```

### Posts

#### Get Posts
```
GET /api/posts?page=1&per_page=10
```

**Response:**
```json
{
  "success": true,
  "data": {
    "posts": [
      {
        "id": 1,
        "content": "This is a post",
        "media_urls": ["path/to/image1.webp", "path/to/image2.webp"],
        "member": {
          "id": 1,
          "name": "John Doe",
          "photo": "path/to/profile.jpg"
        },
        "admin": null,
        "created_by_admin": false,
        "time_ago": "2 hours ago",
        "likes_count": 5,
        "comments_count": 3,
        "shares_count": 2,
        "is_liked": false,
        "created_at": "2025-09-20T10:30:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 10,
      "total": 45
    }
  }
}
```

#### Create Post
```
POST /api/posts
```

**Request Body (multipart/form-data):**
```
content: "This is a post"
media[]: (file upload)
```

**Response:**
```json
{
  "success": true,
  "message": "Post created successfully",
  "data": {
    "id": 1,
    "content": "This is a post",
    "media_urls": ["path/to/image.webp"],
    "member_id": 1,
    "created_at": "2025-09-20T10:30:00.000000Z"
  }
}
```

#### Like/Unlike Post
```
POST /api/posts/{id}/like
```

**Response:**
```json
{
  "success": true,
  "data": {
    "is_liked": true,
    "likes_count": 6
  }
}
```

#### Add Comment
```
POST /api/posts/{id}/comment
```

**Request Body:**
```json
{
  "comment": "This is a comment"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Comment added successfully",
  "data": {
    "id": 1,
    "post_id": 1,
    "member_id": 1,
    "comment": "This is a comment",
    "created_at": "2025-09-20T10:30:00.000000Z"
  }
}
```

#### Get Comments
```
GET /api/posts/{id}/comments?page=1
```

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "comment": "This is a comment",
        "member": {
          "id": 1,
          "name": "John Doe",
          "photo": "path/to/profile.jpg"
        },
        "time_ago": "5 minutes ago",
        "created_at": "2025-09-20T10:30:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 15,
      "total": 3
    }
  }
}
```

### Events

#### Get Events
```
GET /api/events
```

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "title": "Community Event",
        "description": "Description of the event",
        "event_date": "2025-10-15T18:00:00.000000Z",
        "location": "Community Center",
        "status": "upcoming",
        "user_rsvp": "going",
        "interested_count": 15,
        "going_count": 8,
        "created_at": "2025-09-20T10:30:00.000000Z"
      }
    ]
  }
}
```

#### RSVP to Event
```
POST /api/events/{id}/rsvp
```

**Request Body:**
```json
{
  "response": "going" // or "interested"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "interested_count": 15,
    "going_count": 9
  }
}
```

### Notifications

#### Get Notifications
```
GET /api/notifications
```

**Response:**
```json
{
  "success": true,
  "data": {
    "notifications": [
      {
        "id": 1,
        "title": "New Event",
        "message": "A new event has been scheduled",
        "type": "event",
        "data": null,
        "read_at": null,
        "created_at": "2025-09-20T10:30:00.000000Z"
      }
    ]
  }
}
```

#### Mark Notification as Read
```
POST /api/notifications/{id}/read
```

**Response:**
```json
{
  "success": true,
  "message": "Notification marked as read"
}
```

## Admin API Endpoints

### Authentication

#### Admin Login
```
POST /api/admin/auth/login
```

**Request Body:**
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "admin": {
      "id": 1,
      "username": "admin",
      "email": "admin@example.com",
      "name": "Administrator",
      "photo": "path/to/photo.jpg"
    }
  }
}
```

### Dashboard

#### Get Dashboard Statistics
```
GET /api/admin/dashboard/stats
```

**Response:**
```json
{
  "success": true,
  "data": {
    "total_members": 150,
    "total_posts": 245,
    "total_events": 12,
    "active_events": 3,
    "recent_posts": [
      // ... recent posts
    ]
  }
}
```

### Members

#### Get Members
```
GET /api/admin/members?page=1&per_page=10&search=john
```

**Response:**
```json
{
  "success": true,
  "data": {
    "members": [
      {
        "id": 1,
        "member_id": "HT2025001",
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "1234567890",
        "status": "active",
        "created_at": "2025-09-20T10:30:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 15,
      "per_page": 10,
      "total": 145
    }
  }
}
```

#### Get Member
```
GET /api/admin/members/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "member_id": "HT2025001",
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "address": "123 Main St",
    "photo": "path/to/photo.jpg",
    "status": "active",
    "created_at": "2025-09-20T10:30:00.000000Z"
  }
}
```

#### Update Member
```
PUT /api/admin/members/{id}
```

**Request Body:**
```json
{
  "name": "John Doe Updated",
  "email": "john.updated@example.com",
  "phone": "0987654321",
  "address": "456 New St",
  "status": "active"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Member updated successfully",
  "data": {
    // ... updated member data
  }
}
```

#### Delete Member
```
DELETE /api/admin/members/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Member deleted successfully"
}
```

### Posts

#### Get Posts
```
GET /api/admin/posts?page=1&per_page=10
```

**Response:**
```json
{
  "success": true,
  "data": {
    "posts": [
      {
        "id": 1,
        "content": "This is a post",
        "media_urls": ["path/to/image.webp"],
        "member": {
          "id": 1,
          "name": "John Doe"
        },
        "admin": null,
        "created_at": "2025-09-20T10:30:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 10,
      "total": 45
    }
  }
}
```

#### Create Post
```
POST /api/admin/posts
```

**Request Body (multipart/form-data):**
```
content: "This is an admin post"
media[]: (file upload)
```

**Response:**
```json
{
  "success": true,
  "message": "Post created successfully",
  "data": {
    "id": 1,
    "content": "This is an admin post",
    "media_urls": ["path/to/image.webp"],
    "admin_id": 1,
    "created_at": "2025-09-20T10:30:00.000000Z"
  }
}
```

#### Update Post
```
PUT /api/admin/posts/{id}
```

**Request Body:**
```json
{
  "content": "Updated post content"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Post updated successfully",
  "data": {
    // ... updated post data
  }
}
```

#### Delete Post
```
DELETE /api/admin/posts/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Post deleted successfully"
}
```

### Events

#### Get Events
```
GET /api/admin/events?page=1&per_page=10
```

**Response:**
```json
{
  "success": true,
  "data": {
    "events": [
      {
        "id": 1,
        "title": "Community Event",
        "description": "Description of the event",
        "event_date": "2025-10-15T18:00:00.000000Z",
        "location": "Community Center",
        "status": "upcoming",
        "created_at": "2025-09-20T10:30:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 2,
      "per_page": 10,
      "total": 12
    }
  }
}
```

#### Create Event
```
POST /api/admin/events
```

**Request Body:**
```json
{
  "title": "New Event",
  "description": "Description of the new event",
  "event_date": "2025-11-20T18:00:00.000000Z",
  "location": "New Location",
  "status": "upcoming"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Event created successfully",
  "data": {
    "id": 2,
    "title": "New Event",
    "description": "Description of the new event",
    "event_date": "2025-11-20T18:00:00.000000Z",
    "location": "New Location",
    "status": "upcoming",
    "created_at": "2025-09-20T10:30:00.000000Z"
  }
}
```

#### Update Event
```
PUT /api/admin/events/{id}
```

**Request Body:**
```json
{
  "title": "Updated Event",
  "description": "Updated description",
  "event_date": "2025-11-25T18:00:00.000000Z",
  "location": "Updated Location",
  "status": "upcoming"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Event updated successfully",
  "data": {
    // ... updated event data
  }
}
```

#### Delete Event
```
DELETE /api/admin/events/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Event deleted successfully"
}
```

## Error Responses

All API endpoints return consistent error responses in the following format:

```json
{
  "success": false,
  "message": "Error description"
}
```

For validation errors:
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "Error message for the field"
    ]
  }
}
```

## Rate Limiting

API endpoints are rate-limited to prevent abuse:
- 60 requests per minute for most endpoints
- 5 login attempts per minute
- 10 file uploads per minute

## Webhooks

Currently, the platform does not implement webhooks. All updates are fetched through API calls.

## Changelog

### v1.0.0 (2025-09-20)
- Initial release
- Member authentication and registration
- Post creation and interaction
- Event management
- Admin dashboard and management
- Android app integration