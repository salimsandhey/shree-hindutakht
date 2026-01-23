# Hindu Takht App - React Native Migration Plan

## Current App Analysis

The current Hindu Takht app is a web application built with Laravel that uses a WebView wrapper for Android. The app has the following key features:

1. **Member Management** - Registration, login, profile management
2. **Social Feed** - Posts with text, images, and videos; likes and comments
3. **Events** - Event listing, details, and RSVP functionality
4. **Donations** - Donation information and payment options
5. **Notifications** - In-app notifications system

## Proposed Architecture

We will rebuild the app using:
- **Frontend**: React Native (cross-platform mobile app)
- **Backend**: Laravel API (unchanged, will serve as REST API)
- **Database**: MySQL (unchanged)
- **Authentication**: JWT tokens

### App Structure

```
hindutakht-react-native/
├── src/
│   ├── components/          # Reusable UI components
│   ├── screens/             # App screens (Home, Events, Donation, Profile)
│   ├── navigation/          # React Navigation setup
│   ├── services/            # API services
│   ├── store/               # State management (Redux or Context API)
│   ├── utils/               # Utility functions
│   ├── assets/              # Images, fonts
│   └── constants/           # App constants
├── android/                 # Android specific code
├── ios/                     # iOS specific code
└── App.js                   # Main app component
```

## Database Schema (Unchanged from Current)

### Members Table
```
members
- id (Primary Key)
- member_id (Unique ID like HT123456)
- name
- email
- password (hashed)
- phone
- address
- photo (profile picture path)
- date_of_birth
- gender
- status (active/inactive)
- joined_at (registration date)
- created_at
- updated_at
```

### Admins Table
```
admins
- id (Primary Key)
- name
- username
- email
- password (hashed)
- phone
- photo
- role (super_admin, content_manager, etc.)
- status (active/inactive)
- last_login_at
- created_at
- updated_at
```

### Posts Table
```
posts
- id (Primary Key)
- member_id (Foreign Key to members, nullable)
- admin_id (Foreign Key to admins, nullable)
- content (text content)
- media (JSON array of image/video paths)
- type (text, image, video, mixed)
- is_pinned (boolean - important posts at top)
- is_featured (boolean)
- status (active, draft, archived)
- likes_count
- comments_count
- shares_count
- published_at
- created_by_admin (boolean)
- created_at
- updated_at
```

### Post Likes Table
```
post_likes
- id (Primary Key)
- post_id (Foreign Key to posts)
- member_id (Foreign Key to members)
- created_at
- updated_at
```

### Post Comments Table
```
post_comments
- id (Primary Key)
- post_id (Foreign Key to posts)
- member_id (Foreign Key to members)
- comment (text)
- parent_id (for reply comments, nullable)
- is_approved (boolean)
- created_at
- updated_at
```

### Events Table
```
events
- id (Primary Key)
- title
- description
- location
- featured_image (image path)
- event_date
- registration_deadline
- max_participants
- status (upcoming, ongoing, completed, cancelled)
- is_featured (boolean)
- interested_count
- going_count
- additional_info (JSON)
- created_at
- updated_at
```

### Event RSVPs Table
```
event_rsvps
- id (Primary Key)
- event_id (Foreign Key to events)
- member_id (Foreign Key to members)
- response (interested/going)
- notes
- created_at
- updated_at
```

### Notifications Table
```
notifications
- id (Primary Key)
- member_id (Foreign Key to members)
- type (post_like, comment, event, etc.)
- title
- message
- data (JSON additional info)
- is_read (boolean)
- read_at
- created_at
- updated_at
```

### App Settings Table
```
app_settings
- id (Primary Key)
- key (setting identifier)
- value (setting value)
- type (string, integer, boolean, json)
- description
- created_at
- updated_at
```

### Donation Settings Table
```
donation_settings
- id (Primary Key)
- bank_details (JSON with bank name, account, IFSC)
- upi_id
- qr_code (QR code image path)
- additional_info (JSON)
- is_active (boolean)
- created_at
- updated_at
```

