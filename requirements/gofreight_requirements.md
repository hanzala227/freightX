# GoFreight Complete Functional Requirements

This document outlines the comprehensive module and feature requirements extracted from the GoFreight platform. The Freight Management System (FMS) must cover the following domains, structured perfectly into API routes, models, and UI components consistent with the platform theme.

## 1. Dashboard & Analytics
- **Total Profit**: KPI chart and numerical tracking for overall profitability.
- **Total Volume**: Number of Bills of Lading (B/L) and Air Waybills (AWB).
- **Customer Insights**: Tracking of Active Customer counts, Lost Customers, Top 5 Profit Customers, and Top 5 Volume Customers.
- **Negative Profit Tracking**: Dedicated tracking for shipments operating at a loss.

## 2. Action Center / Workflow
- **Task List**: Operational task management and workflow progression tracking.

## 3. Ocean Import
- **New Shipment**: Ability to create fresh ocean import records.
- **New Shipment from Quote**: System flow to seamlessly transition sales quotations into active ocean imports.
- **Shipment Lists**: 'My Shipment List', 'Master B/L (MBL) List', 'House B/L (HBL) List'.
- **Container Tracking**: 'My Containers' tracking timeline.
- **EDI History**: Historic logs for Electronic Data Interchange synchronization.

## 4. Ocean Export
- **New Shipment / New Shipment from Quote**.
- **Shipment Lists**: 'My Shipment List', 'Master B/L List', 'House B/L List'.
- **Bookings Management**: 'New Booking', 'New Booking from Quote', 'Booking List'.
- **Schedules**: 'New Vessel Schedule', 'Vessel Schedule List'.

## 5. Air Import
- **New Shipment / New Shipment from Quote**.
- **Shipment Lists**: 'My Shipment List', 'MAWB List', 'HAWB List'.

## 6. Air Export
- **New Shipment / New Shipment from Quote**.
- **Bookings Management**: 'New Booking', 'New Booking from Quote', 'Booking List'.
- **Shipment Lists**: 'My Shipment List', 'MAWB List', 'HAWB List'.
- **MAWB Stock List**: Inventory management for Air Waybills.

## 7. Truck (Domestic Freight)
- **Shipment Management**: 'New Shipment', 'New Shipment from Quote', 'My Shipment List', 'Shipment List'.

## 8. Miscellaneous Operations
- **Operation Management**: 'New Operation', 'My Operation List', 'Operation List' (for non-standard logistics services like customs clearing alone, warehousing alone, etc.).

## 9. Warehouse Logistics
- **Automobile List**: Specialized tracking interfaces for vehicle and heavy machinery freight.
- **Receipts**: Documentation and management of warehouse receipts (WHR).
- **Receiving & Shipping**: Dock management, receiving logs, and outbound movement records.

## 10. Accounting & Finance
- **Invoice & Cost Management**: 'Invoice/Cost List', 'G&A Invoice/Expense List', 'Create G&A Expense', 'Create G&A Invoice'.
- **Payment Processing**: AP (Accounts Payable) and AR (Accounts Receivable) payment tracking.
- **Bank Management**: Bank balances, multi-currency tracking, and transactions.
- **Journal**: General Ledger (GL) entries and financial journal logs.
- **Reports**: Financial statements, balance sheets, and accounting audit trails.

## 11. Sales & CRM
- **Quotation Management**: 'New Quotation', 'Quotation List'.

## 12. Trade Partners
- **Partner Management**: 'New Trade Partner', 'Trade Partner List'.
- **Financial Controls**: 'Trade Partner Credit Entry' for managing partner credit limits.
- **Integrations**: 'Trade Partner Mapping List' (for external EDI/System Integration).

## 13. System Reports
- **Management Reports**: Volume & Profit Report/Chart, Employee Performance and sales routing reports.
- **Operational Reports**: Master Shipment Report, Container Storage/Demurrage Report.
- **Advanced Reports**: Customizable Business Intelligence (BI) reporting generator.
- **Audit Reports**: System access logs, User Log In/Out Active Reports.

## 14. Settings & Configurations
- **Financial Settings**: Accounting configurations (Currency Tables, Bank Lists, Billing Codes, G/L Codes), and digital payment gateway settings (e.g., GoFreight Pay mechanics).
- **Operational Setup**: To Do List configuration, IT No. Management, AWB No. Management, Package Unit dictionary, Container TP/SZ templates.
- **System Admin**: Role-based User Management, Tracking User Management, Country/Port Management.
- **Integrations**: Customer Portal configurations, Tracking Notification (Email/SMS) & Custom Report Settings.

## 15. Tools & Utilities
- **Data Integrations**: 'EDI Import Shipment' for bulk ingestion.
- **Bulk Tools**: 'Import & Update' utilities for mass data management.
- **Industry Links**: Quick-access bookmarks for port authorities and tracking sites.
