# Shree Hindutakht - Technical Documentation

## Table of Contents
1. [Overview](#overview)
2. [Technology Stack](#technology-stack)
3. [Architecture](#architecture)
4. [Database Schema](#database-schema)
5. [API Endpoints](#api-endpoints)
6. [Frontend Structure](#frontend-structure)
7. [Android App](#android-app)
8. [Authentication](#authentication)
9. [Key Features](#key-features)
10. [Deployment](#deployment)
11. [Configuration](#configuration)
12. [Security](#security)
13. [Performance](#performance)

## Overview

Shree Hindutakht is a social community platform built with Laravel PHP framework for the backend and Blade/Tailwind CSS for the frontend. The application also includes a native Android app that wraps the web application using WebView technology. The platform serves as a digital community hub for Hindu community members to connect, share posts, participate in events, make donations, and access member-specific features.

## Technology Stack

### Backend
- **Framework**: Laravel 9.x
- **Language**: PHP 8.x
- **Database**: SQLite (development), MySQL (production)
- **API**: RESTful JSON API
- **Authentication**: JWT (JSON Web Tokens)
- **Image Processing**: Intervention Image Library

### Frontend
- **Template Engine**: Blade
- **Styling**: Tailwind CSS
- **JavaScript**: Vanilla JavaScript with custom modules
- **Build Tool**: Vite
- **Progressive Web App**: Service Worker support

### Mobile
- **Platform**: Android (WebView wrapper)
- **Navigation**: Native Android navigation with web content

### Infrastructure
- **Web Server**: Apache
- **Development**: XAMPP environment
- **Deployment**: Hostinger shared hosting

## Architecture

The application follows a typical MVC (Model-View-Controller) architecture with additional layers for API services and mobile integration:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/           # REST API controllers
│   │   │   └── Admin/     # Admin-specific API controllers
│   │   └── Web/           # Web page controllers
│   └── Middleware/        # Authentication and request processing
├── Models/                # Database models
├── Helpers/               # Utility classes
└── Services/              # Business logic services

resources/
├── views/                 # Blade templates
├── js/                    # JavaScript modules
└── css/                   # Stylesheets

routes/
├── api.php                # API routes
├── web.php                # Web routes
└── channels.php           # Broadcasting channels

public/
├── index.php              # Entry point
├── manifest.json          # PWA manifest
└── sw.js                  # Service worker
```

## Database Schema

### Members Table
- `id` - Primary key
- `member_id` - Unique member identifier
- `name` - Member's full name
- `email` - Email address
- `phone` - Phone number
- `address` - Physical address
- `photo` - Profile photo path
- `status` - Membership status (active/inactive)
- `created_at`, `updated_at` - Timestamps

### Admins Table
- `id` - Primary key
- `username` - Admin username
- `email` - Email address
- `password` - Hashed password
- `name` - Admin's name
- `photo` - Profile photo path
- `created_at`, `updated_at` - Timestamps

### Posts Table
- `id` - Primary key
- `content` - Post content
- `media_urls` - JSON array of media file paths
- `member_id` - Foreign key to members (nullable)
- `admin_id` - Foreign key to admins (nullable)
- `created_at`, `updated_at` - Timestamps

### Events Table
- `id` - Primary key
- `title` - Event title
- `description` - Event description
- `event_date` - Date and time of event
- `location` - Event location
- `status` - Event status (upcoming/past)
- `created_at`, `updated_at` - Timestamps

### Notifications Table
- `id` - Primary key
- `title` - Notification title
- `message` - Notification message
- `type` - Notification type
- `data` - Additional JSON data
- `read_at` - When notification was read
- `member_id` - Foreign key to members
- `created_at`, `updated_at` - Timestamps

### Post Likes Table
- `id` - Primary key
- `post_id` - Foreign key to posts
- `member_id` - Foreign key to members
- `created_at`, `updated_at` - Timestamps

### Post Comments Table
- `id` - Primary key
- `post_id` - Foreign key to posts
- `member_id` - Foreign key to members
- `comment` - Comment text
- `created_at`, `updated_at` - Timestamps

### Event RSVPs Table
- `id` - Primary key
- `event_id` - Foreign key to events
- `member_id` - Foreign key to members
- `response` - RSVP response (interested/going)
- `created_at`, `updated_at` - Timestamps

## API Endpoints

### Authentication
- `POST /api/auth/login` - Member login
- `POST /api/auth/register` - Member registration
- `GET /api/auth/profile` - Get authenticated member profile
- `POST /api/auth/logout` - Logout
- `POST /api/auth/change-password` - Change password

### Posts
- `GET /api/posts` - Get paginated posts
- `POST /api/posts` - Create new post
- `POST /api/posts/{id}/like` - Like/unlike a post
- `POST /api/posts/{id}/comment` - Add comment to post
- `GET /api/posts/{id}/comments` - Get post comments

### Events
- `GET /api/events` - Get events
- `POST /api/events/{id}/rsvp` - RSVP to event

### Notifications
- `GET /api/notifications` - Get notifications
- `POST /api/notifications/{id}/read` - Mark notification as read

### Admin
- `POST /api/admin/auth/login` - Admin login
- `GET /api/admin/dashboard/stats` - Get dashboard statistics
- `GET /api/admin/members` - Get members list
- `GET /api/admin/members/{id}` - Get specific member
- `PUT /api/admin/members/{id}` - Update member
- `DELETE /api/admin/members/{id}` - Delete member
- `GET /api/admin/posts` - Get posts
- `POST /api/admin/posts` - Create post
- `PUT /api/admin/posts/{id}` - Update post
- `DELETE /api/admin/posts/{id}` - Delete post
- `GET /api/admin/events` - Get events
- `POST /api/admin/events` - Create event
- `PUT /api/admin/events/{id}` - Update event
- `DELETE /api/admin/events/{id}` - Delete event

## Frontend Structure

### Public Pages
- `/` - Landing page
- `/about` - About page
- `/team` - Team page
- `/mission-vision` - Mission and vision page
- `/privacy-policy` - Privacy policy page

### Authentication Pages
- `/login` - Member login
- `/register` - Member registration
- `/admin/login` - Admin login

### Member Pages
- `/home` - Member dashboard
- `/member/dashboard` - Member dashboard
- `/member/profile` - Member profile
- `/member/edit-profile` - Edit profile
- `/member/id-card` - ID card display
- `/member/posts` - Member posts
- `/member/events` - Member events
- `/member/donations` - Member donations
- `/member/notifications` - Member notifications

### Admin Pages
- `/admin/dashboard` - Admin dashboard
- `/admin/members` - Manage members
- `/admin/posts` - Manage posts
- `/admin/events` - Manage events
- `/admin/donations` - Manage donations

## Android App

The Android app is a WebView wrapper that provides a native experience for the web application.

### Key Features
- Native splash screen
- Authentication token management
- Back button handling
- Native navigation controls
- Offline capabilities through service worker

### Components
- `SplashActivity` - Initial authentication check and splash screen
- `MainActivity` - Main WebView container
- `TestAuthActivity` - Authentication testing

### Authentication Flow
1. Splash screen checks for existing authentication tokens
2. If authenticated, loads home page directly
3. If not authenticated, loads landing page
4. Tokens are managed through Android CookieManager

## Authentication

### Member Authentication
- JWT-based authentication
- Tokens stored in localStorage
- Session management through middleware
- Password hashing with bcrypt

### Admin Authentication
- Separate authentication system from members
- Session-based authentication
- Admin-specific middleware protection

### Token Management
- Access tokens with expiration
- Automatic token refresh
- Secure storage in localStorage
- Token validation on each API request

## Key Features

### Post Management
- Create posts with text and media
- Image compression and WebP conversion
- Like and comment functionality
- Infinite scroll loading

### Event Management
- Create and manage events
- RSVP functionality
- Event notifications

### Member Management
- Profile management
- ID card generation
- Membership status tracking

### Notification System
- Real-time notifications
- Read/unread status
- Notification types for different activities

### Donation System
- Donation tracking
- Payment integration (external)

### Admin Dashboard
- Statistics overview
- Member management
- Content moderation
- Event management

## Deployment

### Requirements
- PHP 8.x
- Apache with mod_rewrite
- SQLite or MySQL database
- Node.js for asset compilation

### Deployment Steps
1. Clone repository to server
2. Run `composer install` to install PHP dependencies
3. Run `npm install` to install JavaScript dependencies
4. Configure environment variables in `.env` file
5. Run database migrations: `php artisan migrate`
6. Compile assets: `npm run build`
7. Set proper file permissions
8. Configure Apache virtual host

### Hostinger Deployment
- Shared hosting environment
- Specific path configurations required
- Storage path adjustments needed
- Database configuration for MySQL

## Configuration

### Environment Variables
Key configuration variables in `.env`:
- `APP_NAME` - Application name
- `APP_ENV` - Environment (local/production)
- `APP_KEY` - Application encryption key
- `APP_DEBUG` - Debug mode
- `DB_CONNECTION` - Database connection
- `DB_DATABASE` - Database name
- `DB_USERNAME` - Database username
- `DB_PASSWORD` - Database password

### Filesystem Configuration
- Local storage for development
- Public storage for uploaded files
- Image compression settings
- Media path configurations

### JWT Configuration
- Token expiration times
- Secret keys
- Algorithm settings

## Security

### Authentication Security
- Password hashing with bcrypt
- JWT token encryption
- Session protection
- CSRF protection

### Data Security
- SQL injection prevention through Eloquent ORM
- XSS protection through Blade escaping
- Input validation
- File upload security

### API Security
- Rate limiting
- CORS configuration
- Token-based authentication
- Request validation

### Mobile Security
- Secure token storage
- WebView security settings
- SSL enforcement

## Performance

### Optimization Techniques
- Image compression and WebP conversion
- Lazy loading for images
- Infinite scroll for posts
- Caching strategies
- Service worker for offline support

### Caching
- Browser caching for static assets
- Service worker caching for API responses
- localStorage caching for user data
- Cache invalidation strategies

### Asset Optimization
- Vite build system for JavaScript/CSS
- Minification of assets
- Code splitting
- Lazy loading modules

### Database Optimization
- Proper indexing
- Eager loading to prevent N+1 queries
- Query optimization
- Pagination for large datasets