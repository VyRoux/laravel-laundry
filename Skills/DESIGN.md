# Laundry Ibu — UI/UX Design Document

## 1. Brand Identity

| Atribut | Detail |
|---------|--------|
| **Nama Aplikasi** | Laundry Ibu |
| **Tagline** | *(none currently)* |
| **Tone** | Profesional, hangat, terpercaya |
| **Target User** | Admin & kasir laundry skala kecil-menengah |
| **Aksen** | Indigo → Blue gradient (`bg-gradient-to-r from-indigo-600 to-blue-500`) |

### Logo
- Digunakan inisial **"LI"** sebagai logo compact di sidebar (saat collapsed).
- Nama panjang "Laundry Ibu" muncul saat sidebar expanded.

---

## 2. Color Palette

### Primary — Indigo / Blue
```css
/* Gradien utama */
from-indigo-600 to-blue-500

/* Solid */
indigo-600   /* #4f46e5 — tombol, link, badge aktif */
indigo-50    /* #eef2ff — hover sidebar, bg item aktif */
blue-500     /* #3b82f6 — secondary accent */
```

### Neutrals — Slate
```css
slate-50   /* #f8fafc — background halaman */
slate-100  /* #f1f5f9 — border ringan, bg tabel header */
slate-200  /* #e2e8f0 — border card, sidebar divider */
slate-400  /* #94a3b8 — teks sekunder, label */
slate-600  /* #475569 — teks body */
slate-700  /* #334155 — judul, teks tebal */
slate-800  /* #1e293b — heading besar */
slate-900  /* #0f172a — body default */
```

### Status Semantic Colors

| Status | Warna | Penggunaan |
|--------|-------|------------|
| `baru` | blue-600 / blue-50 | Status transaksi baru |
| `proses` | amber-600 / amber-50 | Status sedang diproses |
| `selesai` | emerald-600 / emerald-50 | Status selesai |
| `diambil` | indigo-600 / indigo-50 | Status sudah diambil |
| `dibayar` | green-600 / green-50 | Pembayaran lunas |
| `belum_dibayar` | red-600 / red-50 | Pembayaran pending |
| **Laki-laki** | blue-50 / blue-600 | Badge gender |
| **Perempuan** | pink-50 / pink-600 | Badge gender |
| **Hapus/Danger** | red-600 / red-50 | Tombol hapus, trash icon |

```css
/* Contoh status badge */
<span class="px-3 py-1 rounded-lg text-xs font-bold
    {{ $status == 'baru' ? 'bg-blue-50 text-blue-600' :
       ($status == 'proses' ? 'bg-amber-50 text-amber-600' :
       ($status == 'selesai' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600')) }}">
```

---

## 3. Typography

| Elemen | Kelas | Penggunaan |
|--------|-------|------------|
| Font | `font-sans` (Instrument Sans via Tailwind) | Default |
| Halaman | `text-2xl font-bold text-slate-800 mb-6` | `@yield('title')` |
| Card title | `text-lg font-bold text-slate-700` | Judul section |
| Table header | `text-xs uppercase font-semibold text-slate-600` | `thead` |
| Table cell | `text-sm` | `tbody td` |
| Invoice kode | `font-mono text-sm font-bold text-indigo-600` | Kode invoice |
| Body | `text-sm text-slate-600` | Paragraf |
| Label form | `text-sm font-bold text-slate-700 mb-1` | Label input |
| Brand | `text-lg font-bold bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent` | Sidebar brand |

### Linear Scale
```
Heading h1     → 3xl (login), 2xl (dashboard)
Section title  → lg
Table body     → sm
Label          → xs (uppercase)
```

---

## 4. Layout & Grid

### App Shell
```
┌─────────────────────────────────────────────┐
│  SIDEBAR (collapsible)  │   HEADER (h-16)    │
│  ┌───────────────────┐  ├────────────────────┤
│  │ w-64 / w-16       │  │                    │
│  │                   │  │   MAIN CONTENT      │
│  │ - Brand           │  │   (overflow-y-auto) │
│  │ - Nav items       │  │                    │
│  │ - Pin + Logout    │  │   max-w-7xl mx-auto│
│  └───────────────────┘  │                    │
│                         └────────────────────┘
└─────────────────────────────────────────────┘
```

### Header (h-16)
- Kiri: Hamburger (mobile only)
- Kanan: Nama user + role badge + avatar inisial (`rounded-xl bg-indigo-600`)
- Border bottom: `border-slate-200`

### Sidebar Behavior
| State | Lebar | Trigger |
|-------|-------|---------|
| Collapsed | `w-16` | Default desktop |
| Expanded | `w-64` | Hover (desktop) / Click pin |
| Pinned | `w-64` | Tombol pin (persisted via localStorage) |
| Mobile open | Full overlay | Hamburger click |

