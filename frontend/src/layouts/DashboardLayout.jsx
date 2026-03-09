import { Link, Outlet } from 'react-router-dom';
import { useTheme } from '../context/ThemeContext';

const menus = [
  ['Dashboard', '/'], ['Inventory', '/inventory'], ['Brands', '/brands'], ['Models', '/models'],
  ['Features', '/features'], ['Companies', '/companies'], ['Users', '/users'], ['Master Data', '/masters'], ['Settings', '/settings']
];

export default function DashboardLayout() {
  const { theme, toggleTheme } = useTheme();

  return (
    <div className="min-h-screen flex bg-slate-100 dark:bg-slate-950">
      <aside className="w-64 bg-slate-900 text-white p-4">
        <h1 className="text-xl font-bold mb-4">car-dealership-dashboard</h1>
        <nav className="space-y-2">{menus.map(([label, path]) => <Link key={path} to={path} className="block px-3 py-2 rounded hover:bg-slate-700">{label}</Link>)}</nav>
      </aside>
      <main className="flex-1 p-6">
        <div className="bg-white dark:bg-slate-900 dark:border dark:border-slate-700 p-4 rounded mb-4 shadow flex justify-between items-center">
          <span>Admin Dashboard</span>
          <button onClick={toggleTheme} className="px-3 py-1 rounded bg-slate-800 text-white dark:bg-yellow-400 dark:text-slate-900">
            {theme === 'dark' ? '☀️ Light' : '🌙 Dark'}
          </button>
        </div>
        <Outlet />
      </main>
    </div>
  );
}
