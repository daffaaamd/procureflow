<div align="center">

# 📦 ProcureFlow

**Enterprise Procurement Management Platform**

A centralized procurement system that simplifies the entire purchasing cycle — from purchase requests and approvals to purchase orders, goods receipts, invoices (with 3-way matching), and payments — in one clean, role-based dashboard.

**Live Demo → [procureflow-wheat.vercel.app](https://procureflow-wheat.vercel.app)**

<br/>

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v3-38B2AC?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-4-FF6384?style=for-the-badge&logo=chart.js&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-6-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Vercel](https://img.shields.io/badge/Deployed%20on-Vercel-000000?style=for-the-badge&logo=vercel&logoColor=white)

</div>

<p align="center">
    <img src="https://images.unsplash.com/photo-1578574577315-3fbeb0cecdc2?auto=format&fit=crop&w=1400&q=80"
         alt="ProcureFlow — centralized procurement for PT XYZ Enterprise" width="100%">
</p>

---

## ✨ Highlights

- **End-to-end procurement workflow** — Purchase Request → Approval → Purchase Order → Goods Receipt → Invoice → Payment, tracked across the whole cycle.
- **3-way invoice matching** — invoices are automatically matched against the Purchase Order and Goods Receipt (quantity & amount) to flag `Matched` / `Mismatched`.
- **Role-based access** — dedicated portals for `Admin`, `Requester`, `Procurement`, `Manager`, `Warehouse`, and `Finance` with route-level permission middleware.
- **Rich dashboard** — KPI cards (PRs, pending approvals, active POs, outstanding invoices, total procurement value), spending trend chart, recent POs, top vendors, and a live activity feed.
- **Master data** — vendors with ratings & tax numbers, products with SKU, category, price, and stock.
- **Transparency** — every action is written to an audit log and available in the reports & audit views.
- **Secure & reliable** — all workflow steps run inside database transactions via dedicated service classes.

---

## 👥 Demo Accounts

All demo accounts use password **`password`**.

| Role         | Email                       | Access                                                        |
|--------------|-----------------------------|---------------------------------------------------------------|
| Admin        | `admin@procureflow.test`    | Full access incl. reports & audit                             |
| Manager      | `manager@procureflow.test`  | Approve / reject purchase requests                            |
| Procurement  | `procurement@procureflow.test` | Create & manage purchase orders, vendors, products         |
| Requester    | `requester@procureflow.test`| Submit purchase requests                                      |
| Warehouse    | `warehouse@procureflow.test`| Record goods receipts                                         |
| Finance      | `finance@procureflow.test`  | Verify invoices, process payments                             |

> The login form is pre-filled with `admin@procureflow.test` / `password` for quick access.

---

## 🔄 Procurement Workflow

```
📝 Purchase Request  →  ✅ Approval (Manager)  →  📄 Purchase Order (Procurement)
        ↓
📦 Goods Receipt (Warehouse)  →  🧾 Invoice + 3-Way Match (Finance)  →  💳 Payment
```

Each step transitions the document status and is recorded in the audit trail.

---

## 🖼 Preview

<p align="center">
  <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=900&q=80" alt="Office & Procurement" width="48%">
  <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c50800?auto=format&fit=crop&w=900&q=80" alt="Warehouse & Logistics" width="48%">
</p>

<p align="center">
  <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=900&q=80" alt="Corporate Workspace" width="48%">
  <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=900&q=80" alt="Workspace & IT Procurement" width="48%">
</p>

> ⚠️ Images above are theme illustrations. Explore the actual interface at the [live demo](https://procureflow-wheat.vercel.app) — sign in with the demo account pre-filled on the login page (password `password`).

---

## 🛠 Tech Stack

| Layer      | Technology                                                        |
|------------|-------------------------------------------------------------------|
| Backend    | PHP 8.2, Laravel 11                                                |
| Frontend   | Tailwind CSS v3, Alpine.js (CDN), Chart.js                         |
| Build tool | Vite + Laravel Vite Plugin                                         |
| Database   | MySQL (local) · SQLite (Vercel serverless)                         |
| Hosting    | Vercel (`vercel-php` runtime) + GitHub                             |

---

## 🚀 Getting Started

### Prerequisites

- PHP ≥ 8.2
- Composer
- Node.js ≥ 18
- MySQL (or SQLite for a zero-setup start)

### Local development

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment setup
cp .env.example .env
php artisan key:generate

# 3. Configure database in .env (defaults to SQLite below)
# DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite

# 4. Run migrations & seed demo data
php artisan migrate --seed

# 5. Build frontend assets
npm run build

# 6. Start the dev server
php artisan serve
```

Then open `http://127.0.0.1:8000`.

> 💡 `DatabaseSeeder` generates 6 role-based demo users, 10 vendors, 40 products, and 30 full procurement workflows (PR → PO → GR → Invoice → Payment).

---

## ☁️ Deploying to Vercel

1. Push this repository to GitHub.
2. Import the repository in [Vercel](https://vercel.com/new) (framework preset: **Other**).
3. The included [`vercel.json`](vercel.json) handles the PHP runtime, static assets, and routing — no extra configuration needed.
4. Set a fresh `APP_KEY` in the Vercel project environment variables.

Key files:

```
vercel.json    → serverless config, env vars, and route rules
api/index.php  → PHP bridge for the serverless runtime (tmp dirs, Laravel bootstrap)
```

> The dashboard chart query is **driver-agnostic** (`DATE_FORMAT` on MySQL / `strftime` on SQLite) so the app runs identically in both environments.

---

## 📁 Project Structure

```
app/
├── Http/Controllers/      → Auth, dashboard, and module controllers (PR, PO, GR, invoices, payments, vendors, products)
├── Models/                → Eloquent models (PurchaseRequest, PurchaseOrder, GoodsReceipt, Invoice, Payment, Vendor, Product, …)
└── Services/              → Business logic in transactions (approvals, PO creation, receiving, 3-way match, payments)
resources/views/
├── layouts/               → App shell, navigation & sidebar
├── auth/                  → Login page
├── dashboard.blade.php    → KPI cards, spending chart, recent POs, top vendors
└── pr|po|gr|invoices|payments|vendors|products|reports|audit → module views
database/seeders/          → Demo users, vendors, products & 30 workflow scenarios
```

---

## 🗺 Roadmap Ideas

- [ ] PDF export for PRs, POs, and invoices
- [ ] Email notifications & approval reminders
- [ ] Reorder point alerts from product stock
- [ ] Multi-company tenancy

---

## 📄 License

This is a portfolio project built with [Laravel](https://laravel.com) (MIT license).

<div align="center">

**Designed & Made with ❤️ by Daffa Ahmad Baihaqi**

</div>
