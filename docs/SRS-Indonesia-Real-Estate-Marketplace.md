# Software Requirement Specification (SRS)

## Indonesia Real Estate Marketplace Platform

---

## Executive Summary

The platform is a web-based real estate marketplace designed for Indonesia. It supports property discovery, property listing management, lead generation, subscription plans, agent verification, property verification, administrative moderation, and reporting.

### Business Goals

- Generate qualified buyer and renter leads
- Provide a trusted property marketplace
- Enable scalable nationwide property search
- Support verified agents and verified properties

---

## Project Scope

### Included

- Responsive web platform
- Sale and Rent listings
- Offline payment verification
- Agent verification
- Property verification
- CMS, SEO, and Reporting

### Excluded

- Online payment gateway
- Mobile applications
- MLS integrations

---

## User Roles

| Role | Permissions |
|---|---|
| Visitor | Browse and search public listings |
| Buyer | Save properties, submit inquiries |
| Property Owner | Create listings, manage leads |
| Agent | Create listings, manage leads |
| Administrator | Complete access to all platform modules |

---

## Property Management Module

Property types are master-driven and configurable by administrators. Examples include:

- House
- Villa
- Apartment
- Condominium
- Office
- Warehouse
- Land
- Hotel
- Others

### Workflow

```
Draft → Pending Review → Approved → Published → Expired → Archived
```

---

## Search & Discovery

- Keyword Search
- Address Search
- Auto Suggest
- Map Search
- Radius Search
- Saved Searches

---

## Lead Management

### Lead Sources

- Contact Form
- Phone Inquiry
- Schedule Visit

### Lead Status Workflow

```
New → Contacted → Follow Up → Qualified → Negotiation → Won/Lost
```

---

## Subscription & Offline Payment

### Plans

- Free
- Basic
- Premium
- Enterprise

### Workflow

```
Package Selection → Invoice Generation → Bank Transfer → Payment Proof Upload → Admin Verification → Activation
```

---

## Agent Verification

### Documents

- **Mandatory:** KTP Upload
- **Optional:** Business License, NPWP

### Statuses

- Pending
- Under Review
- Verified
- Rejected
- Suspended

---

## Property Verification

### Documents

- Ownership Certificate
- Land Certificate
- Supporting Legal Documents

### Statuses

- Not Verified
- Pending Verification
- Verified
- Rejected

---

## Admin Modules

- Dashboard
- User Management
- Property Moderation
- Lead Management
- Subscription Management
- Offline Payment Verification
- Verification Center
- Advertisement Management
- CMS Management
- Blog Management
- SEO Management
- Location Management
- Email Templates
- Reports & Analytics

---

## Acceptance Criteria

- All modules tested and approved
- Admin approval workflow operational
- Lead management workflow operational
- Offline payment workflow operational
- Search and reporting modules operational

---

## Advanced Features (Optional)

1. Multilingual support (Bahasa Indonesia and English)
2. Elasticsearch-based search
3. Polygon Search
4. Geo Search
5. Advanced Reporting
6. Tracking / Logs
7. WhatsApp Notifications
8. Social Posting / Advertisement

---

## Non-Functional Requirements (Optional)

### Performance

- Search response < 2 seconds
- High availability architecture

### Security

- SSL
- Role Based Access Control
- Audit Logging
- Password Hashing

### Scalability

- AWS Infrastructure
- Redis Cache
- Elasticsearch Cluster
