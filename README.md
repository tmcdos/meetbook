# Application to book for a meeting #

### Manage bookings for meetings with prospects ###

----------
This is a simple web application to manage meetings with prospects.
Each of your sales persons must have an account to login. Once logged, (s)he may define calendars, time slots, prospects,
and send invitations for a meeting.
Prospects can book one (or more, if allowed) of the time slots - and there is a warning if they try to book 2 overlapping time slots
(e.g. to meet with 2 different sales persons at the same time). A prospect may also cancel his/her bookings.
Your sales persons may define different templates for the notification e-mails (invitation, booking confirmation, cancellation confirmation),
but only one template for each of the 3 types may be active at a time.

You will need to download and extract TinyMCE into "tiny_mce" folder, and in order to allow insertion of images into e-mail templates you will
have to also download and activate the TinyMCE plugins "ImageManager" and "FileManager". This application was tested with TinyMCE v2.1.3
