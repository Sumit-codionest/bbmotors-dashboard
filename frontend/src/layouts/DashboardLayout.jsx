import { Link, Outlet } from 'react-router-dom';

const menus = [
  ['Dashboard', '/'], ['Inventory', '/inventory'], ['Brands', '/brands'], ['Models', '/models'],
  ['Features', '/features'], ['Companies', '/companies'], ['Users', '/users'], ['Master Data', '/masters'], ['Settings', '/settings']
];

export default function DashboardLayout() {
  return (
    <div className="min-h-screen flex">
      <aside className="w-64 bg-slate-900 text-white p-4">
        <h1 className="text-xl font-bold mb-4">car-dealership-dashboard</h1>
        <nav className="space-y-2">{menus.map(([label, path]) => <Link key={path} to={path} className="block px-3 py-2 rounded hover:bg-slate-700">{label}</Link>)}</nav>
      </aside>
      <main className="flex-1 p-6">
        <div className="bg-white p-4 rounded mb-4 shadow">Admin Dashboard</div>
        <Outlet />
      </main>
    </div>
  );
}
