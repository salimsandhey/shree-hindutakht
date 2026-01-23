# Hindu Takht App - React Native Structure

## Project Structure

```
hindutakht-mobile/
├── src/
│   ├── components/          # Reusable UI components
│   ├── screens/             # Screen components
│   ├── navigation/          # Navigation setup
│   ├── services/            # API services and utilities
│   ├── store/               # State management (Redux/Context)
│   ├── utils/               # Utility functions
│   ├── assets/              # Images, fonts, etc.
│   └── constants/           # Application constants
├── android/                 # Android specific code
├── ios/                     # iOS specific code
├── App.js                   # Main app component
└── package.json             # Dependencies and scripts
```

## Core Screens

### Authentication Screens
1. **LoginScreen** - Member login form
2. **RegisterScreen** - New member registration
3. **ForgotPasswordScreen** - Password recovery

### Main App Screens
1. **HomeScreen** - Main feed with posts
2. **EventsScreen** - Community events listing
3. **DonationScreen** - Donation information and options
4. **ProfileScreen** - User profile and settings
5. **EditProfileScreen** - Profile editing functionality
6. **NotificationsScreen** - User notifications
7. **PostDetailScreen** - Detailed view of a post
8. **EventDetailScreen** - Detailed view of an event
9. **CreatePostScreen** - For admin to create new posts
10. **CreateEventScreen** - For admin to create new events

## Navigation Structure

### Bottom Tab Navigator
- Home (Feed)
- Events
- Donate
- Profile

### Stack Navigators
Each tab will have its own stack navigator for deeper navigation:

1. **Home Stack**
   - HomeScreen
   - PostDetailScreen
   - CommentsScreen

2. **Events Stack**
   - EventsScreen
   - EventDetailScreen
   - EventRSVPScreen

3. **Donation Stack**
   - DonationScreen

4. **Profile Stack**
   - ProfileScreen
   - EditProfileScreen
   - ChangePasswordScreen
   - IDCardScreen

## Core Components

### UI Components
1. **PostCard** - Display individual posts with media, likes, comments
2. **EventCard** - Display events with date, location, RSVP status
3. **CommentItem** - Display individual comments
4. **NotificationItem** - Display individual notifications
5. **ProfileHeader** - User profile header with avatar and stats
6. **MediaViewer** - Component for viewing images/videos
7. **LikeButton** - Interactive like button with count
8. **RSVPButton** - Event RSVP functionality
9. **ShareButton** - Post sharing functionality

### Form Components
1. **LoginForm** - Authentication form
2. **RegisterForm** - Registration form
3. **EditProfileForm** - Profile editing form
4. **ChangePasswordForm** - Password change form
5. **CreatePostForm** - Post creation form (admin)
6. **CreateEventForm** - Event creation form (admin)
7. **CommentForm** - Comment input form

## State Management

### Global State
- User authentication state
- Notification counts
- App settings

### Screen State
- Loading states
- Form data
- API response data
- Error states

## Services

### API Services
1. **AuthService** - Authentication related API calls
2. **PostService** - Post/feed related API calls
3. **EventService** - Event related API calls
4. **MemberService** - Member/profile related API calls
5. **DonationService** - Donation related API calls
6. **NotificationService** - Notification related API calls

### Utility Services
1. **StorageService** - Local storage utilities
2. **ImageService** - Image processing and optimization
3. **DateService** - Date formatting and manipulation
4. **ValidationService** - Form validation utilities