### Halaman CRUD (List)
```
┌─────────────────────────────────────────┐
│  ┌─────────────────────────────────────┐│
│  │  Daftar X        [+ Tambah X]      ││  ← card header
│  ├─────────────────────────────────────┤│
│  │  ┌─────────────────────────────────┐││
│  │  │  Tabel Data (overflow-x-auto)   │││  ← card body
│  │  │  - thead (bg-slate-50)         │││
│  │  │  - tbody (divide-y)            │││
│  │  │  - Aksi: Edit | Hapus          │││
│  │  └─────────────────────────────────┘││
│  └─────────────────────────────────────┘│
│  [Trash Icon] fixed bottom-right       │
└─────────────────────────────────────────┘
```

### Halaman Form (Create/Edit)
```
┌─────────────────────────────────────────┐
│  ┌─────────────────────────────────────┐│
│  │  Judul Form                         ││
│  │  ─────────────────────────────────  ││
│  │  [Input] [Input]                    ││  ← 1-2 column grid
│  │  [Select] [Select]                  ││
│  │  ...                                ││
│  │  ─────────────────────────────────  ││
│  │  [Simpan]          [Batal]          ││
│  └─────────────────────────────────────┘│
└─────────────────────────────────────────┘
```

### Halaman Transaksi (Create)
```
┌─────────────────────────────────────────┐
│  ┌─────────────────────────────────────┐│
│  │  [Outlet] [Member] [Tgl] [Batas]   ││
│  │  ─────────────────────────────────  ││
│  │  Item Paket          [+ Tambah]    ││
│  │  [Paket▼] [Qty] [Ket] [✕]         ││  ← dynamic rows
│  │  [Paket▼] [Qty] [Ket] [✕]         ││     with Alpine.js
│  │  ─────────────────────────────────  ││
│  │  [Biaya Tamb] [Diskon] [Pajak]     ││
│  │  [Status▼] [Pembayaran▼]           ││
│  │  ─────────────────────────────────  ││
│  │  [Simpan]              [Batal]     ││
│  └─────────────────────────────────────┘│
└─────────────────────────────────────────┘
```

### Invoice / Show Transaksi
```
┌─────────────────────────────────────────┐
│  Kop Toko (nama outlet, alamat, telp)   │
│  ─────────────────────────────────────  │
│  INVOICE                                │
│  Kode: INV/20260523/00001              │
│  ┌──────────────┐ ┌──────────────────┐ │
│  │ Info Pelanggan│ │ Info Transaksi   │ │
│  └──────────────┘ └──────────────────┘ │
│  ┌────────────────────────────────────┐ │
│  │ Tabel Item (Paket, Qty, Harga,    │ │
│  │          Subtotal)                │ │
│  └────────────────────────────────────┘ │
│  ┌─────────────────────┐               │
│  │ Ringkasan Total     │               │
│  │ Subtotal: Rp ...    │               │
│  │ Diskon: -Rp ...     │               │
│  │ Grand Total: Rp ... │               │
│  └─────────────────────┘               │
│  [← Kembali]   [Edit]   [Cetak]        │  ← no-print
└─────────────────────────────────────────┘
```

---

## 5. Navigation

### Sidebar Menu Items

**Operasional** (admin & kasir)
| Icon | Nama | Route |
|------|------|-------|
| 📋 Clipboard | Transaksi | `route('transaksi.index')` |
| 📦 Package | Paket | `route('paket.index')` |
| 👥 Users | Pelanggan | `route('member.index')` |

**Manajemen** (admin only)
| Icon | Nama | Route |
|------|------|-------|
| 🏢 Building | Outlet | `route('outlet.index')` |
| 👤 Person | Pengguna | `route('user.index')` |

**Footer**
| Icon | Aksi |
|------|------|
| 📌 Pin | Toggle pin sidebar (persisted) |
| 🚪 Logout | POST logout (red, hover:bg-red-50) |

### Status Active Page
`request()->routeIs('entity.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : ''`

### Breadcrumb
Belum diimplementasikan (t tempat kosong di header).

---

## 6. Component Patterns

### Cards / Containers
```blade
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
```

### Tombol Primary
```blade
<button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
    + Tambah Baru
</button>
```

### Tombol Edit
```blade
<a class="text-amber-600 hover:underline font-semibold">Edit</a>
```

### Tombol Hapus
```blade
<button class="text-red-600 hover:underline font-semibold">Hapus</button>
```

### Form Input
```blade
<input class="w-full px-4 py-3 rounded-2xl border border-slate-200
              focus:outline-none focus:ring-2 focus:ring-indigo-500
              transition-all bg-white/50" />
```

