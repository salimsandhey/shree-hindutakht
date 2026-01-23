# Hindu Takht App - Data Models

## Core Models

### Member
Represents a community member/user of the application.

**Fields:**
- id (bigint, primary key)
- member_id (string) - Unique member identifier (HT + 6 digits)
- name (string) - Full name
- email (string) - Email address
- password (string) - Hashed password
- phone (string, nullable) - Phone number
- address (text, nullable) - Physical address
- photo (string, nullable) - Profile photo path
- date_of_birth (date, nullable) - Date of birth
- gender (string, nullable) - Gender
- status (string) - Account status (active/inactive)
- joined_at (datetime) - Member since date
- email_verified_at (datetime, nullable) - Email verification timestamp
- remember_token (string, nullable) - Remember me token
- created_at (datetime)
- updated_at (datetime)

**Relationships:**
- Has many Posts
- Has many PostLikes
- Has many PostComments
- Has many EventRsvps
- Has many Notifications

### Admin
Represents an administrator user with content management privileges.

**Fields:**
- id (bigint, primary key)
- name (string) - Full name
- username (string) - Username for login
- email (string) - Email address
- password (string) - Hashed password
- phone (string, nullable) - Phone number
- photo (string, nullable) - Profile photo path
- role (string) - Admin role (super_admin, content_manager, etc.)
- status (string) - Account status (active/inactive)
- last_login_at (datetime, nullable) - Last login timestamp
- created_at (datetime)
- updated_at (datetime)

**Relationships:**
- Has many Posts (as author)

### Post
Represents a post in the community feed.

**Fields:**
- id (bigint, primary key)
- member_id (bigint, foreign key, nullable) - Author (if member created)
- admin_id (bigint, foreign key, nullable) - Author (if admin created)
- content (text) - Post content
- media (json, nullable) - Array of media file paths
- type (string) - Post type (text, image, video, mixed)
- is_pinned (boolean) - Whether post is pinned to top
- is_featured (boolean) - Whether post is featured
- status (string) - Post status (active, draft, archived)
- likes_count (integer) - Cached like count
- comments_count (integer) - Cached comment count
- shares_count (integer) - Cached share count
- published_at (datetime) - Publication timestamp
- created_by_admin (boolean) - Whether created by admin
- created_at (datetime)
- updated_at (datetime)

**Relationships:**
- Belongs to Member (nullable)
- Belongs to Admin (nullable)
- Has many PostLikes
- Has many PostComments

### PostLike
Represents a like on a post by a member.

**Fields:**
- id (bigint, primary key)
- post_id (bigint, foreign key) - Post being liked
- member_id (bigint, foreign key) - Member who liked
- created_at (datetime)
- updated_at (datetime)

**Relationships:**
- Belongs to Post
- Belongs to Member

### PostComment
Represents a comment on a post by a member.

**Fields:**
- id (bigint, primary key)
- post_id (bigint, foreign key) - Post being commented on
- member_id (bigint, foreign key) - Member who commented
- comment (text) - Comment content
- parent_id (bigint, foreign key, nullable) - For threaded comments
- is_approved (boolean) - Whether comment is approved
- created_at (datetime)
- updated_at (datetime)

**Relationships:**
- Belongs to Post
- Belongs to Member
- Has many Replies (self-referencing through parent_id)

### Event
Represents a community event.

**Fields:**
- id (bigint, primary key)
- title (string) - Event title
- description (text) - Event description
- location (string) - Event location
- featured_image (string, nullable) - Featured image path
- event_date (datetime) - Event date and time
- registration_deadline (datetime, nullable) - RSVP deadline
- max_participants (integer, nullable) - Maximum participants
- status (string) - Event status (upcoming, ongoing, completed, cancelled)
- is_featured (boolean) - Whether event is featured
- interested_count (integer) - Cached interested count
- going_count (integer) - Cached going count
- additional_info (json, nullable) - Additional event information
- created_at (datetime)
- updated_at (datetime)

**Relationships:**
- Has many EventRsvps

### EventRsvp
Represents a member's RSVP to an event.

**Fields:**
- id (bigint, primary key)
- event_id (bigint, foreign key) - Event being RSVP'd to
- member_id (bigint, foreign key) - Member RSVPing
- response (string) - RSVP response (interested, going)
- notes (text, nullable) - Additional notes
- created_at (datetime)
- updated_at (datetime)

**Relationships:**
- Belongs to Event
- Belongs to Member

### Notification
Represents a notification for a member.

**Fields:**
- id (bigint, primary key)
- member_id (bigint, foreign key) - Member receiving notification
- type (string) - Notification type (post_like, comment, event, etc.)
- title (string) - Notification title
- message (text) - Notification message
- data (json, nullable) - Additional data
- is_read (boolean) - Whether notification is read
- read_at (datetime, nullable) - When notification was read
- created_at (datetime)
- updated_at (datetime)

**Relationships:**
- Belongs to Member

### AppSetting
Represents application-wide settings.

**Fields:**
- id (bigint, primary key)
- key (string) - Setting key
- value (text) - Setting value
- type (string) - Setting type (string, integer, boolean, json)
- description (text, nullable) - Setting description
- created_at (datetime)
- updated_at (datetime)

### DonationSetting
Represents donation-related settings.

**Fields:**
- id (bigint, primary key)
- bank_details (json) - Bank account information
- upi_id (string, nullable) - UPI payment ID
- qr_code (string, nullable) - QR code image path
- additional_info (json, nullable) - Additional donation information
- is_active (boolean) - Whether donations are active
- created_at (datetime)
- updated_at (datetime)