# PHP MVC + Auth + ACL (Minimal)

Features:
- Users, Roles, Modules, Permissions
- Role-based ACL + per-user overrides
- Login/logout with password hashing
- CSRF protection for all POST forms
- Basic login throttling (5 attempts / 5 minutes per email+IP)
- Simple Roles & Permissions UI

## Quick Start
1. Create MySQL database `acl_demo` (or change in `config/config.php`).
2. Import `database/schema.sql`.
3. Point web server docroot to `public/`.
4. Visit `/auth/login` and sign in: **admin@example.com / admin123**.

## Add a Module
1. Insert a row into `modules` with a unique `slug` (e.g., `tenants`).
2. Insert its actions into `permissions` for that module (`view/add/edit/update/delete`).
3. Assign permissions to roles via **Roles → Permissions** UI.
4. Create your controller + views and guard with `ACL::ensure('<slug>','<action>')`.




Steps to Add a New Module

1. Create the Database Table

Define a table for your module (e.g., offers) with appropriate columns for the entity you’re managing.

Add the Module to modules Table

Insert a record with the module’s name, slug, and description.

Add Default Permissions

Create actions for the module (e.g., view, add, edit, update, delete) in the permissions table.

Assign Permissions to Roles

Grant the desired roles (like Admin) full access to the module by inserting into role_permissions.

2. Create a Model

Make a class representing the module, with methods for CRUD operations (create, read, update, delete).

3. Create a Controller

Make a controller class for the module with methods corresponding to actions (index, create, edit, update, delete).

Use ACL checks (require_can) to enforce permissions.

4. Create Views

Create views for listing records, creating/editing forms, and optionally detailed views.

5. Add Routes (Optional)

Ensure your MVC router can handle the module’s controller and its methods.

Typically, your App class already routes based on URL segments.

6. Update Navigation/Menu

Add links in your layout or navbar using the url() helper and permission checks (can()).

Test

Test the module’s pages, CRUD operations, and permission enforcement with different users/roles.

## Modules

1. Client Management
•	Register and update client details
•	Link client to offer
•	Display payment status and balances
2. Offer Tracking
•	Create and track land offers
•	Calculate total cost (including fees)
•	Mark offers as surrendered or expired
3. Payment Recording
•	Add partial or full payments
•	Auto-calculate balances
•	Highlight overdue accounts
4. Reports
•	Generate financial summaries
•	List defaulters and outstanding payments
•	Export reports as downloadable HTML or PDF (via PHP libraries)
5. Search & Filter Tools
•	Search by name, plot number, receipt number
•	Filter clients by payment status, deadline range


[Clients]
  └── client_id (PK)
  └── name
  └── type

        ↓ 1:N

[Offers]
  └── offer_id (PK)
  └── client_id (FK → Clients.client_id)
  └── plot_number
  └── area_hectarage
  └── area_location
  └── offer_amount
  └── deadline_date
  └── status
  └── date_of_issue
  └── ground_rent
  └── offer_listing_id

        ↓ 1:N                    ↓ 1:1

[Payments]                  [Fees]
  └── payment_id (PK)         └── fee_id (PK)
  └── client_id (FK)          └── offer_id (FK → Offers.offer_id)
  └── offer_id (FK)           └── legal_fees
  └── amount_paid             └── app_fees
  └── payment_date            └── development_charges
  └── receipt_number          └── stamp_duty
  └── description             └── status

        ↓ N:1

[Audit_Log]
  └── log_id (PK)
  └── table_name
  └── record_id
  └── change_type
  └── changed_by
  └── change_date
  └── old_value
  └── new_value

  [Offer_Listing]
  └── offer_listing_id (PK)
  └── name_description
  └── district_area
  └── date
  └── total_number_of_offers
  └── committee
  └── chair_person
  └── digitized_copy_loose_minute
  └── uploaded_by
  └── uploaded_date



Controllers

Each module should have a controller class (e.g., ClientsController.php) in your controllers folder.

Here’s the list:

ClientsController

index() → List all clients

create() → Show form for new client

store() → Save client to DB

edit($id) → Show form to edit client

update($id) → Save changes

delete($id) → Remove client

linkOffer($id) → Link client to offer

paymentStatus($id) → Show balances

OffersController

index() → List all offers

create() → Create new offer

store() → Save offer

edit($id) → Edit existing offer

update($id) → Update offer

delete($id) → Delete offer

calculateCost($id) → Calculate total + fees

markExpired($id) → Mark as expired/surrendered

PaymentsController

index() → List payments

create() → Record new payment

store() → Save payment

edit($id) → Edit payment

update($id) → Update payment

delete($id) → Delete payment

calculateBalance($clientId) → Auto-calc balances

highlightOverdue() → Show overdue accounts

ReportsController

index() → Report dashboard

financialSummary() → Summaries per period

defaulters() → List unpaid/late clients

outstanding() → Show balances

exportPdf() → Generate PDF reports

AnalysisController (Search & Filter)

index() → Search form / filter page

searchClients() → By name, plot number, receipt number

filterPayments() → By payment status, deadline range

Views

Each module gets its own folder in views/:

views/clients/

index.php → List clients

create.php → New client form

edit.php → Edit client

link_offer.php → Link client to offer

status.php → Show balances

views/offers/

index.php → List offers

create.php → New offer form

edit.php → Edit offer

calculate.php → Cost calculation

expired.php → Expired/surrendered list

views/payments/

index.php → Payment list

create.php → New payment

edit.php → Edit payment

overdue.php → Highlight overdue

views/reports/

index.php → Report dashboard

financial_summary.php

defaulters.php

outstanding.php

export.php (download link)

views/analysis/

index.php → Search form

results.php → Display search results

filter.php → Filter UI