### Select
```blade
<select class="w-full border rounded py-2 px-3
               focus:outline-none focus:ring-2 focus:ring-indigo-500">
```

### Status Badge
```blade
<span class="capitalize px-3 py-1 rounded-lg text-xs font-bold
    bg-{color}-50 text-{color}-600">
    {{ $status }}
</span>
```

### Notification / Alert

**Success** (auto-dismiss 3 detik)
```blade
<div x-data="{ show: true }" x-show="show"
     x-init="setTimeout(() => show = false, 3000)"
     class="flex items-center p-4 mb-4 text-emerald-800
            bg-emerald-50 border border-emerald-100 rounded-2xl shadow-sm">
    <span class="font-semibold text-sm">{{ session('success') }}</span>
</div>
```

**Error** (dismissible by user)
```blade
<div x-data="{ show: true }" x-show="show"
     class="flex items-center justify-between p-4 mb-4
            text-red-800 bg-red-50 border border-red-100 rounded-2xl shadow-sm">
    <span class="font-semibold text-sm">{{ session('error') }}</span>
    <button @click="show = false" class="text-red-500 hover:text-red-700 font-bold">×</button>
</div>
```

**Validation Errors**
```blade
<div x-data="{ show: true }" x-show="show"
     class="flex items-center justify-between p-4 bg-red-50 border border-red-100
            text-red-800 rounded-2xl shadow-sm">
    <ul class="text-xs list-disc list-inside">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
```

### Empty State
```blade
<tr>
    <td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">
        Belum ada data transaksi.
    </td>
</tr>
```

### Trash Icon (Fixed Bottom)
```blade
<a href="{{ route('transaksi.trashed') }}"
   class="fixed bottom-6 z-50 right-6 lg:right-auto inline-flex items-center
          justify-center w-9 h-9 bg-red-50 hover:bg-red-100 text-red-600
          border border-red-200 rounded-full shadow-lg transition-all duration-300">
    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">...</svg>
</a>
```

Posisi horizontal icon trash menyesuaikan lebar sidebar (collapsed/expanded) via Alpine.js binding di `:style`.

---

## 7. Page Inventory

| # | Halaman | View | Route | Middleware |
|---|---------|------|-------|------------|
| 1 | **Login** | `login.blade.php` | `/login` | guest |
| 2 | **Dashboard** | `dashboard/index.blade.php` | `/dashboard` | auth |
| 3 | **Outlet Index** | `admin/outlet/index.blade.php` | `/dashboard/outlet` | admin |
| 4 | **Outlet Create** | `admin/outlet/create.blade.php` | `/dashboard/outlet/create` | admin |
| 5 | **Outlet Edit** | `admin/outlet/edit.blade.php` | `/dashboard/outlet/{id}/edit` | admin |
| 6 | **Outlet Trashed** | `admin/outlet/trashed.blade.php` | `/dashboard/outlet/trashed` | admin |
| 7 | **User Index** | `admin/user/index.blade.php` | `/dashboard/user` | admin |
| 8 | **User Create** | `admin/user/create.blade.php` | `/dashboard/user/create` | admin |
| 9 | **User Edit** | `admin/user/edit.blade.php` | `/dashboard/user/{id}/edit` | admin |
| 10 | **User Trashed** | `admin/user/trashed.blade.php` | `/dashboard/user/trashed` | admin |
| 11 | **Member Index** | `admin/member/index.blade.php` | `/dashboard/member` | admin,kasir |
| 12 | **Member Create** | `admin/member/create.blade.php` | `/dashboard/member/create` | admin,kasir |
| 13 | **Member Edit** | `admin/member/edit.blade.php` | `/dashboard/member/{id}/edit` | admin,kasir |
| 14 | **Member Trashed** | `admin/member/trashed.blade.php` | `/dashboard/member/trashed` | admin,kasir |
| 15 | **Paket Index** | `admin/paket/index.blade.php` | `/dashboard/paket` | admin,kasir |
| 16 | **Paket Create** | `admin/paket/create.blade.php` | `/dashboard/paket/create` | admin,kasir |
| 17 | **Paket Edit** | `admin/paket/edit.blade.php` | `/dashboard/paket/{id}/edit` | admin,kasir |
| 18 | **Paket Trashed** | `admin/paket/trashed.blade.php` | `/dashboard/paket/trashed` | admin,kasir |
| 19 | **Transaksi Index** | `admin/transaksi/index.blade.php` | `/dashboard/transaksi` | admin,kasir |
| 20 | **Transaksi Create** | `admin/transaksi/create.blade.php` | `/dashboard/transaksi/create` | admin,kasir |
| 21 | **Transaksi Show** | `admin/transaksi/show.blade.php` | `/dashboard/transaksi/{id}` | admin,kasir |
| 22 | **Transaksi Edit** | `admin/transaksi/edit.blade.php` | `/dashboard/transaksi/{id}/edit` | admin,kasir |
| 23 | **Transaksi Trashed** | `admin/transaksi/trashed.blade.php` | `/dashboard/transaksi/trashed` | admin,kasir |

