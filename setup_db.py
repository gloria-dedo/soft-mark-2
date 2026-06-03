import sqlite3
import os

db_path = os.path.join(os.path.dirname(__file__), 'database', 'erp_store.sqlite')
conn = sqlite3.connect(db_path)
cursor = conn.cursor()

# Add more ERP products
more_products = [
    (
        'Supply Chain Management',
        'End-to-end supply chain visibility from procurement to delivery. Manage vendors, purchase orders, and logistics.',
        379.99, 4.7,
        'assets/images/supply_chain.jpg',
        'Vendor Portal, PO Automation, Logistics Tracking, Demand Forecasting'
    ),
    (
        'Project Management Suite',
        'Plan, track, and deliver projects on time and within budget. Supports agile and waterfall methodologies.',
        329.99, 4.6,
        'assets/images/project_mgmt.jpg',
        'Gantt Charts, Resource Allocation, Time Tracking, Budget Management'
    ),
    (
        'Manufacturing MRP',
        'Material Requirements Planning for production scheduling, bill of materials, and shop floor control.',
        549.99, 4.8,
        'assets/images/manufacturing.jpg',
        'Bill of Materials, Production Scheduling, Quality Control, Machine Utilisation'
    ),
    (
        'Payroll Management',
        'Accurate, automated payroll processing with tax calculation, benefits administration and payslip generation.',
        249.99, 4.9,
        'assets/images/payroll.jpg',
        'Automated Tax, Employee Self-service, Direct Deposit, Benefits Management'
    ),
    (
        'Business Intelligence',
        'Transform raw data into powerful insights with real-time analytics, custom reports, and interactive dashboards.',
        459.99, 4.7,
        'assets/images/bi.jpg',
        'Custom Reports, KPI Tracking, Data Visualisation, Scheduled Exports'
    ),
    (
        'Point of Sale (POS)',
        'Fast, reliable POS system with inventory sync, customer loyalty, and multi-location support.',
        229.99, 4.5,
        'assets/images/pos.jpg',
        'Inventory Sync, Loyalty Programs, Receipt Printing, Offline Mode'
    ),
    (
        'Fleet Management',
        'Track and manage your vehicle fleet with GPS tracking, maintenance scheduling, and fuel management.',
        319.99, 4.4,
        'assets/images/fleet.jpg',
        'GPS Tracking, Maintenance Alerts, Fuel Monitoring, Driver Management'
    ),
    (
        'Document Management',
        'Centralised document repository with version control, digital signatures, and workflow approvals.',
        189.99, 4.6,
        'assets/images/document.jpg',
        'Version Control, e-Signatures, Approval Workflows, Full-text Search'
    ),
    (
        'Quality Management',
        'Maintain product and process quality with inspection checklists, non-conformance reporting, and CAPA.',
        299.99, 4.5,
        'assets/images/quality.jpg',
        'Inspection Checklists, CAPA Tracking, Audit Trails, ISO Compliance'
    ),
]

# Check and insert only missing ones
for p in more_products:
    cursor.execute("SELECT id FROM products WHERE name = ?", (p[0],))
    if not cursor.fetchone():
        cursor.execute(
            "INSERT INTO products (name, description, price, rating, image_url, features) VALUES (?, ?, ?, ?, ?, ?)",
            p
        )
        print(f"  + Added: {p[0]}")
    else:
        print(f"  - Skipped (exists): {p[0]}")

conn.commit()
conn.close()
print("\nDone! All ERP products seeded.")
