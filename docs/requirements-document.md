#SLP Requirements Document
##JAUNT Inc.


 
#####Nonprofit overview:
To allow Central Virginians with disabilities to get where they need to go safely, efficiently and affordably while respecting the environment. 

#####Contact: 
Lucas Lyons, lucasl@ridejaunt.org, (434)296-3184x120  
John Overton, johno@ridejaunt.org, (434)297-2635

#####Website: 
http://www.ridejaunt.org

#####System summary: 
A system to provide reservation tracking information in a customer-oriented web portal.  Additional requirements include a staff time-sheet system.

#####Development notes: 
This project will run on the servers that are located at their headquarters in Charlottesville; all are Windows servers, and run some type of SQL server.  Any of the three languages are viable.  However, their IT person (John Overton (johno@ridejaunt.org) who will be maintaining this site knows PHP, so there is a strong preference for a PHP-based app.

#####Confidentiality notes: 
The information contained in the database is somewhat confidential.  However, the students will have read-access to another database, which has very confidential data.  Thus, an NDA will likely be necessary.

#####Description
JAUNT is a transportation service for individuals with disabilities.  They provide over 300,000 trips per year to residents of the Charlottesville and surrounding areas.  

JAUNT has a total of 80 vehicles, and about 60 are running at any given time.  On busy days, the can  transport up to 1,000 individuals.  Those who ride on JAUNT are called 'clients'.  There are about 25 non-driver staff, about half of which are hourly; and 90 drivers, all of who are hourly.  There are 3,000 active clients at any time, and around 15,000 clients within the three years.  Clients come and go, for example if they break their leg for a period of time.  About 300,000 rides per year for the last 3 years.  

Their buses are all equipped with a GPS trackers.  They use a commercial system made by Trapeze Software (http://www.trapezegroup.com/, http://en.wikipedia.org/wiki/Trapeze_Software).  The relevant module of that software is the Paratransit Scheduling System, or PASS (also on that Wikipedia page, section 3.4).  A vehicle's GPS sends it's location periodically back to JAUNT's servers, and this data ends up in the PASS database, which can then be queried by the proposed system.  In addition to the obvious longitude and latitude position of each bus, additional information is also stored for each entry, such as the resolved address of that location.  Each bus has an assigned number; trips are scheduled with a given bus number, although traffic and delays can change that bus reservation, sometimes as little as only 20 minutes prior to pick-up.
Although the software system is commercial, the project described herein will only be accessing the data stored in the database, and will NOT be interacting directly with the software.  The data is owned by JAUNT, so there are no licensing issues.  The database itself is quite large (about 1,000 tables in the DB), but only a small part will be relevant to this project (say, 5 tables or so).  The schema of those tables, along with sample data, will be provided to the team by John Overton once the project starts.  Note that the system described herein will only have read-only access to the big database, and will keep a separate database for the system itself.  

Clients can receive a “certification” from CAT (Charlottesville Area Transit) which basically attests to the fact that they have a disability, and thus qualify for discounted fares with this service.  Anybody can ride JAUNT, but this CAT certification allows for the discounted rates.
Currently, reservations are made by clients contacting the JAUNT staff (via email or phone), and the staff make the reservation.  An email confirmation is sent to the clients who requested their reservation via email.  

All employees of JAUNT have a work-assigned email address.  But note that not all clients have email addresses.

#####Features
The primary requirement of this system is a volunteer portal, with the following features:  

- Standard web portal stuff: ability to log in, reset password, change profile information, etc.  

- Reservations
 	- Clients should be able to see their upcoming reservations 
 	- Clients should be able to pull up a historical list of their reservations kept in the SLP system
	- Make reservations
		* The reservations will be stored in the system, and all reservation schedulers will receive an email when a new reservation is added. The scheduler will then process all reservations via the SLP system. The emails sent upon a reservation request will be a system-configurable option, and administrators are able to toggle whether or not these emails are sent.
		* The reservations themselves are made into the PASS system – due to not wanting to modify the PASS database (as that would likely void their warranty), a staff member will enter the information from this system into the PASS system to actually make the reservation. An optional requirement is to see if this can be made more efficient.
		* When the staff member completes the reservation, the system should send an e-mail confirmation to the client.
		* Admins can generate PDF reports for any individual reservation request.
	- Cancel reservations: clients cannot cancel reservations. Staff members can deny reservations after they are approved, which effectively cancels them.
	- The client's CAT certification information will be stored in the database, and will be displayed to the user on the user's profile. This information is not editable by users, but the system will display an alert when the certification will expire in less than one month. The system will also send emails to clients whose certifications expire 30, 60, and 90 days from now via a nightly cronjob.
- Initial sign-up
	- Only allowed clients can have a working web portal account.  Clients will fill out a form (with name, address, email, password, etc.), but can not yet log into the system. A staff member would associate them with a client ID (which is in the PASS system, and likely will need to be copied into this system as well). This is what verifies that they are allowed to use JAUNT's services, and what allows them to actually log in (and, if necessary, finish setting up their account).
	- In addition, staff can create accounts for new users themselves, and manually enter the client ID.
- Reports and graph generation
	- Note that the PASS system generates reports and such for the staff, so only minimal summary graphs will be generated for the staff.
	- Admins can generate PDF reports containing the data for these minimal graphs and the lifetime statistics for the system.
- Multi-lingual
	- Many customers speak Spanish, and there are other primary languages spoken by the clients
	- There should be a way to define additional languages, and then enter translations
	- This means a easy drop-down box (or similar) to change languages on login
- User levels
	- There are five user levels: admins, schedulers, hourly, drivers, and clients
	- Admins can create users and change system settings
	- Staff have three primary flags that determine what functions they can perform and what they see. Admins are separate than other staff.
		* The flags are:
			* Can handle reservations: only a small number of people are “schedulers” and can handle reservations (see them, mark them as handled, etc.) – it will likely be about 6 people.
			* Handle time-off requests (see below): this may be the same people as the schedulers, or it may be a separate permission
		* Note that all admins can handle reservations and approve/deny time off
		* Most staff do not see the forms to handle reservations.  The exception is the staff who are allowed to handle reservations, as they may need to enter that data for somebody else (for example, if a client calls in a reservation, then one will be entering a reservation for somebody else)
		* Any user see the change profile forms, etc.
		* Thus, drivers will really only see their hourly time-off form, as well as the change profile forms
	- Clients see what was described above.
- Time-off system
	- There are two different versions of this: one for hourly workers (which is more important to implement) and one for salaried workers.  The one for salaried workers is not being implemented in this system.
		* They currently request time off via a form; the idea is to implement that via a web form in the portal
		* A staff member will then proceed through the requests and approve or deny each one; such approval or denial will result in an email being sent to the staff member
		* Display of a calendar for all the requested time off.  This calendar is visible to only those who can approve time off requests and the requester themselves. This does not need to be fancy like Google Calendar, but it does need to be a easy-to-view table format.
		* Each user will be able to see their pending and approved timeoff requests on a calendar on their user profile.
		* Admins can generate PDF reports for any individual timeoff request.
			
#####Requirements: Minimum
- [x] Web portal application form, staff approval, login, user types, and user permissions
- [x] Single language (English)
- [x] Password reset
- [x] Calendar view when viewing time off requests
- [x] Time off (requesting, view, approve, deny)
- [x] Reservations (requesting, view, approve, deny)
- [x] Basic admin metrics
- [x] Links to tutorial pages (how to ride, pricing, etc)

#####Requirements: Desired
- [x] Calendar view to make submitting time off requests easier
- [x] More advanced metric queries and better looking displays (graphs and visuals for metrics)
- [x] Multi-lingual support
- [x] Negotiation
- [x] PDF reports
- [x] Admin settings page
- [x] Refine styling

#####Requirements: Optional
None!

#####Notes
None!