## API Endpoints (Unchanged from Current)

### Authentication
- `POST /api/auth/register` - Register new member
- `POST /api/auth/login` - Member login
- `POST /api/auth/logout` - Member logout
- `GET /api/auth/profile` - Get member profile
- `PUT /api/auth/profile` - Update member profile
- `POST /api/auth/update-profile` - Update profile with file uploads
- `POST /api/auth/remove-photo` - Remove profile photo
- `POST /api/auth/change-password` - Change password

### Posts (Feed)
- `GET /api/posts` - Get posts feed
- `GET /api/posts/{post}` - Get specific post
- `POST /api/posts/{post}/like` - Toggle like on post
- `POST /api/posts/{post}/comment` - Add comment to post
- `GET /api/posts/{post}/comments` - Get post comments

### Events
- `GET /api/events` - Get upcoming events
- `GET /api/events/{event}` - Get specific event
- `POST /api/events/{event}/rsvp` - RSVP to event

### Donations
- `GET /api/donation-info` - Get donation information

### Notifications
- `GET /api/notifications` - Get member notifications
- `POST /api/notifications/read-all` - Mark all notifications as read
- `GET /api/notifications/unread-count` - Get unread notification count

## React Native App Features

### 1. Authentication Flow
- Login screen with email/password
- Registration screen
- Password reset functionality
- JWT token storage and management

### 2. Home Screen (Feed)
- Pull-to-refresh feed
- Infinite scroll for posts
- Post cards with text, images, videos
- Like button with count
- Comment button with count
- Share functionality
- Pinned posts at the top

### 3. Events Screen
- List of upcoming events
- Event cards with date, location, featured image
- RSVP buttons (Interested/Going)
- Event details screen

### 4. Donation Screen
- Display bank details
- Show UPI ID
- Display QR code for UPI payments

### 5. Profile Screen
- User profile information
- Profile picture
- Edit profile functionality
- Change password
- View ID card
- Logout

### 6. Navigation
- Bottom tab navigator (Home, Events, Donation, Profile)
- Stack navigators for each section
- Smooth transitions between screens

## Implementation Steps

### Phase 1: Setup and Authentication
1. Create React Native project
2. Setup navigation structure
3. Implement authentication screens
4. Connect to Laravel API for login/registration
5. Implement JWT token handling

### Phase 2: Core Features
1. Implement home feed with posts
2. Add post interaction (likes, comments)
3. Implement events listing and RSVP
4. Add donation information screen
5. Implement profile management

### Phase 3: Advanced Features
1. Add offline capabilities
2. Implement image caching
3. Add push notifications
4. Implement deep linking
5. Add analytics

### Phase 4: Testing and Deployment
1. Unit testing
2. Integration testing
3. Performance optimization
4. App store deployment

## UI/UX Design Approach

The React Native app will maintain the same design patterns as the current web app:

1. **Bottom Navigation** - Same 4-tab structure (Feed, Events, Donate, Profile)
2. **Card-based Layout** - Posts and events displayed in cards
3. **Consistent Color Scheme** - Maintain brand colors
4. **Responsive Design** - Adapt to different screen sizes
5. **Smooth Animations** - Like button animations, loading indicators

## Technology Stack

### Frontend (React Native)
- React Native CLI
- React Navigation
- Axios for API calls
- Redux or Context API for state management
- AsyncStorage for local storage
- React Native Image Picker for media uploads

### Backend (Laravel - Unchanged)
- Laravel 9
- JWT Authentication
- MySQL Database
- RESTful API

## Benefits of Migration

1. **Native Performance** - Better performance than WebView wrapper
2. **Platform Features** - Access to native device features
3. **Offline Capabilities** - Implement offline data storage
4. **Push Notifications** - Native push notification support
5. **App Store Presence** - Publish to Google Play and Apple App Store
6. **Better User Experience** - Smoother animations and transitions