export default function KpiCard({ title, value }) {
  return <div className="bg-white dark:bg-slate-900 dark:border dark:border-slate-700 p-4 rounded shadow"><p className="text-sm text-slate-500 dark:text-slate-300">{title}</p><p className="text-2xl font-bold">{value}</p></div>;
}