### Dashboard per Role

| Role | Konten |
|------|--------|
| **Admin** | Hero card sambutan + statistik sistem (outlet, user, member) |
| **Kasir** | Hero card sambutan + statistik outlet (transaksi hari ini, pelanggan) |
| **Owner** | Hero card sambutan + pendapatan, transaksi selesai, belum bayar + grafik pendapatan 6 bulan + distribusi status |

---

## 8. Interaction Patterns

### Sidebar
- **Collapsed → hover** → expand (smooth 300ms ease-in-out)
- **Pin toggle** → persist `sidebar_pinned` di `localStorage`
- **Mobile** → overlay muncul dengan backdrop blur + transisi
- **Active page** → highlight indigo dengan `bg-indigo-50 text-indigo-600 font-semibold`

### Notifications
- **Success** → auto-hide setelah 3 detik
- **Error** → tetap tampil sampai user klik `×`
- **Validation errors** → tetap tampil sampai user klik `×`

### Form Interactions
- **Dynamic rows** (transaksi create): tambah/hapus item via Alpine.js `x-for`
- **Loading state**: button submit berubah jadi spinner saat form disubmit (Alpine.js `x-data="{ loading: false }"` + `@submit="loading = true"`)
- **Confirm before delete**: `onsubmit="return confirm('...')"` untuk soft-delete
- **Hover scale** pada tombol: `hover:scale-105` atau `active:scale-95`

### Hover Effects
- Table rows: `hover:bg-slate-50 transition-all`
- Sidebar items: `hover:bg-indigo-50 hover:text-indigo-600`
- Cards: `shadow-sm` default, bisa ditingkatkan jadi `hover:shadow-md`
- Buttons: `transition-all` + color shift

### Print
- Invoice (`show.blade.php`) memiliki stylesheet `@media print` khusus:
  - Sembunyikan sidebar, header, dan tombol aksi
  - Tampilkan hanya `.invoice-print`
  - Gunakan posisi absolut untuk full-width

---

## 9. Responsive Design

| Breakpoint | Perilaku |
|------------|----------|
| **Mobile (< 1024px)** | Sidebar tersembunyi default, buka via hamburger overlay. Sidebar pinned disabled. Trash icon di `right-6`. |
| **Desktop (≥ 1024px)** | Sidebar static di kiri (`lg:translate-x-0 lg:static`). Trash icon di `left: (sidebar_width + 8)px`. |

### Grid Responsiveness
```
1 kolom   → mobile default
2 kolom   → md:grid-cols-2  (form fields)
3 kolom   → md:grid-cols-3  (stat cards)
4 kolom   → md:grid-cols-4  (status distribution)
```

### Table
- Dibungkus `overflow-x-auto` untuk horizontal scroll di layar kecil
- Tidak menggunakan card-grid alternatif untuk mobile — tabel tetap tabel

---

## 10. Future Design Improvements (Roadmap)

### High Priority
- [ ] **Breadcrumb navigation** di header untuk konteks halaman
- [ ] **Search & filter** bar di halaman index (cari member, invoice, dll)
- [ ] **Pagination styling** yang konsisten dengan tema
- [ ] **Dark mode** toggle (Tailwind v4 support via `@media prefers-color-scheme`)

### Medium Priority
- [ ] **Loading skeleton** untuk halaman yang berat (dashboard owner)
- [ ] **Confirmation modal** (Ganti confirm() native dengan modal kustom)
- [ ] **Toast notifications** untuk aksi cepat (update status, dll)
- [ ] **Export buttons** (PDF/Excel) di halaman transaksi

### Low Priority
- [ ] **Animasi transisi** antar halaman (Alpine.js + Tailwind)
- [ ] **Drag & drop** untuk urutan item paket
- [ ] **Icon library** resmi (Heroicons via npm) — sekarang masih inline SVG
- [ ] **Font preload** untuk performa
- [ ] **Progressive Web App (PWA)** support untuk akses offline

---

## Design Tokens Summary

```
Spacing: 4px base (p-4, p-6, gap-6, px-6 py-4)
Radius: rounded-2xl (card), rounded-xl (button/badge), rounded-lg (sidebar item)
Shadow: shadow-sm (card), shadow-lg (avatar), shadow-2xl (login card)
Transition: duration-200 (hover), duration-300 (sidebar), duration-500 (alert dismiss)
Max content width: max-w-7xl (1280px)
```

---

*Documented: May 2026 — Built with Tailwind CSS v4 + Alpine.js 3.x on Laravel 13*
