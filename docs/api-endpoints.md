# Hindu Takht App - API Endpoints

## Authentication

### Member Authentication
- `POST /api/auth/register` - Register new member
- `POST /api/auth/login` - Member login
- `POST /api/auth/logout` - Member logout
- `GET /api/auth/profile` - Get member profile
- `PUT /api/auth/profile` - Update member profile
- `POST /api/auth/update-profile` - Update profile with file uploads
- `POST /api/auth/remove-photo` - Remove profile photo
- `POST /api/auth/change-password` - Change password
- `POST /api/auth/refresh` - Refresh authentication token
- `GET /api/auth/member/id-card` - Generate member ID card
- `GET /api/auth/member/id-card/download` - Download member ID card

### Admin Authentication
- `POST /api/admin/login` - Admin login
- `POST /api/admin/logout` - Admin logout
- `GET /api/admin/profile` - Get admin profile
- `POST /api/admin/refresh` - Refresh admin token

## Posts (Feed)

### Member Post Access
- `GET /api/posts` - Get posts feed
- `GET /api/posts/{post}` - Get specific post
- `POST /api/posts/{post}/like` - Toggle like on post
- `POST /api/posts/{post}/comment` - Add comment to post
- `GET /api/posts/{post}/comments` - Get post comments
- `POST /api/posts/{post}/share` - Share post

### Admin Post Management
- `GET /api/admin/posts` - Get all posts
- `POST /api/admin/posts` - Create new post
- `GET /api/admin/posts/{post}` - Get specific post
- `PUT /api/admin/posts/{post}` - Update post
- `DELETE /api/admin/posts/{post}` - Delete post

## Events

### Member Event Access
- `GET /api/events` - Get upcoming events
- `GET /api/events/{event}` - Get specific event
- `POST /api/events/{event}/rsvp` - RSVP to event
- `GET /api/events/{event}/rsvps` - Get event RSVPs

### Admin Event Management
- `GET /api/admin/events` - Get all events
- `POST /api/admin/events` - Create new event
- `GET /api/admin/events/{event}` - Get specific event
- `PUT /api/admin/events/{event}` - Update event
- `DELETE /api/admin/events/{event}` - Delete event

## Members

### Admin Member Management
- `GET /api/admin/members` - Get all members
- `GET /api/admin/members/stats` - Get member statistics
- `GET /api/admin/members/{member}` - Get specific member
- `PUT /api/admin/members/{member}` - Update member
- `DELETE /api/admin/members/{member}` - Delete member
- `POST /api/admin/members/{member}/toggle-status` - Toggle member status

## Donations

### Public Access
- `GET /api/donation-info` - Get donation information

### Admin Donation Management
- `GET /api/admin/donations` - Get donation settings
- `PUT /api/admin/donations` - Update donation settings

## Notifications

### Member Notifications
- `GET /api/notifications` - Get member notifications
- `POST /api/notifications/{notification}/read` - Mark notification as read
- `POST /api/notifications/read-all` - Mark all notifications as read
- `GET /api/notifications/unread-count` - Get unread notification count